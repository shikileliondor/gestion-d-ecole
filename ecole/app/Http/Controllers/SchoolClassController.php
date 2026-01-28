<?php

namespace App\Http\Controllers;

use App\Models\AffectationEnseignant;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\Inscription;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\ProgrammeClasse;
use App\Models\ProgrammeMatiere;
use App\Models\Serie;
use App\Services\ClasseService;
use App\Services\MatiereService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SchoolClassController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $classesQuery = Classe::query()->orderBy('nom');

        $academicYears = AnneeScolaire::query()
            ->orderByDesc('date_debut')
            ->get()
            ->each(fn (AnneeScolaire $annee) => $annee->setAttribute('name', $annee->libelle));

        $selectedAcademicYearId = $request->input('academic_year_id');
        $selectedLevelId = $request->input('level_id');
        $selectedSerieId = $request->input('serie_id');

        if ($selectedAcademicYearId) {
            $classesQuery->where('annee_scolaire_id', $selectedAcademicYearId);
        }
        if ($selectedLevelId) {
            $classesQuery->where('niveau_id', $selectedLevelId);
        }
        if ($selectedSerieId) {
            $classesQuery->where('serie_id', $selectedSerieId);
        }

        $classes = $classesQuery->get();

        $subjects = Matiere::query()
            ->orderBy('nom')
            ->get()
            ->each(fn (Matiere $matiere) => $matiere->setAttribute('name', $matiere->nom));

        $students = Eleve::query()
            ->orderBy('nom')
            ->orderBy('prenoms')
            ->get()
            ->each(function (Eleve $eleve) {
                $eleve->setAttribute('last_name', $eleve->nom);
                $eleve->setAttribute('first_name', $eleve->prenoms);
            });

        $staff = Enseignant::query()
            ->orderBy('nom')
            ->orderBy('prenoms')
            ->get()
            ->each(function (Enseignant $enseignant) {
                $enseignant->setAttribute('last_name', $enseignant->nom);
                $enseignant->setAttribute('first_name', $enseignant->prenoms);
            });

        $levels = Niveau::query()
            ->orderBy('ordre')
            ->get()
            ->keyBy('id');

        $series = Serie::query()
            ->orderBy('code')
            ->get()
            ->keyBy('id');

        $academicYearsById = $academicYears->keyBy('id');

        $inscriptionCounts = Inscription::query()
            ->select('classe_id', DB::raw('count(*) as total'))
            ->groupBy('classe_id')
            ->pluck('total', 'classe_id');

        $assignmentCounts = AffectationEnseignant::query()
            ->select('classe_id', DB::raw('count(*) as total'))
            ->groupBy('classe_id')
            ->pluck('total', 'classe_id');

        $programmeCounts = ProgrammeClasse::query()
            ->select('classe_id', DB::raw('count(*) as total'))
            ->groupBy('classe_id')
            ->pluck('total', 'classe_id');

        $enseignantsById = $staff->keyBy('id');
        $matieresById = $subjects->keyBy('id');
        $assignmentsByClass = AffectationEnseignant::query()
            ->get()
            ->groupBy('classe_id');
        $programmesByClass = ProgrammeClasse::query()
            ->get()
            ->groupBy('classe_id');

        $coefficientsByLevel = ProgrammeMatiere::query()
            ->get()
            ->groupBy(fn (ProgrammeMatiere $programme) => $programme->annee_scolaire_id.'-'.$programme->niveau_id.'-'.$programme->serie_id);

        $classes->each(function (Classe $classe) use (
            $academicYearsById,
            $levels,
            $series,
            $inscriptionCounts,
            $assignmentCounts,
            $programmeCounts,
            $assignmentsByClass,
            $programmesByClass,
            $coefficientsByLevel,
            $enseignantsById,
            $matieresById
        ) {
            $classe->setAttribute('name', $classe->nom);
            $classe->setAttribute('level', optional($levels->get($classe->niveau_id))->code);
            $classe->setAttribute('series', optional($series->get($classe->serie_id))->code);
            $classe->setAttribute('section', null);
            $classe->setAttribute('room', null);
            $classe->setAttribute('manual_headcount', $classe->effectif_max);
            $classe->setAttribute('student_assignments_count', $inscriptionCounts[$classe->id] ?? 0);
            $classe->setAttribute('subject_assignments_count', $programmeCounts[$classe->id] ?? 0);
            $classe->setAttribute('teacher_assignments_count', $assignmentCounts[$classe->id] ?? 0);

            $academicYear = $academicYearsById->get($classe->annee_scolaire_id);
            if ($academicYear) {
                $classe->setRelation('academicYear', $academicYear);
            }

            $assignments = $this->mapProgrammeAssignments(
                $programmesByClass->get($classe->id, collect()),
                $assignmentsByClass->get($classe->id, collect()),
                $enseignantsById,
                $matieresById
            );
            $classe->setRelation('subjectAssignments', $assignments);

            $coeffKey = $classe->annee_scolaire_id.'-'.$classe->niveau_id.'-'.$classe->serie_id;
            $officialCoefficients = $coefficientsByLevel->get($coeffKey, collect())->keyBy('matiere_id');
            $programmeMatieres = $programmesByClass->get($classe->id, collect());

            $programmeComplete = $programmeMatieres->isNotEmpty()
                && $programmeMatieres->every(fn (ProgrammeClasse $programme) => $officialCoefficients->has($programme->matiere_id));
            $assignmentsComplete = $programmeMatieres->isNotEmpty()
                && $programmeMatieres->every(function (ProgrammeClasse $programme) use ($assignmentsByClass, $classe) {
                    return $assignmentsByClass->get($classe->id, collect())->contains('matiere_id', $programme->matiere_id);
                });

            $classe->setAttribute('programme_complete', $programmeComplete);
            $classe->setAttribute('assignments_complete', $assignmentsComplete);
        });

        $seriesOptions = $series->values()->map(fn (Serie $serie) => $serie->code)->all();

        if ($request->expectsJson()) {
            $gridHtml = view('classes.partials.class-grid', ['classes' => $classes])->render();

            return response()->json([
                'message' => 'Liste mise à jour.',
                'grid_html' => $gridHtml,
            ]);
        }

        return view('classes.index', compact(
            'classes',
            'academicYears',
            'subjects',
            'students',
            'staff',
            'seriesOptions',
            'levels',
            'series',
            'selectedAcademicYearId',
            'selectedLevelId',
            'selectedSerieId'
        ));
    }

    public function store(Request $request, ClasseService $service): JsonResponse|RedirectResponse
    {
        $data = $request->validateWithBag('classForm', [
            'academic_year_id' => ['required', 'exists:annees_scolaires,id'],
            'name' => ['required', 'string', 'max:255'],
            'level' => ['nullable', 'string', 'max:50'],
            'series' => ['nullable', 'string', 'max:50'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $niveauId = $this->resolveNiveauId($data['level'] ?? null);
        $serieId = $this->resolveSerieId($data['series'] ?? null);

        $classe = $service->create([
            'annee_scolaire_id' => $data['academic_year_id'],
            'niveau_id' => $niveauId,
            'serie_id' => $serieId,
            'nom' => $data['name'],
            'effectif_max' => $data['capacity'] ?? null,
            'actif' => ($data['status'] ?? 'active') === 'active',
        ]);

        if ($request->expectsJson()) {
            $classe = $this->hydrateClassCard($classe);
            $cardHtml = view('classes.partials.class-card', ['class' => $classe])->render();

            return response()->json([
                'message' => 'La classe a été créée avec succès.',
                'class_id' => $classe->id,
                'card_html' => $cardHtml,
            ]);
        }

        return back()->with('status', 'La classe a été créée avec succès.');
    }

    public function updateHeadcount(Request $request, Classe $class, ClasseService $service): JsonResponse|RedirectResponse
    {
        $data = $request->validateWithBag('headcountForm', [
            'manual_headcount' => ['nullable', 'integer', 'min:0'],
        ]);

        $service->update($class, [
            'effectif_max' => $data['manual_headcount'] ?? null,
        ]);

        if ($request->expectsJson()) {
            $classe = $this->hydrateClassCard($class->refresh());
            $cardHtml = view('classes.partials.class-card', ['class' => $classe])->render();

            return response()->json([
                'message' => "L'effectif de la classe a été mis à jour.",
                'class_id' => $classe->id,
                'card_html' => $cardHtml,
            ]);
        }

        return back()->with('status', "L'effectif de la classe a été mis à jour.");
    }

    public function storeSubject(Request $request, MatiereService $service): JsonResponse|RedirectResponse
    {
        $data = $request->validateWithBag('subjectForm', [
            'code' => ['required', 'string', 'max:50', 'unique:matieres,code'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $subject = $service->create([
            'code' => $data['code'],
            'nom' => $data['name'],
            'actif' => ($data['status'] ?? 'active') === 'active',
        ]);

        if ($request->expectsJson()) {
            $subject->setAttribute('name', $subject->nom);
            $subject->setAttribute('level', $subject->niveau_id ? optional(Niveau::query()->find($subject->niveau_id))->code : null);
            $subject->setAttribute('series', $subject->serie_id ? optional(Serie::query()->find($subject->serie_id))->code : null);

            $optionHtml = view('classes.partials.subject-option', ['subject' => $subject])->render();

            return response()->json([
                'message' => 'La matière a été créée avec succès.',
                'subject_option_html' => $optionHtml,
            ]);
        }

        return back()->with('status', 'La matière a été créée avec succès.');
    }

    public function assignSubject(Request $request, Classe $class): JsonResponse|RedirectResponse
    {
        $data = $request->validateWithBag('assignSubjectForm', [
            'subject_id' => ['required', 'exists:matieres,id'],
            'teacher_ids' => ['nullable', 'array'],
            'teacher_ids.*' => ['integer', 'exists:enseignants,id'],
        ]);

        $teacherIds = collect($data['teacher_ids'] ?? [])->unique()->values();

        if ($teacherIds->isEmpty()) {
            return back()->withErrors(['teacher_ids' => 'Veuillez sélectionner au moins un enseignant.'], 'assignSubjectForm');
        }

        foreach ($teacherIds as $teacherId) {
            AffectationEnseignant::query()->firstOrCreate([
                'annee_scolaire_id' => $class->annee_scolaire_id,
                'enseignant_id' => $teacherId,
                'classe_id' => $class->id,
                'matiere_id' => $data['subject_id'],
            ]);
        }

        ProgrammeClasse::query()->firstOrCreate([
            'annee_scolaire_id' => $class->annee_scolaire_id,
            'classe_id' => $class->id,
            'matiere_id' => $data['subject_id'],
        ], [
            'actif' => true,
        ]);

        if ($request->expectsJson()) {
            $classe = $this->hydrateClassCard($class->refresh());
            $cardHtml = view('classes.partials.class-card', ['class' => $classe])->render();
            $summaryHtml = view('classes.partials.subject-summary', ['class' => $classe])->render();

            return response()->json([
                'message' => 'La matière a été affectée à la classe.',
                'class_id' => $classe->id,
                'card_html' => $cardHtml,
                'subject_summary_html' => $summaryHtml,
            ]);
        }

        return back()->with('status', 'La matière a été affectée à la classe.');
    }

    public function assignStudent(Request $request, Classe $class): JsonResponse|RedirectResponse
    {
        $data = $request->validateWithBag('assignStudentForm', [
            'student_id' => ['required', 'exists:eleves,id'],
            'start_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,transferred,completed'],
        ]);

        $alreadyAssigned = Inscription::query()
            ->where('eleve_id', $data['student_id'])
            ->where('annee_scolaire_id', $class->annee_scolaire_id)
            ->exists();

        if ($alreadyAssigned) {
            return back()->withErrors([
                'student_id' => "Cet élève est déjà affecté à une classe pour cette année scolaire.",
            ], 'assignStudentForm');
        }

        Inscription::create([
            'annee_scolaire_id' => $class->annee_scolaire_id,
            'eleve_id' => $data['student_id'],
            'classe_id' => $class->id,
            'date_inscription' => $data['start_date'] ?? now()->toDateString(),
            'statut' => $this->mapAssignmentStatus($data['status'] ?? null),
        ]);

        if ($request->expectsJson()) {
            $classe = $this->hydrateClassCard($class->refresh());
            $cardHtml = view('classes.partials.class-card', ['class' => $classe])->render();

            return response()->json([
                'message' => "L'élève a été affecté à la classe.",
                'class_id' => $classe->id,
                'card_html' => $cardHtml,
            ]);
        }

        return back()->with('status', "L'élève a été affecté à la classe.");
    }

    public function storeSeries(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'series_list' => ['nullable', 'string', 'max:255'],
        ]);

        $series = collect(explode(',', $data['series_list'] ?? ''))
            ->map(fn ($value) => trim($value))
            ->filter()
            ->unique()
            ->values();

        foreach ($series as $code) {
            Serie::query()->firstOrCreate(
                ['code' => $code],
                ['libelle' => null, 'actif' => true]
            );
        }

        if ($request->expectsJson()) {
            $seriesOptions = Serie::query()
                ->orderBy('code')
                ->pluck('code');
            $seriesOptionsHtml = view('classes.partials.series-options', ['seriesOptions' => $seriesOptions])->render();

            return response()->json([
                'message' => 'Les séries ont été mises à jour.',
                'series_options_html' => $seriesOptionsHtml,
            ]);
        }

        return back()->with('status', 'Les séries ont été mises à jour.');
    }

    private function resolveNiveauId(?string $level): int
    {
        $level = $level ? trim($level) : null;

        if (! $level) {
            $niveau = Niveau::query()->first();
            if ($niveau) {
                return $niveau->id;
            }

            return Niveau::create([
                'code' => 'N/A',
                'ordre' => 1,
                'actif' => true,
            ])->id;
        }

        $niveau = Niveau::query()->where('code', $level)->first();
        if ($niveau) {
            return $niveau->id;
        }

        $nextOrder = (int) Niveau::query()->max('ordre');

        return Niveau::create([
            'code' => $level,
            'ordre' => $nextOrder + 1,
            'actif' => true,
        ])->id;
    }

    private function resolveSerieId(?string $series): ?int
    {
        $series = $series ? trim($series) : null;

        if (! $series) {
            return null;
        }

        $serie = Serie::query()->where('code', $series)->first();
        if ($serie) {
            return $serie->id;
        }

        return Serie::create([
            'code' => $series,
            'libelle' => null,
            'actif' => true,
        ])->id;
    }

    private function mapAssignmentStatus(?string $status): string
    {
        return match ($status) {
            'transferred' => 'TRANSFERE',
            'completed' => 'ABANDON',
            default => 'INSCRIT',
        };
    }

    /**
     * @param Collection<int, ProgrammeClasse> $programmes
     * @param Collection<int, AffectationEnseignant> $assignments
     */
    private function mapProgrammeAssignments(
        Collection $programmes,
        Collection $assignments,
        Collection $enseignantsById,
        Collection $matieresById
    ): Collection {
        $assignmentsBySubject = $assignments->groupBy('matiere_id');

        return $programmes->map(function (ProgrammeClasse $programme) use ($assignmentsBySubject, $enseignantsById, $matieresById) {
            $subject = $matieresById->get($programme->matiere_id);
            $assignment = $assignmentsBySubject->get($programme->matiere_id)?->first();
            $teacher = $assignment ? $enseignantsById->get($assignment->enseignant_id) : null;

            $subjectData = $subject ? (object) [
                'id' => $subject->id,
                'name' => $subject->nom,
                'level' => null,
                'series' => null,
            ] : null;

            $teacherData = $teacher ? (object) [
                'id' => $teacher->id,
                'last_name' => $teacher->nom,
                'first_name' => $teacher->prenoms,
            ] : null;

            return (object) [
                'subject' => $subjectData,
                'teacher' => $teacherData,
                'teachers' => collect($teacherData ? [$teacherData] : []),
                'coefficient' => 1,
                'color' => null,
            ];
        });
    }

    private function hydrateClassCard(Classe $classe): Classe
    {
        $classe->setAttribute('name', $classe->nom);
        $classe->setAttribute('level', optional(Niveau::query()->find($classe->niveau_id))->code);
        $classe->setAttribute('series', optional(Serie::query()->find($classe->serie_id))->code);
        $classe->setAttribute('section', null);
        $classe->setAttribute('room', null);
        $classe->setAttribute('manual_headcount', $classe->effectif_max);
        $classe->setAttribute('student_assignments_count', Inscription::query()->where('classe_id', $classe->id)->count());
        $classe->setAttribute('subject_assignments_count', ProgrammeClasse::query()->where('classe_id', $classe->id)->count());
        $classe->setAttribute('teacher_assignments_count', AffectationEnseignant::query()->where('classe_id', $classe->id)->count());

        $academicYear = AnneeScolaire::query()->find($classe->annee_scolaire_id);
        if ($academicYear) {
            $academicYear->setAttribute('name', $academicYear->libelle);
            $classe->setRelation('academicYear', $academicYear);
        }

        $enseignantsById = Enseignant::query()->orderBy('nom')->orderBy('prenoms')->get()->keyBy('id');
        $matieresById = Matiere::query()->orderBy('nom')->get()->keyBy('id');
        $assignments = AffectationEnseignant::query()->where('classe_id', $classe->id)->get();
        $programmes = ProgrammeClasse::query()->where('classe_id', $classe->id)->get();
        $classe->setRelation('subjectAssignments', $this->mapProgrammeAssignments($programmes, $assignments, $enseignantsById, $matieresById));

        return $classe;
    }
}
