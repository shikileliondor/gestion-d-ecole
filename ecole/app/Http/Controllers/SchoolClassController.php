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
use App\Models\Serie;
use App\Services\ClasseService;
use App\Services\MatiereService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class SchoolClassController extends Controller
{
    public function index(): View
    {
        $classes = Classe::query()
            ->orderBy('nom')
            ->get();

        $academicYears = AnneeScolaire::query()
            ->orderByDesc('date_debut')
            ->get()
            ->each(fn (AnneeScolaire $annee) => $annee->setAttribute('name', $annee->libelle));

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

        $enseignantsById = $staff->keyBy('id');
        $matieresById = $subjects->keyBy('id');
        $assignmentsByClass = AffectationEnseignant::query()
            ->get()
            ->groupBy('classe_id');

        $classes->each(function (Classe $classe) use (
            $academicYearsById,
            $levels,
            $series,
            $inscriptionCounts,
            $assignmentCounts,
            $assignmentsByClass
        ) {
            $classe->setAttribute('name', $classe->nom);
            $classe->setAttribute('level', optional($levels->get($classe->niveau_id))->code);
            $classe->setAttribute('series', optional($series->get($classe->serie_id))->code);
            $classe->setAttribute('section', null);
            $classe->setAttribute('room', null);
            $classe->setAttribute('manual_headcount', $classe->effectif_max);
            $classe->setAttribute('student_assignments_count', $inscriptionCounts[$classe->id] ?? 0);
            $classe->setAttribute('subject_assignments_count', $assignmentCounts[$classe->id] ?? 0);

            $academicYear = $academicYearsById->get($classe->annee_scolaire_id);
            if ($academicYear) {
                $classe->setRelation('academicYear', $academicYear);
            }

            $assignments = $this->mapAssignments(
                $assignmentsByClass->get($classe->id, collect()),
                $enseignantsById,
                $matieresById
            );
            $classe->setRelation('subjectAssignments', $assignments);
        });

        $seriesOptions = $series->values()->map(fn (Serie $serie) => $serie->code)->all();

        return view('classes.index', compact('classes', 'academicYears', 'subjects', 'students', 'staff', 'seriesOptions'));
    }

    public function store(Request $request, ClasseService $service): RedirectResponse
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

        $service->create([
            'annee_scolaire_id' => $data['academic_year_id'],
            'niveau_id' => $niveauId,
            'serie_id' => $serieId,
            'nom' => $data['name'],
            'effectif_max' => $data['capacity'] ?? null,
            'actif' => ($data['status'] ?? 'active') === 'active',
        ]);

        return back()->with('status', 'La classe a été créée avec succès.');
    }

    public function updateHeadcount(Request $request, Classe $class, ClasseService $service): RedirectResponse
    {
        $data = $request->validateWithBag('headcountForm', [
            'manual_headcount' => ['nullable', 'integer', 'min:0'],
        ]);

        $service->update($class, [
            'effectif_max' => $data['manual_headcount'] ?? null,
        ]);

        return back()->with('status', "L'effectif de la classe a été mis à jour.");
    }

    public function storeSubject(Request $request, MatiereService $service): RedirectResponse
    {
        $data = $request->validateWithBag('subjectForm', [
            'code' => ['required', 'string', 'max:50', 'unique:matieres,code'],
            'name' => ['required', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive'],
        ]);

        $service->create([
            'code' => $data['code'],
            'nom' => $data['name'],
            'actif' => ($data['status'] ?? 'active') === 'active',
        ]);

        return back()->with('status', 'La matière a été créée avec succès.');
    }

    public function assignSubject(Request $request, Classe $class): RedirectResponse
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

        return back()->with('status', 'La matière a été affectée à la classe.');
    }

    public function assignStudent(Request $request, Classe $class): RedirectResponse
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

        return back()->with('status', "L'élève a été affecté à la classe.");
    }

    public function storeSeries(Request $request): RedirectResponse
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
     * @param Collection<int, AffectationEnseignant> $assignments
     */
    private function mapAssignments(Collection $assignments, Collection $enseignantsById, Collection $matieresById): Collection
    {
        return $assignments->map(function (AffectationEnseignant $assignment) use ($enseignantsById, $matieresById) {
            $subject = $matieresById->get($assignment->matiere_id);
            $teacher = $enseignantsById->get($assignment->enseignant_id);

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
}
