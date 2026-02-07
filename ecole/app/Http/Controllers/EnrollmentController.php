<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Niveau;
use App\Models\Note;
use App\Services\MatriculeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function hub(): RedirectResponse
    {
        return redirect()->route('students.index', ['open' => 'create']);
    }

    public function create(): RedirectResponse
    {
        return redirect()->route('students.index', ['open' => 'create']);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'gender' => ['nullable', 'in:male,female,other'],
            'date_of_birth' => ['nullable', 'date'],
            'place_of_birth' => ['nullable', 'string', 'max:255'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'class_id' => ['required', 'exists:classes,id'],
            'enrollment_date' => ['nullable', 'date'],
        ]);

        $data = $validator->validate();

        $activeAcademicYear = $this->activeAcademicYear();
        if (! $activeAcademicYear) {
            return back()->withErrors([
                'academic_year_id' => "Aucune année scolaire active n'est disponible.",
            ]);
        }

        $classMatchesYear = Classe::query()
            ->where('id', $data['class_id'])
            ->where('annee_scolaire_id', $activeAcademicYear->id)
            ->exists();

        if (! $classMatchesYear) {
            return back()->withErrors([
                'class_id' => "La classe sélectionnée n'appartient pas à l'année scolaire active.",
            ]);
        }

        DB::transaction(function () use ($data, $activeAcademicYear) {
            $matricule = app(MatriculeService::class)->generateForStudent($data['enrollment_date'] ?? null);
            $prenoms = trim($data['first_name'] . ' ' . ($data['middle_name'] ?? ''));

            $eleve = Eleve::create([
                'matricule' => $matricule,
                'nom' => $data['last_name'],
                'prenoms' => $prenoms,
                'sexe' => $this->mapGender($data['gender'] ?? null),
                'date_naissance' => $data['date_of_birth'] ?? null,
                'lieu_naissance' => $data['place_of_birth'] ?? null,
                'nationalite' => $data['nationality'] ?? null,
            ]);

            Inscription::create([
                'annee_scolaire_id' => $activeAcademicYear->id,
                'eleve_id' => $eleve->id,
                'classe_id' => $data['class_id'],
                'date_inscription' => $data['enrollment_date'] ?? now()->toDateString(),
                'statut' => 'INSCRIT',
            ]);
        });

        return redirect()
            ->route('students.index')
            ->with('status', "L'inscription a été enregistrée avec succès.");
    }

    public function reEnrollments(Request $request): View
    {
        $activeAcademicYear = $this->activeAcademicYear();

        $students = Eleve::query()
            ->orderBy('nom')
            ->orderBy('prenoms')
            ->get();

        $studentSummary = null;
        $selectedStudent = null;

        if ($request->filled('student_id')) {
            $selectedStudent = Eleve::query()->find($request->integer('student_id'));

            if ($selectedStudent) {
                $studentSummary = $this->buildReEnrollmentSummary($selectedStudent, $activeAcademicYear);
            }
        }

        return view('students.re-enrollments', compact(
            'activeAcademicYear',
            'students',
            'selectedStudent',
            'studentSummary'
        ));
    }

    public function storeReEnrollment(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'student_id' => ['required', 'exists:eleves,id'],
            'class_id' => ['required', 'exists:classes,id'],
        ]);

        $data = $validator->validate();

        $activeAcademicYear = $this->activeAcademicYear();
        if (! $activeAcademicYear) {
            return back()->withErrors([
                'academic_year_id' => "Aucune année scolaire active n'est disponible.",
            ]);
        }

        $alreadyEnrolled = Inscription::query()
            ->where('eleve_id', $data['student_id'])
            ->where('annee_scolaire_id', $activeAcademicYear->id)
            ->exists();

        if ($alreadyEnrolled) {
            return back()->withErrors([
                'student_id' => "L'élève est déjà réinscrit pour l'année scolaire active.",
            ]);
        }

        $classMatchesYear = Classe::query()
            ->where('id', $data['class_id'])
            ->where('annee_scolaire_id', $activeAcademicYear->id)
            ->exists();

        if (! $classMatchesYear) {
            return back()->withErrors([
                'class_id' => "La classe proposée n'appartient pas à l'année scolaire active.",
            ]);
        }

        $student = Eleve::query()->findOrFail($data['student_id']);
        $summary = $this->buildReEnrollmentSummary($student, $activeAcademicYear);

        Inscription::create([
            'annee_scolaire_id' => $activeAcademicYear->id,
            'eleve_id' => $student->id,
            'classe_id' => $data['class_id'],
            'date_inscription' => now()->toDateString(),
            'statut' => $summary['status_code'],
        ]);

        return redirect()
            ->route('students.re-enrollments')
            ->with('status', "La réinscription a été validée avec succès.");
    }

    private function buildReEnrollmentSummary(Eleve $student, ?AnneeScolaire $activeAcademicYear): array
    {
        $latestInscription = Inscription::query()
            ->where('eleve_id', $student->id)
            ->orderByDesc('date_inscription')
            ->first();

        $currentClass = $latestInscription
            ? Classe::query()->find($latestInscription->classe_id)
            : null;

        $average = null;
        if ($latestInscription) {
            $average = Note::query()
                ->where('inscription_id', $latestInscription->id)
                ->avg('valeur');
        }

        $status = $average !== null && $average >= 10 ? 'PROMU' : 'REDOUBLANT';
        $statusCode = $status === 'PROMU' ? 'INSCRIT' : 'REDOUBLANT';

        $recommendedClass = $this->resolveRecommendedClass($latestInscription, $activeAcademicYear, $status);

        return [
            'latest_inscription' => $latestInscription,
            'current_class' => $currentClass,
            'average' => $average,
            'status' => $status,
            'status_code' => $statusCode,
            'recommended_class' => $recommendedClass,
        ];
    }

    private function resolveRecommendedClass(
        ?Inscription $latestInscription,
        ?AnneeScolaire $activeAcademicYear,
        string $status
    ): ?Classe {
        if (! $latestInscription || ! $activeAcademicYear) {
            return null;
        }

        $currentClass = Classe::query()->find($latestInscription->classe_id);
        if (! $currentClass) {
            return null;
        }

        $currentNiveau = $currentClass->niveau_id ? Niveau::query()->find($currentClass->niveau_id) : null;
        $currentOrder = $currentNiveau?->ordre ?? 0;
        $targetNiveauId = $currentClass->niveau_id;

        if ($status === 'PROMU') {
            $targetNiveauId = Niveau::query()
                ->where('ordre', '>', $currentOrder)
                ->orderBy('ordre')
                ->value('id') ?? $currentClass->niveau_id;
        }

        $query = Classe::query()
            ->where('annee_scolaire_id', $activeAcademicYear->id)
            ->where('niveau_id', $targetNiveauId);

        if ($currentClass->serie_id) {
            $query->where('serie_id', $currentClass->serie_id);
        }

        return $query->orderBy('nom')->first()
            ?? Classe::query()
                ->where('annee_scolaire_id', $activeAcademicYear->id)
                ->where('niveau_id', $targetNiveauId)
                ->orderBy('nom')
                ->first()
            ?? $currentClass;
    }

    private function mapGender(?string $gender): ?string
    {
        return match ($gender) {
            'male' => 'M',
            'female' => 'F',
            'other' => 'AUTRE',
            default => null,
        };
    }
}
