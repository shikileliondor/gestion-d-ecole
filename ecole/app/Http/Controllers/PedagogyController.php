<?php

namespace App\Http\Controllers;

use App\Models\AffectationEnseignant;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\EleveContact;
use App\Models\Enseignant;
use App\Models\Evaluation;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Note;
use App\Models\Niveau;
use App\Models\Periode;
use App\Models\PeriodeVerrouillage;
use App\Models\ProgrammeClasse;
use App\Models\ProgrammeMatiere;
use App\Models\Serie;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PedagogyController extends Controller
{
    public function subjects(Request $request): View
    {
        $query = Matiere::query()->orderBy('nom');
        $search = $request->string('q')->trim()->toString();
        $status = $request->string('status')->toString();

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('nom', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if (in_array($status, ['active', 'inactive'], true)) {
            $query->where('actif', $status === 'active');
        }

        $subjects = $query->get();

        return view('pedagogy.subjects', compact('subjects', 'search', 'status'));
    }

    public function updateSubjectStatus(Request $request, Matiere $subject): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:active,inactive'],
        ]);

        $subject->update([
            'actif' => $data['status'] === 'active',
        ]);

        if ($request->expectsJson()) {
            $rowHtml = view('pedagogy.partials.subject-row', ['subject' => $subject])->render();

            return response()->json([
                'message' => 'Le statut de la matière a été mis à jour.',
                'subject_id' => $subject->id,
                'row_html' => $rowHtml,
            ]);
        }

        return back()->with('status', 'Le statut de la matière a été mis à jour.');
    }

    public function programme(Request $request): View
    {
        $academicYears = $this->academicYears();
        $classes = $this->classes();
        $subjects = $this->subjectsList();

        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $selectedClassId = (int) ($request->input('class_id') ?? 0);
        $selectedClass = $selectedClassId ? Classe::query()->find($selectedClassId) : null;

        $programmeRows = collect();
        $coefficients = collect();
        $teachersBySubject = collect();

        if ($selectedClass) {
            $programmeRows = ProgrammeClasse::query()
                ->where('annee_scolaire_id', $selectedAcademicYearId)
                ->where('classe_id', $selectedClass->id)
                ->get();

            $coefficients = $this->officialCoefficients($selectedAcademicYearId, $selectedClass->niveau_id, $selectedClass->serie_id);

            $teachersBySubject = AffectationEnseignant::query()
                ->where('annee_scolaire_id', $selectedAcademicYearId)
                ->where('classe_id', $selectedClass->id)
                ->get()
                ->groupBy('matiere_id')
                ->map(function ($items) {
                    $enseignantIds = $items->pluck('enseignant_id')->unique()->all();

                    return Enseignant::query()
                        ->whereIn('id', $enseignantIds)
                        ->orderBy('nom')
                        ->get()
                        ->map(fn (Enseignant $enseignant) => trim($enseignant->nom.' '.$enseignant->prenoms));
                });
        }

        $programmeListHtml = view('pedagogy.partials.programme-list', [
            'programmeRows' => $programmeRows,
            'subjects' => $subjects,
            'coefficients' => $coefficients,
            'teachersBySubject' => $teachersBySubject,
        ])->render();

        return view('pedagogy.programme', [
            'academicYears' => $academicYears,
            'classes' => $classes,
            'subjects' => $subjects,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedClassId' => $selectedClassId,
            'selectedClass' => $selectedClass,
            'programmeListHtml' => $programmeListHtml,
        ]);
    }

    public function storeProgrammeSubject(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:annees_scolaires,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:matieres,id'],
        ]);

        ProgrammeClasse::query()->firstOrCreate([
            'annee_scolaire_id' => $data['academic_year_id'],
            'classe_id' => $data['class_id'],
            'matiere_id' => $data['subject_id'],
        ], [
            'actif' => true,
        ]);

        if ($request->expectsJson()) {
            return $this->programmeListResponse($data['academic_year_id'], $data['class_id']);
        }

        return back()->with('status', 'La matière a été ajoutée au programme.');
    }

    public function destroyProgrammeSubject(Request $request, ProgrammeClasse $programme): JsonResponse|RedirectResponse
    {
        $programme->delete();

        AffectationEnseignant::query()
            ->where('annee_scolaire_id', $programme->annee_scolaire_id)
            ->where('classe_id', $programme->classe_id)
            ->where('matiere_id', $programme->matiere_id)
            ->delete();

        if ($request->expectsJson()) {
            return $this->programmeListResponse($programme->annee_scolaire_id, $programme->classe_id);
        }

        return back()->with('status', 'La matière a été retirée du programme.');
    }

    public function assignments(Request $request): View
    {
        $academicYears = $this->academicYears();
        $classes = $this->classes();
        $teachers = $this->teachers();

        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $selectedClassId = (int) ($request->input('class_id') ?? 0);
        $selectedTeacherId = (int) ($request->input('teacher_id') ?? 0);

        $programmeRows = collect();
        $assignments = collect();
        $missingAssignments = collect();
        $teacherAssignments = collect();

        if ($selectedClassId) {
            $programmeRows = ProgrammeClasse::query()
                ->where('annee_scolaire_id', $selectedAcademicYearId)
                ->where('classe_id', $selectedClassId)
                ->get();

            $assignments = AffectationEnseignant::query()
                ->where('annee_scolaire_id', $selectedAcademicYearId)
                ->where('classe_id', $selectedClassId)
                ->get()
                ->groupBy('matiere_id');

            $missingAssignments = $programmeRows->filter(function (ProgrammeClasse $programme) use ($assignments) {
                return ! $assignments->has($programme->matiere_id);
            });
        }

        if ($selectedTeacherId) {
            $teacherAssignments = AffectationEnseignant::query()
                ->where('annee_scolaire_id', $selectedAcademicYearId)
                ->where('enseignant_id', $selectedTeacherId)
                ->get();
        }

        $assignmentsHtml = view('pedagogy.partials.assignments-class', [
            'programmeRows' => $programmeRows,
            'assignments' => $assignments,
            'missingAssignments' => $missingAssignments,
            'subjects' => $this->subjectsList(),
            'teachers' => $teachers,
            'teacherLoads' => $this->teacherLoads($selectedAcademicYearId),
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedClassId' => $selectedClassId,
        ])->render();

        $teacherAssignmentsHtml = view('pedagogy.partials.assignments-teacher', [
            'teacherAssignments' => $teacherAssignments,
            'classes' => $classes,
            'subjects' => $this->subjectsList(),
            'teachers' => $teachers,
        ])->render();

        return view('pedagogy.assignments', [
            'academicYears' => $academicYears,
            'classes' => $classes,
            'teachers' => $teachers,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedClassId' => $selectedClassId,
            'selectedTeacherId' => $selectedTeacherId,
            'assignmentsHtml' => $assignmentsHtml,
            'teacherAssignmentsHtml' => $teacherAssignmentsHtml,
        ]);
    }

    public function storeAssignment(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:annees_scolaires,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:matieres,id'],
            'teacher_id' => ['nullable', 'exists:enseignants,id'],
        ]);

        AffectationEnseignant::query()
            ->where('annee_scolaire_id', $data['academic_year_id'])
            ->where('classe_id', $data['class_id'])
            ->where('matiere_id', $data['subject_id'])
            ->delete();

        if ($data['teacher_id']) {
            AffectationEnseignant::query()->create([
                'annee_scolaire_id' => $data['academic_year_id'],
                'classe_id' => $data['class_id'],
                'matiere_id' => $data['subject_id'],
                'enseignant_id' => $data['teacher_id'],
            ]);
        }

        if ($request->expectsJson()) {
            return $this->assignmentsListResponse($data['academic_year_id'], $data['class_id']);
        }

        return back()->with('status', 'Affectation mise à jour.');
    }

    public function evaluations(Request $request): View
    {
        $academicYears = $this->academicYears();
        $classes = $this->classes();
        $subjects = $this->subjectsList();
        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periods = $this->periods($selectedAcademicYearId);

        $filters = [
            'academic_year_id' => $selectedAcademicYearId,
            'class_id' => (int) ($request->input('class_id') ?? 0),
            'subject_id' => (int) ($request->input('subject_id') ?? 0),
            'period_id' => (int) ($request->input('period_id') ?? 0),
        ];

        $evaluations = $this->evaluationQuery($filters)->get();

        $listHtml = view('pedagogy.partials.evaluations-list', [
            'evaluations' => $evaluations,
            'classes' => $classes,
            'subjects' => $subjects,
            'periods' => $periods,
        ])->render();

        return view('pedagogy.evaluations', [
            'academicYears' => $academicYears,
            'classes' => $classes,
            'subjects' => $subjects,
            'periods' => $periods,
            'filters' => $filters,
            'evaluationsListHtml' => $listHtml,
        ]);
    }

    public function storeEvaluation(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:annees_scolaires,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'subject_id' => ['required', 'exists:matieres,id'],
            'period_id' => ['required', 'exists:periodes,id'],
            'type' => ['required', 'in:INTERRO,DEVOIR,COMPOSITION,ORAL,PRATIQUE'],
            'title' => ['nullable', 'string', 'max:120'],
            'date' => ['required', 'date'],
            'scale' => ['required', 'numeric', 'min:1'],
        ]);

        $hasSubject = ProgrammeClasse::query()
            ->where('annee_scolaire_id', $data['academic_year_id'])
            ->where('classe_id', $data['class_id'])
            ->where('matiere_id', $data['subject_id'])
            ->exists();

        if (! $hasSubject) {
            return response()->json([
                'message' => 'Cette matière ne fait pas partie du programme de la classe.',
            ], 422);
        }

        Evaluation::query()->create([
            'annee_scolaire_id' => $data['academic_year_id'],
            'classe_id' => $data['class_id'],
            'matiere_id' => $data['subject_id'],
            'periode_id' => $data['period_id'],
            'type' => $data['type'],
            'titre' => $data['title'],
            'date_evaluation' => $data['date'],
            'note_sur' => $data['scale'],
            'statut' => 'BROUILLON',
        ]);

        if ($request->expectsJson()) {
            return $this->evaluationListResponse($data['academic_year_id'], $data['class_id'], $data['subject_id'], $data['period_id']);
        }

        return back()->with('status', "L'évaluation a été créée.");
    }

    public function updateEvaluation(Request $request, Evaluation $evaluation): JsonResponse|RedirectResponse
    {
        if ($evaluation->statut === 'CLOTUREE') {
            return response()->json([
                'message' => 'Cette évaluation est clôturée et ne peut plus être modifiée.',
            ], 422);
        }

        $data = $request->validate([
            'period_id' => ['required', 'exists:periodes,id'],
            'type' => ['required', 'in:INTERRO,DEVOIR,COMPOSITION,ORAL,PRATIQUE'],
            'title' => ['nullable', 'string', 'max:120'],
            'date' => ['required', 'date'],
            'scale' => ['required', 'numeric', 'min:1'],
        ]);

        $evaluation->update([
            'periode_id' => $data['period_id'],
            'type' => $data['type'],
            'titre' => $data['title'],
            'date_evaluation' => $data['date'],
            'note_sur' => $data['scale'],
        ]);

        if ($request->expectsJson()) {
            return $this->evaluationListResponse(
                $evaluation->annee_scolaire_id,
                $evaluation->classe_id,
                $evaluation->matiere_id,
                $evaluation->periode_id
            );
        }

        return back()->with('status', "L'évaluation a été mise à jour.");
    }

    public function updateEvaluationStatus(Request $request, Evaluation $evaluation): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:BROUILLON,PUBLIEE,CLOTUREE'],
        ]);

        $evaluation->update([
            'statut' => $data['status'],
        ]);

        if ($request->expectsJson()) {
            return $this->evaluationListResponse(
                $evaluation->annee_scolaire_id,
                $evaluation->classe_id,
                $evaluation->matiere_id,
                $evaluation->periode_id
            );
        }

        return back()->with('status', "Le statut de l'évaluation a été mis à jour.");
    }

    public function destroyEvaluation(Request $request, Evaluation $evaluation): JsonResponse|RedirectResponse
    {
        if ($evaluation->statut === 'CLOTUREE') {
            return response()->json([
                'message' => 'Cette évaluation est clôturée et ne peut plus être supprimée.',
            ], 422);
        }

        $evaluation->delete();

        if ($request->expectsJson()) {
            return $this->evaluationListResponse(
                $evaluation->annee_scolaire_id,
                $evaluation->classe_id,
                $evaluation->matiere_id,
                $evaluation->periode_id
            );
        }

        return back()->with('status', "L'évaluation a été supprimée.");
    }

    public function grades(Request $request): View
    {
        $academicYears = $this->academicYears();
        $classes = $this->classes();
        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periods = $this->periods($selectedAcademicYearId);
        $selectedClassId = (int) ($request->input('class_id') ?? 0);
        $selectedPeriodId = (int) ($request->input('period_id') ?? 0);
        $selectedEvaluationId = (int) ($request->input('evaluation_id') ?? 0);

        $evaluations = $this->evaluationQuery([
            'academic_year_id' => $selectedAcademicYearId,
            'class_id' => $selectedClassId,
            'period_id' => $selectedPeriodId,
        ])->get();

        $selectedEvaluation = $selectedEvaluationId ? Evaluation::query()->find($selectedEvaluationId) : null;

        $students = collect();
        $notes = collect();
        $studentInsights = collect();

        if ($selectedEvaluation) {
            $students = Inscription::query()
                ->where('annee_scolaire_id', $selectedEvaluation->annee_scolaire_id)
                ->where('classe_id', $selectedEvaluation->classe_id)
                ->get()
                ->map(function (Inscription $inscription) {
                    $eleve = Eleve::query()->find($inscription->eleve_id);
                    $inscription->setRelation('eleve', $eleve);
                    return $inscription;
                });

            $notes = Note::query()
                ->where('evaluation_id', $selectedEvaluation->id)
                ->get()
                ->keyBy('inscription_id');

            $studentInsights = $this->buildGradeInsights($selectedEvaluation, $students);
        }

        return view('pedagogy.grades', [
            'academicYears' => $academicYears,
            'classes' => $classes,
            'periods' => $periods,
            'evaluations' => $evaluations,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedClassId' => $selectedClassId,
            'selectedPeriodId' => $selectedPeriodId,
            'selectedEvaluation' => $selectedEvaluation,
            'students' => $students,
            'notes' => $notes,
            'studentInsights' => $studentInsights,
        ]);
    }

    public function storeGrades(Request $request, Evaluation $evaluation): JsonResponse|RedirectResponse
    {
        if ($evaluation->statut === 'CLOTUREE') {
            return response()->json([
                'message' => 'Cette évaluation est clôturée. La saisie est bloquée.',
            ], 422);
        }

        $lock = PeriodeVerrouillage::query()
            ->where('annee_scolaire_id', $evaluation->annee_scolaire_id)
            ->where('classe_id', $evaluation->classe_id)
            ->where('periode_id', $evaluation->periode_id)
            ->first();

        if ($lock?->verrouille) {
            return response()->json([
                'message' => 'La période est verrouillée. La saisie est bloquée.',
            ], 422);
        }

        $data = $request->validate([
            'notes' => ['required', 'array'],
            'notes.*.valeur' => ['nullable', 'numeric', 'min:0'],
            'notes.*.statut' => ['nullable', 'in:ABS,EXC,DISP'],
        ]);

        $entryDate = now()->toDateString();

        foreach ($data['notes'] as $inscriptionId => $entry) {
            $noteValue = $entry['valeur'] ?? null;
            $status = $entry['statut'] ?? null;

            if ($status) {
                $noteValue = 0;
            }

            if ($noteValue !== null && $noteValue !== '' && $noteValue > $evaluation->note_sur) {
                return response()->json([
                    'message' => 'Certaines notes dépassent le barème de l\'évaluation.',
                ], 422);
            }

            Note::query()->updateOrCreate([
                'evaluation_id' => $evaluation->id,
                'inscription_id' => $inscriptionId,
            ], [
                'valeur' => $noteValue ?? 0,
                'statut' => $status,
                'periode_id' => $evaluation->periode_id,
                'date_saisie' => $entryDate,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Les notes ont été enregistrées.',
            ]);
        }

        return back()->with('status', 'Les notes ont été enregistrées.');
    }

    public function reportCards(Request $request): View
    {
        $academicYears = $this->academicYears();
        $classes = $this->classes();
        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periods = $this->periods($selectedAcademicYearId);
        $selectedClassId = (int) ($request->input('class_id') ?? 0);
        $selectedPeriodId = (int) ($request->input('period_id') ?? 0);

        $reportData = collect();
        $lockStatus = null;

        if ($selectedClassId && $selectedPeriodId) {
            $reportData = $this->buildReportCardData($selectedAcademicYearId, $selectedClassId, $selectedPeriodId);
            $lockStatus = PeriodeVerrouillage::query()
                ->where('annee_scolaire_id', $selectedAcademicYearId)
                ->where('classe_id', $selectedClassId)
                ->where('periode_id', $selectedPeriodId)
                ->first();
        }

        return view('pedagogy.report-cards', [
            'academicYears' => $academicYears,
            'classes' => $classes,
            'periods' => $periods,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedClassId' => $selectedClassId,
            'selectedPeriodId' => $selectedPeriodId,
            'reportData' => $reportData,
            'lockStatus' => $lockStatus,
        ]);
    }

    public function toggleReportLock(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:annees_scolaires,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'period_id' => ['required', 'exists:periodes,id'],
        ]);

        $lock = PeriodeVerrouillage::query()->firstOrCreate([
            'annee_scolaire_id' => $data['academic_year_id'],
            'classe_id' => $data['class_id'],
            'periode_id' => $data['period_id'],
        ], [
            'verrouille' => false,
        ]);

        $lock->update(['verrouille' => ! $lock->verrouille]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $lock->verrouille ? 'Période verrouillée.' : 'Période déverrouillée.',
                'locked' => $lock->verrouille,
            ]);
        }

        return back()->with('status', $lock->verrouille ? 'Période verrouillée.' : 'Période déverrouillée.');
    }

    public function reportCardsPdf(Request $request, Classe $class, Periode $period)
    {
        $academicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $data = $this->buildReportCardData($academicYearId, $class->id, $period->id);

        $pdf = Pdf::loadView('pedagogy.pdf.report-cards', [
            'class' => $class,
            'period' => $period,
            'reportData' => $data,
        ]);

        return $pdf->download("bulletins-{$class->nom}-{$period->libelle}.pdf");
    }

    public function reportCardsEmail(Request $request, Classe $class, Periode $period): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:annees_scolaires,id'],
        ]);

        $reportData = $this->buildReportCardData($data['academic_year_id'], $class->id, $period->id);

        $sent = 0;
        $missing = 0;

        foreach ($reportData as $index => $entry) {
            $student = $entry['student'];
            if (! $student) {
                $missing++;
                continue;
            }

            $contact = EleveContact::query()->where('eleve_id', $student->id)->first();
            $email = $contact?->email;

            if (! $email) {
                $missing++;
                continue;
            }

            $pdf = Pdf::loadView('pedagogy.pdf.report-card-student', [
                'class' => $class,
                'period' => $period,
                'entry' => $entry,
                'rank' => $index + 1,
            ]);

            Mail::send([], [], function ($message) use ($email, $student, $class, $period, $pdf) {
                $message->to($email)
                    ->subject("Bulletin {$student->nom} {$student->prenoms} - {$class->nom} ({$period->libelle})")
                    ->setBody('Veuillez trouver ci-joint le bulletin de notes.', 'text/plain')
                    ->attachData($pdf->output(), "bulletin-{$student->nom}-{$student->prenoms}.pdf");
            });

            $sent++;
        }

        $message = "Bulletins envoyés : {$sent}.";
        if ($missing > 0) {
            $message .= " {$missing} élève(s) sans email configuré.";
        }

        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
            ]);
        }

        return back()->with('status', $message);
    }

    public function transcripts(Request $request): View
    {
        $academicYears = $this->academicYears();
        $students = Eleve::query()->orderBy('nom')->orderBy('prenoms')->get();

        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periods = $this->periods($selectedAcademicYearId);
        $selectedStudentId = (int) ($request->input('student_id') ?? 0);
        $selectedPeriodId = (int) ($request->input('period_id') ?? 0);

        $reportData = collect();

        if ($selectedStudentId) {
            $reportData = $this->buildTranscriptData($selectedAcademicYearId, $selectedStudentId, $selectedPeriodId);
        }

        return view('pedagogy.transcripts', [
            'academicYears' => $academicYears,
            'periods' => $periods,
            'students' => $students,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedStudentId' => $selectedStudentId,
            'selectedPeriodId' => $selectedPeriodId,
            'reportData' => $reportData,
        ]);
    }

    public function transcriptPdf(Request $request, Eleve $student)
    {
        $academicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periodId = (int) ($request->input('period_id') ?? 0);

        $reportData = $this->buildTranscriptData($academicYearId, $student->id, $periodId);

        $pdf = Pdf::loadView('pedagogy.pdf.transcript', [
            'student' => $student,
            'reportData' => $reportData,
        ]);

        return $pdf->download("releve-{$student->nom}-{$student->prenoms}.pdf");
    }

    public function studentReportCards(Request $request): View
    {
        $academicYears = $this->academicYears();
        $students = Eleve::query()->orderBy('nom')->orderBy('prenoms')->get();

        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periods = $this->periods($selectedAcademicYearId);
        $selectedStudentId = (int) ($request->input('student_id') ?? 0);
        $selectedPeriodId = (int) ($request->input('period_id') ?? 0);

        $reportData = collect();

        if ($selectedStudentId && $selectedPeriodId) {
            $reportData = $this->buildStudentReportCardData($selectedAcademicYearId, $selectedStudentId, $selectedPeriodId);
        }

        return view('pedagogy.student-report-cards', [
            'academicYears' => $academicYears,
            'periods' => $periods,
            'students' => $students,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedStudentId' => $selectedStudentId,
            'selectedPeriodId' => $selectedPeriodId,
            'reportData' => $reportData,
        ]);
    }

    public function leaderboard(Request $request): View
    {
        $academicYears = $this->academicYears();
        $classes = $this->classes();
        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periods = $this->periods($selectedAcademicYearId);
        $subjects = $this->subjectsList();
        $selectedClassId = (int) ($request->input('class_id') ?? 0);
        $selectedPeriodId = (int) ($request->input('period_id') ?? 0);
        $selectedSubjectId = (int) ($request->input('subject_id') ?? 0);

        $data = collect();
        $rankingMode = $selectedSubjectId ? 'subject' : 'general';

        if ($selectedClassId && $selectedPeriodId) {
            $data = $selectedSubjectId
                ? $this->buildSubjectRankingData($selectedAcademicYearId, $selectedClassId, $selectedPeriodId, $selectedSubjectId)
                : $this->buildReportCardData($selectedAcademicYearId, $selectedClassId, $selectedPeriodId);
        }

        return view('pedagogy.leaderboard', [
            'academicYears' => $academicYears,
            'classes' => $classes,
            'periods' => $periods,
            'subjects' => $subjects,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedClassId' => $selectedClassId,
            'selectedPeriodId' => $selectedPeriodId,
            'selectedSubjectId' => $selectedSubjectId,
            'rankingMode' => $rankingMode,
            'rankingData' => $data,
        ]);
    }

    public function resultsDashboard(Request $request): View
    {
        $academicYears = $this->academicYears();
        $classes = $this->classes();
        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periods = $this->periods($selectedAcademicYearId);
        $subjects = $this->subjectsList();
        $selectedClassId = (int) ($request->input('class_id') ?? 0);
        $selectedPeriodId = (int) ($request->input('period_id') ?? 0);
        $selectedSubjectId = (int) ($request->input('subject_id') ?? 0);
        $search = $request->string('q')->trim()->toString();

        $studentsQuery = Inscription::query()
            ->where('annee_scolaire_id', $selectedAcademicYearId)
            ->when($selectedClassId, fn ($query, $classId) => $query->where('classe_id', $classId));

        if ($search !== '') {
            $studentsQuery->where(function ($query) use ($search) {
                $query->whereHas('eleve', function ($builder) use ($search) {
                    $builder->where('nom', 'like', "%{$search}%")
                        ->orWhere('prenoms', 'like', "%{$search}%")
                        ->orWhere('matricule', 'like', "%{$search}%");
                });
            });
        }

        $students = $studentsQuery
            ->get()
            ->map(function (Inscription $inscription) {
                $inscription->setRelation('eleve', Eleve::query()->find($inscription->eleve_id));
                return $inscription;
            });

        $periodIdForScores = $selectedPeriodId ?: ($periods->first()?->id ?? 0);
        $evaluationQuery = Evaluation::query()
            ->where('annee_scolaire_id', $selectedAcademicYearId)
            ->when($selectedClassId, fn ($query, $classId) => $query->where('classe_id', $classId))
            ->when($periodIdForScores, fn ($query, $periodId) => $query->where('periode_id', $periodId))
            ->when($selectedSubjectId, fn ($query, $subjectId) => $query->where('matiere_id', $subjectId))
            ->where('statut', '!=', 'BROUILLON');

        $evaluations = $evaluationQuery->get();
        $notes = Note::query()
            ->whereIn('evaluation_id', $evaluations->pluck('id'))
            ->get();

        $scorecards = $selectedClassId && $periodIdForScores
            ? $this->buildStudentScorecards(
                $students,
                $evaluations,
                $notes,
                $selectedAcademicYearId,
                $selectedClassId,
                $selectedSubjectId ?: null
            )
            : collect();

        $dashboardStats = [
            'student_count' => $students->count(),
            'evaluation_count' => $evaluations->count(),
            'average' => $scorecards->count() ? round($scorecards->avg('average'), 2) : null,
        ];

        $activePeriodId = $selectedPeriodId ?: ($periods->first()?->id ?? 0);
        $evaluationCounts = Evaluation::query()
            ->where('annee_scolaire_id', $selectedAcademicYearId)
            ->when($activePeriodId, fn ($query, $periodId) => $query->where('periode_id', $periodId))
            ->where('statut', '!=', 'BROUILLON')
            ->select('classe_id', DB::raw('count(*) as total'))
            ->groupBy('classe_id')
            ->pluck('total', 'classe_id');

        $classAverages = $classes->map(function (Classe $classe) use ($selectedAcademicYearId, $activePeriodId, $evaluationCounts) {
            $average = null;

            if ($activePeriodId) {
                $reportData = $this->buildReportCardData($selectedAcademicYearId, $classe->id, $activePeriodId);
                $average = $reportData->count() ? round($reportData->avg('average'), 2) : null;
            }

            return [
                'id' => $classe->id,
                'name' => $classe->name,
                'average' => $average,
                'evaluation_count' => $evaluationCounts->get($classe->id, 0),
            ];
        });

        $averageTrend = collect();
        if ($selectedClassId) {
            $averageTrend = $periods->map(function (Periode $period) use ($selectedAcademicYearId, $selectedClassId) {
                $reportData = $this->buildReportCardData($selectedAcademicYearId, $selectedClassId, $period->id);

                return [
                    'label' => $period->libelle,
                    'average' => $reportData->count() ? round($reportData->avg('average'), 2) : null,
                ];
            });
        }

        $topStudents = $scorecards
            ->filter(fn ($entry) => $entry['average'] !== null)
            ->sortByDesc('average')
            ->take(5)
            ->values();

        $alerts = [];
        if ($selectedClassId && $activePeriodId) {
            $lowAverageCount = $scorecards->where('average', '<', 10)->count();
            $evaluationCount = $evaluationCounts->get($selectedClassId, 0);

            if ($evaluationCount === 0) {
                $alerts[] = 'Aucune évaluation validée pour la période active.';
            }
            if ($lowAverageCount > 0) {
                $alerts[] = "{$lowAverageCount} élève(s) sous la moyenne.";
            }
        }

        $subjectAverages = $this->buildSubjectAverages(
            $selectedAcademicYearId,
            $selectedClassId,
            $periodIdForScores,
            $selectedSubjectId ?: null
        );

        return view('pedagogy.results-dashboard', [
            'academicYears' => $academicYears,
            'classes' => $classes,
            'periods' => $periods,
            'subjects' => $subjects,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedClassId' => $selectedClassId,
            'selectedPeriodId' => $selectedPeriodId,
            'selectedSubjectId' => $selectedSubjectId,
            'search' => $search,
            'scorecards' => $scorecards,
            'dashboardStats' => $dashboardStats,
            'classAverages' => $classAverages,
            'averageTrend' => $averageTrend,
            'topStudents' => $topStudents,
            'alerts' => $alerts,
            'subjectAverages' => $subjectAverages,
        ]);
    }

    public function resultsAnalytics(Request $request): View
    {
        $academicYears = $this->academicYears();
        $classes = $this->classes();
        $selectedAcademicYearId = $this->resolveAcademicYearId($request->integer('academic_year_id')) ?? 0;
        $periods = $this->periods($selectedAcademicYearId);
        $selectedClassId = (int) ($request->input('class_id') ?? 0);
        $selectedPeriodId = (int) ($request->input('period_id') ?? 0);
        $activePeriodId = $selectedPeriodId ?: ($periods->first()?->id ?? 0);

        $evaluationCounts = Evaluation::query()
            ->where('annee_scolaire_id', $selectedAcademicYearId)
            ->when($activePeriodId, fn ($query, $periodId) => $query->where('periode_id', $periodId))
            ->where('statut', '!=', 'BROUILLON')
            ->select('classe_id', DB::raw('count(*) as total'))
            ->groupBy('classe_id')
            ->pluck('total', 'classe_id');

        $classAverages = $classes->map(function (Classe $classe) use ($selectedAcademicYearId, $activePeriodId, $evaluationCounts) {
            $average = null;

            if ($activePeriodId) {
                $reportData = $this->buildReportCardData($selectedAcademicYearId, $classe->id, $activePeriodId);
                $average = $reportData->count() ? round($reportData->avg('average'), 2) : null;
            }

            return [
                'id' => $classe->id,
                'name' => $classe->name,
                'average' => $average,
                'evaluation_count' => $evaluationCounts->get($classe->id, 0),
            ];
        });

        $trendSeries = collect();
        $topStudents = collect();
        if ($selectedClassId) {
            $trendSeries = $periods->map(function (Periode $period) use ($selectedAcademicYearId, $selectedClassId) {
                $reportData = $this->buildReportCardData($selectedAcademicYearId, $selectedClassId, $period->id);

                return [
                    'label' => $period->libelle,
                    'average' => $reportData->count() ? round($reportData->avg('average'), 2) : null,
                ];
            });

            if ($activePeriodId) {
                $reportData = $this->buildReportCardData($selectedAcademicYearId, $selectedClassId, $activePeriodId);
                $topStudents = $reportData
                    ->filter(fn ($entry) => $entry['average'] !== null)
                    ->sortByDesc('average')
                    ->take(6)
                    ->values();
            }
        }

        return view('pedagogy.results-analytics', [
            'academicYears' => $academicYears,
            'classes' => $classes,
            'periods' => $periods,
            'selectedAcademicYearId' => $selectedAcademicYearId,
            'selectedClassId' => $selectedClassId,
            'selectedPeriodId' => $selectedPeriodId,
            'classAverages' => $classAverages,
            'trendSeries' => $trendSeries,
            'topStudents' => $topStudents,
        ]);
    }

    private function academicYears()
    {
        return AnneeScolaire::query()
            ->orderByDesc('date_debut')
            ->get()
            ->each(fn (AnneeScolaire $annee) => $annee->setAttribute('name', $annee->libelle));
    }

    private function classes()
    {
        $levels = Niveau::query()->orderBy('ordre')->get()->keyBy('id');
        $series = Serie::query()->orderBy('code')->get()->keyBy('id');

        return Classe::query()
            ->orderBy('nom')
            ->get()
            ->each(function (Classe $classe) use ($levels, $series) {
                $classe->setAttribute('name', $classe->nom);
                $classe->setAttribute('level', optional($levels->get($classe->niveau_id))->code);
                $classe->setAttribute('series', optional($series->get($classe->serie_id))->code);
            });
    }

    private function subjectsList()
    {
        return Matiere::query()
            ->orderBy('nom')
            ->get()
            ->each(fn (Matiere $matiere) => $matiere->setAttribute('name', $matiere->nom));
    }

    private function teachers()
    {
        return Enseignant::query()
            ->orderBy('nom')
            ->orderBy('prenoms')
            ->get()
            ->each(function (Enseignant $enseignant) {
                $enseignant->setAttribute('name', trim($enseignant->nom.' '.$enseignant->prenoms));
            });
    }

    private function periods(?int $academicYearId = null)
    {
        return Periode::query()
            ->when($academicYearId, fn ($query, $yearId) => $query->where('annee_scolaire_id', $yearId))
            ->orderBy('ordre')
            ->get();
    }

    private function teacherLoads(int $academicYearId)
    {
        if (! $academicYearId) {
            return collect();
        }

        return AffectationEnseignant::query()
            ->select('enseignant_id', DB::raw('count(*) as total'))
            ->where('annee_scolaire_id', $academicYearId)
            ->groupBy('enseignant_id')
            ->pluck('total', 'enseignant_id');
    }

    private function officialCoefficients(int $academicYearId, ?int $levelId, ?int $serieId)
    {
        if (! $academicYearId || ! $levelId) {
            return collect();
        }

        return ProgrammeMatiere::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('niveau_id', $levelId)
            ->when($serieId, fn ($query) => $query->where('serie_id', $serieId))
            ->get()
            ->keyBy('matiere_id');
    }

    private function programmeListResponse(int $academicYearId, int $classId): JsonResponse
    {
        $classe = Classe::query()->find($classId);
        $programmeRows = ProgrammeClasse::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->get();

        $coefficients = $classe
            ? $this->officialCoefficients($academicYearId, $classe->niveau_id, $classe->serie_id)
            : collect();

        $teachersBySubject = AffectationEnseignant::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->get()
            ->groupBy('matiere_id')
            ->map(function ($items) {
                $enseignantIds = $items->pluck('enseignant_id')->unique()->all();

                return Enseignant::query()
                    ->whereIn('id', $enseignantIds)
                    ->orderBy('nom')
                    ->get()
                    ->map(fn (Enseignant $enseignant) => trim($enseignant->nom.' '.$enseignant->prenoms));
            });

        $listHtml = view('pedagogy.partials.programme-list', [
            'programmeRows' => $programmeRows,
            'subjects' => $this->subjectsList(),
            'coefficients' => $coefficients,
            'teachersBySubject' => $teachersBySubject,
        ])->render();

        return response()->json([
            'message' => 'Programme mis à jour.',
            'list_html' => $listHtml,
        ]);
    }

    private function assignmentsListResponse(int $academicYearId, int $classId): JsonResponse
    {
        $programmeRows = ProgrammeClasse::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->get();

        $assignments = AffectationEnseignant::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->get()
            ->groupBy('matiere_id');

        $missingAssignments = $programmeRows->filter(function (ProgrammeClasse $programme) use ($assignments) {
            return ! $assignments->has($programme->matiere_id);
        });

        $listHtml = view('pedagogy.partials.assignments-class', [
            'programmeRows' => $programmeRows,
            'assignments' => $assignments,
            'missingAssignments' => $missingAssignments,
            'subjects' => $this->subjectsList(),
            'teachers' => $this->teachers(),
            'teacherLoads' => $this->teacherLoads($academicYearId),
            'selectedAcademicYearId' => $academicYearId,
            'selectedClassId' => $classId,
        ])->render();

        return response()->json([
            'message' => 'Affectations mises à jour.',
            'list_html' => $listHtml,
        ]);
    }

    private function evaluationQuery(array $filters)
    {
        return Evaluation::query()
            ->when($filters['academic_year_id'] ?? null, fn ($query, $year) => $query->where('annee_scolaire_id', $year))
            ->when($filters['class_id'] ?? null, fn ($query, $classId) => $query->where('classe_id', $classId))
            ->when($filters['subject_id'] ?? null, fn ($query, $subjectId) => $query->where('matiere_id', $subjectId))
            ->when($filters['period_id'] ?? null, fn ($query, $periodId) => $query->where('periode_id', $periodId))
            ->orderByDesc('date_evaluation');
    }

    private function evaluationListResponse(int $academicYearId, int $classId, int $subjectId, int $periodId): JsonResponse
    {
        $evaluations = $this->evaluationQuery([
            'academic_year_id' => $academicYearId,
            'class_id' => $classId,
            'subject_id' => $subjectId,
            'period_id' => $periodId,
        ])->get();

        $listHtml = view('pedagogy.partials.evaluations-list', [
            'evaluations' => $evaluations,
            'classes' => $this->classes(),
            'subjects' => $this->subjectsList(),
            'periods' => $this->periods($academicYearId),
        ])->render();

        return response()->json([
            'message' => "Liste d'évaluations mise à jour.",
            'list_html' => $listHtml,
        ]);
    }

    private function buildReportCardData(int $academicYearId, int $classId, int $periodId)
    {
        $classe = Classe::query()->find($classId);
        if (! $classe) {
            return collect();
        }

        $programmeRows = ProgrammeClasse::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->get();

        $coefficients = $this->officialCoefficients($academicYearId, $classe->niveau_id, $classe->serie_id);

        $evaluations = Evaluation::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->where('periode_id', $periodId)
            ->where('statut', '!=', 'BROUILLON')
            ->get();

        $evaluationIds = $evaluations->pluck('id');

        $notes = Note::query()
            ->whereIn('evaluation_id', $evaluationIds)
            ->get();

        $students = Inscription::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->get()
            ->map(function (Inscription $inscription) {
                $inscription->setRelation('eleve', Eleve::query()->find($inscription->eleve_id));
                return $inscription;
            });

        $subjects = Matiere::query()->whereIn('id', $programmeRows->pluck('matiere_id'))->get()->keyBy('id');

        $report = $students->map(function (Inscription $inscription) use ($programmeRows, $coefficients, $evaluations, $notes, $subjects) {
            $subjectAverages = [];
            $totalPoints = 0;
            $totalCoefficients = 0;

            foreach ($programmeRows as $programme) {
                $subjectEvaluations = $evaluations->where('matiere_id', $programme->matiere_id);
                $subjectNotes = $notes->whereIn('evaluation_id', $subjectEvaluations->pluck('id'))
                    ->where('inscription_id', $inscription->id)
                    ->whereNull('statut');

                $average = null;
                if ($subjectNotes->count() > 0) {
                    $average = round($subjectNotes->avg('valeur'), 2);
                }

                $coefficient = optional($coefficients->get($programme->matiere_id))->coefficient;
                $coefficient = $coefficient ? (float) $coefficient : null;

                if ($average !== null && $coefficient) {
                    $totalPoints += $average * $coefficient;
                    $totalCoefficients += $coefficient;
                }

                $subjectAverages[] = [
                    'subject' => $subjects->get($programme->matiere_id)?->nom ?? 'Matière',
                    'average' => $average,
                    'coefficient' => $coefficient,
                ];
            }

            $generalAverage = $totalCoefficients > 0 ? round($totalPoints / $totalCoefficients, 2) : null;

            return [
                'student' => $inscription->eleve,
                'subjects' => $subjectAverages,
                'average' => $generalAverage,
            ];
        });

        $sorted = $report->sortByDesc(fn ($entry) => $entry['average'] ?? -1)->values();

        return $sorted->values();
    }

    private function buildTranscriptData(int $academicYearId, int $studentId, int $periodId)
    {
        $inscriptions = Inscription::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('eleve_id', $studentId)
            ->get();

        $data = [];

        foreach ($inscriptions as $inscription) {
            $classe = Classe::query()->find($inscription->classe_id);
            if (! $classe) {
                continue;
            }
            $programmeRows = ProgrammeClasse::query()
                ->where('annee_scolaire_id', $academicYearId)
                ->where('classe_id', $classe->id)
                ->get();

            $coefficients = $this->officialCoefficients($academicYearId, $classe->niveau_id, $classe->serie_id);

            $evaluationQuery = Evaluation::query()
                ->where('annee_scolaire_id', $academicYearId)
                ->where('classe_id', $classe->id)
                ->where('statut', '!=', 'BROUILLON');

            if ($periodId) {
                $evaluationQuery->where('periode_id', $periodId);
            }

            $evaluations = $evaluationQuery->get();
            $evaluationIds = $evaluations->pluck('id');

            $notes = Note::query()
                ->whereIn('evaluation_id', $evaluationIds)
                ->where('inscription_id', $inscription->id)
                ->get();

            $subjects = Matiere::query()->whereIn('id', $programmeRows->pluck('matiere_id'))->get()->keyBy('id');

            $subjectRows = [];
            $totalPoints = 0;
            $totalCoefficients = 0;

            foreach ($programmeRows as $programme) {
                $subjectEvaluations = $evaluations->where('matiere_id', $programme->matiere_id);
                $subjectNotes = $notes->whereIn('evaluation_id', $subjectEvaluations->pluck('id'))
                    ->whereNull('statut');

                $average = null;
                if ($subjectNotes->count() > 0) {
                    $average = round($subjectNotes->avg('valeur'), 2);
                }

                $coefficient = optional($coefficients->get($programme->matiere_id))->coefficient;
                $coefficient = $coefficient ? (float) $coefficient : null;

                if ($average !== null && $coefficient) {
                    $totalPoints += $average * $coefficient;
                    $totalCoefficients += $coefficient;
                }

                $subjectRows[] = [
                    'subject' => $subjects->get($programme->matiere_id)?->nom ?? 'Matière',
                    'average' => $average,
                    'coefficient' => $coefficient,
                ];
            }

            $generalAverage = $totalCoefficients > 0 ? round($totalPoints / $totalCoefficients, 2) : null;

            $data[] = [
                'class' => $classe,
                'subjects' => $subjectRows,
                'average' => $generalAverage,
            ];
        }

        return collect($data);
    }

    private function buildGradeInsights(Evaluation $evaluation, $students)
    {
        $evaluations = Evaluation::query()
            ->where('annee_scolaire_id', $evaluation->annee_scolaire_id)
            ->where('classe_id', $evaluation->classe_id)
            ->where('periode_id', $evaluation->periode_id)
            ->where('statut', '!=', 'BROUILLON')
            ->get();

        $notes = Note::query()
            ->whereIn('evaluation_id', $evaluations->pluck('id'))
            ->whereNull('statut')
            ->get();

        $programmeRows = ProgrammeClasse::query()
            ->where('annee_scolaire_id', $evaluation->annee_scolaire_id)
            ->where('classe_id', $evaluation->classe_id)
            ->get();

        return $students->mapWithKeys(function (Inscription $inscription) use ($evaluation, $notes, $evaluations, $programmeRows) {
            $subjectEvaluations = $evaluations->where('matiere_id', $evaluation->matiere_id);
            $subjectNotes = $notes->whereIn('evaluation_id', $subjectEvaluations->pluck('id'))
                ->where('inscription_id', $inscription->id);

            $subjectAverage = $subjectNotes->count() ? round($subjectNotes->avg('valeur'), 2) : null;

            $totalPoints = 0;
            $totalSubjects = 0;

            foreach ($programmeRows as $programme) {
                $programmeEvaluations = $evaluations->where('matiere_id', $programme->matiere_id);
                $programmeNotes = $notes->whereIn('evaluation_id', $programmeEvaluations->pluck('id'))
                    ->where('inscription_id', $inscription->id);

                if ($programmeNotes->count() > 0) {
                    $totalPoints += $programmeNotes->avg('valeur');
                    $totalSubjects += 1;
                }
            }

            $generalAverage = $totalSubjects > 0 ? round($totalPoints / $totalSubjects, 2) : null;

            return [
                $inscription->id => [
                    'subject_average' => $subjectAverage,
                    'general_average' => $generalAverage,
                ],
            ];
        });
    }

    private function buildStudentReportCardData(int $academicYearId, int $studentId, int $periodId)
    {
        $inscriptions = Inscription::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('eleve_id', $studentId)
            ->get();

        $results = [];

        foreach ($inscriptions as $inscription) {
            $classe = Classe::query()->find($inscription->classe_id);
            if (! $classe) {
                continue;
            }

            $reportCards = $this->buildReportCardData($academicYearId, $classe->id, $periodId);
            $index = $reportCards->search(function ($entry) use ($studentId) {
                return $entry['student']?->id === $studentId;
            });

            if ($index === false) {
                continue;
            }

            $entry = $reportCards->get($index);

            $results[] = [
                'class' => $classe,
                'rank' => $index + 1,
                'average' => $entry['average'] ?? null,
                'subjects' => $entry['subjects'] ?? [],
            ];
        }

        return collect($results);
    }

    private function buildSubjectRankingData(int $academicYearId, int $classId, int $periodId, int $subjectId)
    {
        $evaluations = Evaluation::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->where('periode_id', $periodId)
            ->where('matiere_id', $subjectId)
            ->where('statut', '!=', 'BROUILLON')
            ->get();

        $notes = Note::query()
            ->whereIn('evaluation_id', $evaluations->pluck('id'))
            ->whereNull('statut')
            ->get();

        $students = Inscription::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->get()
            ->map(function (Inscription $inscription) {
                $inscription->setRelation('eleve', Eleve::query()->find($inscription->eleve_id));
                return $inscription;
            });

        $subject = Matiere::query()->find($subjectId);

        $ranking = $students->map(function (Inscription $inscription) use ($notes, $evaluations, $subject) {
            $studentNotes = $notes->where('inscription_id', $inscription->id);
            $average = $studentNotes->count() ? round($studentNotes->avg('valeur'), 2) : null;

            return [
                'student' => $inscription->eleve,
                'average' => $average,
                'subject' => $subject?->nom ?? 'Matière',
            ];
        });

        return $ranking->sortByDesc(fn ($entry) => $entry['average'] ?? -1)->values();
    }

    private function buildStudentScorecards($students, $evaluations, $notes, int $academicYearId, int $classId, ?int $subjectId = null)
    {
        $programmeRows = ProgrammeClasse::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->when($subjectId, fn ($query, $id) => $query->where('matiere_id', $id))
            ->get();

        $subjects = Matiere::query()
            ->whereIn('id', $programmeRows->pluck('matiere_id'))
            ->get()
            ->keyBy('id');

        return $students->map(function (Inscription $inscription) use ($programmeRows, $subjects, $evaluations, $notes) {
            $subjectScores = [];
            $totalPoints = 0;
            $totalSubjects = 0;

            foreach ($programmeRows as $programme) {
                $subjectEvaluations = $evaluations->where('matiere_id', $programme->matiere_id);
                $subjectNotes = $notes->whereIn('evaluation_id', $subjectEvaluations->pluck('id'))
                    ->where('inscription_id', $inscription->id)
                    ->whereNull('statut');

                $average = $subjectNotes->count() ? round($subjectNotes->avg('valeur'), 2) : null;

                if ($average !== null) {
                    $totalPoints += $average;
                    $totalSubjects += 1;
                }

                $subjectScores[] = [
                    'subject' => $subjects->get($programme->matiere_id)?->nom ?? 'Matière',
                    'average' => $average,
                ];
            }

            $generalAverage = $totalSubjects > 0 ? round($totalPoints / $totalSubjects, 2) : null;

            return [
                'student' => $inscription->eleve,
                'subjects' => $subjectScores,
                'average' => $generalAverage,
            ];
        });
    }

    private function buildSubjectAverages(int $academicYearId, int $classId, int $periodId, ?int $subjectId = null)
    {
        if (! $classId || ! $periodId) {
            return collect();
        }

        $programmeRows = ProgrammeClasse::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->when($subjectId, fn ($query, $id) => $query->where('matiere_id', $id))
            ->get();

        $subjects = Matiere::query()
            ->whereIn('id', $programmeRows->pluck('matiere_id'))
            ->get()
            ->keyBy('id');

        $evaluations = Evaluation::query()
            ->where('annee_scolaire_id', $academicYearId)
            ->where('classe_id', $classId)
            ->where('periode_id', $periodId)
            ->when($subjectId, fn ($query, $id) => $query->where('matiere_id', $id))
            ->where('statut', '!=', 'BROUILLON')
            ->get();

        $notes = Note::query()
            ->whereIn('evaluation_id', $evaluations->pluck('id'))
            ->whereNull('statut')
            ->get();

        return $programmeRows->map(function (ProgrammeClasse $programme) use ($subjects, $evaluations, $notes) {
            $subjectEvaluations = $evaluations->where('matiere_id', $programme->matiere_id);
            $subjectNotes = $notes->whereIn('evaluation_id', $subjectEvaluations->pluck('id'));
            $average = $subjectNotes->count() ? round($subjectNotes->avg('valeur'), 2) : null;

            return [
                'subject' => $subjects->get($programme->matiere_id)?->nom ?? 'Matière',
                'average' => $average,
            ];
        });
    }
}
