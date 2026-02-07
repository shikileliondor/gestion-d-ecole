<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\EleveContact;
use App\Models\EleveTuteur;
use App\Models\Inscription;
use App\Models\Niveau;
use App\Services\MatriculeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(): View
    {
        $students = Eleve::query()
            ->select('eleves.*')
            ->addSelect([
                'class_name' => Inscription::query()
                    ->select('classes.nom')
                    ->join('classes', 'inscriptions.classe_id', '=', 'classes.id')
                    ->whereColumn('inscriptions.eleve_id', 'eleves.id')
                    ->latest('inscriptions.date_inscription')
                    ->limit(1),
                'latest_status' => Inscription::query()
                    ->select('inscriptions.statut')
                    ->whereColumn('inscriptions.eleve_id', 'eleves.id')
                    ->latest('inscriptions.date_inscription')
                    ->limit(1),
            ])
            ->orderBy('eleves.nom')
            ->orderBy('eleves.prenoms')
            ->get();

        $students->each(function (Eleve $student) {
            $student->setAttribute('admission_number', $student->matricule);
            $student->setAttribute('last_name', $student->nom);
            $student->setAttribute('first_name', $student->prenoms);

            $status = $student->latest_status;
            if ($status) {
                $student->setAttribute('status', $this->mapInscriptionStatus($status));
            } else {
                $student->setAttribute('status', 'inactive');
            }
        });

        $classes = Classe::query()
            ->orderBy('nom')
            ->get()
            ->each(function (Classe $classe) {
                $classe->setAttribute('name', $classe->nom);
            });

        $academicYears = AnneeScolaire::query()
            ->orderByDesc('date_debut')
            ->get()
            ->each(function (AnneeScolaire $annee) {
                $annee->setAttribute('name', $annee->libelle);
            });

        $activeAcademicYear = $this->activeAcademicYear();
        if ($activeAcademicYear) {
            $activeAcademicYear->setAttribute('name', $activeAcademicYear->libelle);
        }

        $levels = Niveau::query()
            ->orderBy('ordre')
            ->get();

        return view('students.index', compact('students', 'classes', 'academicYears', 'activeAcademicYear', 'levels'));
    }

    public function create(): View
    {
        $classes = Classe::query()
            ->orderBy('nom')
            ->get()
            ->each(function (Classe $classe) {
                $classe->setAttribute('name', $classe->nom);
            });

        $academicYears = AnneeScolaire::query()
            ->orderByDesc('date_debut')
            ->get()
            ->each(function (AnneeScolaire $annee) {
                $annee->setAttribute('name', $annee->libelle);
            });

        $activeAcademicYear = $this->activeAcademicYear();
        if ($activeAcademicYear) {
            $activeAcademicYear->setAttribute('name', $activeAcademicYear->libelle);
        }

        $levels = Niveau::query()
            ->orderBy('ordre')
            ->get();

        return view('students.create', compact('classes', 'academicYears', 'activeAcademicYear', 'levels'));
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
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'enrollment_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,suspended,transferred,graduated,inactive'],
            'level_id' => ['required', 'exists:niveaux,id'],
            'class_id' => ['required', 'exists:classes,id'],
            'academic_year_id' => ['nullable', 'exists:annees_scolaires,id'],
            'class_status' => ['nullable', 'in:active,transferred,completed'],
            'parent_first_name' => ['nullable', 'string', 'max:255'],
            'parent_last_name' => ['nullable', 'string', 'max:255'],
            'parent_relationship' => ['nullable', 'string', 'max:50'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'parent_email' => ['nullable', 'email', 'max:255'],
            'parent_address' => ['nullable', 'string', 'max:255'],
            'parent_occupation' => ['nullable', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $parentFields = [
                $request->input('parent_first_name'),
                $request->input('parent_last_name'),
                $request->input('parent_relationship'),
                $request->input('parent_phone'),
                $request->input('parent_email'),
                $request->input('parent_address'),
                $request->input('parent_occupation'),
            ];

            $hasParentData = collect($parentFields)->filter(fn ($value) => filled($value))->isNotEmpty();

            if ($hasParentData) {
                if (! $request->filled('parent_first_name')) {
                    $validator->errors()->add('parent_first_name', 'Le prénom du tuteur est requis.');
                }

                if (! $request->filled('parent_last_name')) {
                    $validator->errors()->add('parent_last_name', 'Le nom du tuteur est requis.');
                }

                if (! $request->filled('parent_phone')) {
                    $validator->errors()->add('parent_phone', 'Le téléphone du tuteur est requis.');
                }
            }

            if ($request->filled('level_id') && $request->filled('class_id')) {
                $matchesLevel = Classe::query()
                    ->where('id', $request->input('class_id'))
                    ->where('niveau_id', $request->input('level_id'))
                    ->exists();

                if (! $matchesLevel) {
                    $validator->errors()->add('class_id', 'La classe sélectionnée ne correspond pas au niveau choisi.');
                }
            }
        });

        $data = $validator->validate();

        $academicYearId = $this->resolveAcademicYearId($data['academic_year_id'] ?? null);

        if (! $academicYearId) {
            return back()->withErrors([
                'academic_year_id' => "Aucune année scolaire active n'est disponible.",
            ]);
        }

        DB::transaction(function () use ($data, $academicYearId) {
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
                'annee_scolaire_id' => $academicYearId,
                'eleve_id' => $eleve->id,
                'classe_id' => $data['class_id'],
                'date_inscription' => $data['enrollment_date'] ?? now()->toDateString(),
                'statut' => $this->mapClassStatus($data['class_status'] ?? null),
            ]);

            $hasContactData = collect([
                $data['phone'] ?? null,
                $data['email'] ?? null,
                $data['address'] ?? null,
                $data['city'] ?? null,
            ])->filter(fn ($value) => filled($value))->isNotEmpty();

            if ($hasContactData) {
                EleveContact::create([
                    'eleve_id' => $eleve->id,
                    'telephone' => $data['phone'] ?? null,
                    'email' => $data['email'] ?? null,
                    'adresse' => $data['address'] ?? null,
                    'ville' => $data['city'] ?? null,
                ]);
            }

            $hasParentData = collect([
                $data['parent_first_name'] ?? null,
                $data['parent_last_name'] ?? null,
                $data['parent_relationship'] ?? null,
                $data['parent_phone'] ?? null,
                $data['parent_email'] ?? null,
                $data['parent_address'] ?? null,
                $data['parent_occupation'] ?? null,
            ])->filter(fn ($value) => filled($value))->isNotEmpty();

            if ($hasParentData && filled($data['parent_phone'] ?? null)) {
                EleveTuteur::create([
                    'eleve_id' => $eleve->id,
                    'lien' => $this->mapRelationship($data['parent_relationship'] ?? null),
                    'nom' => $data['parent_last_name'] ?? $data['parent_first_name'],
                    'prenoms' => $data['parent_first_name'] ?? null,
                    'telephone_1' => $data['parent_phone'],
                    'email' => $data['parent_email'] ?? null,
                    'profession' => $data['parent_occupation'] ?? null,
                    'adresse' => $data['parent_address'] ?? null,
                ]);
            }
        });

        return redirect()
            ->route('students.index')
            ->with('status', "L'élève a été ajouté avec succès.");
    }

    public function show(int $id): JsonResponse
    {
        $eleve = Eleve::query()->findOrFail($id);

        $inscription = Inscription::query()
            ->where('inscriptions.eleve_id', $eleve->id)
            ->leftJoin('classes', 'inscriptions.classe_id', '=', 'classes.id')
            ->select('classes.nom as name', 'inscriptions.statut', 'inscriptions.date_inscription')
            ->latest('inscriptions.date_inscription')
            ->first();

        $contact = EleveContact::query()
            ->where('eleve_id', $eleve->id)
            ->first();

        $tuteur = EleveTuteur::query()
            ->where('eleve_id', $eleve->id)
            ->latest('id')
            ->first();

        $studentPayload = [
            'id' => $eleve->id,
            'admission_number' => $eleve->matricule,
            'date_of_birth' => $eleve->date_naissance,
            'address' => $contact?->adresse,
            'city' => $contact?->ville,
            'phone' => $contact?->telephone,
            'email' => $contact?->email,
        ];

        $parentPayload = $tuteur ? [
            'first_name' => $tuteur->prenoms,
            'last_name' => $tuteur->nom,
            'phone' => $tuteur->telephone_1,
            'email' => $tuteur->email,
            'relationship' => $tuteur->lien,
        ] : [];

        return response()->json([
            'student' => $studentPayload,
            'class' => $inscription,
            'parent' => $parentPayload,
            'grades' => [],
            'payments' => [],
            'documents' => [],
        ]);
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

    private function mapRelationship(?string $relationship): string
    {
        $value = strtoupper(trim((string) $relationship));

        return match ($value) {
            'PERE', 'PAPA', 'PÈRE', 'PEREE' => 'PERE',
            'MERE', 'MAMAN', 'MÈRE' => 'MERE',
            'TUTEUR', 'TUTRICE' => 'TUTEUR',
            default => 'AUTRE',
        };
    }

    private function mapClassStatus(?string $status): string
    {
        return match ($status) {
            'transferred' => 'TRANSFERE',
            'completed' => 'ABANDON',
            default => 'INSCRIT',
        };
    }

    private function mapInscriptionStatus(string $status): string
    {
        return match ($status) {
            'INSCRIT', 'REDOUBLANT', 'TRANSFERE' => 'active',
            default => 'inactive',
        };
    }
}
