<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\EleveContact;
use App\Models\EleveTuteur;
use App\Models\EleveUrgence;
use App\Models\Inscription;
use App\Services\MatriculeService;
use Barryvdh\DomPDF\Facade\Pdf;
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

        return view('students.index', compact('students', 'classes', 'academicYears'));
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

        return view('students.create', compact('classes', 'academicYears', 'activeAcademicYear'));
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
            'matricule_national' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'enrollment_date' => ['nullable', 'date'],
            'status' => ['nullable', 'in:active,suspended,transferred,graduated,inactive'],
            'class_id' => ['required', 'exists:classes,id'],
            'academic_year_id' => ['nullable', 'exists:annees_scolaires,id'],
            'class_status' => ['nullable', 'in:active,transferred,completed'],
            'previous_school' => ['nullable', 'string', 'max:150'],
            'arrival_date' => ['nullable', 'date'],
            'previous_class' => ['nullable', 'string', 'max:100'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:30'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'father_phone' => ['nullable', 'string', 'max:30'],
            'father_address' => ['nullable', 'string', 'max:255'],
            'father_occupation' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'mother_phone' => ['nullable', 'string', 'max:30'],
            'mother_address' => ['nullable', 'string', 'max:255'],
            'mother_occupation' => ['nullable', 'string', 'max:255'],
            'guardian_name' => ['nullable', 'string', 'max:255'],
            'guardian_relationship' => ['nullable', 'string', 'max:50'],
            'guardian_phone' => ['nullable', 'string', 'max:30'],
            'guardian_address' => ['nullable', 'string', 'max:255'],
            'guardian_occupation' => ['nullable', 'string', 'max:255'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $this->validateContactBlock($validator, $request, 'father', 'Le père');
            $this->validateContactBlock($validator, $request, 'mother', 'La mère');
            $this->validateContactBlock($validator, $request, 'guardian', 'Le correspondant');
        });

        $data = $validator->validate();

        $academicYearId = $this->resolveAcademicYearId($data['academic_year_id'] ?? null);

        if (! $academicYearId) {
            return back()->withErrors([
                'academic_year_id' => "Aucune année scolaire active n'est disponible.",
            ]);
        }

        DB::transaction(function () use ($data, $academicYearId, $request) {
            $matricule = app(MatriculeService::class)->generateForStudent($data['enrollment_date'] ?? null);
            $prenoms = trim($data['first_name'] . ' ' . ($data['middle_name'] ?? ''));
            $photoPath = $this->storePhoto($request);

            $eleve = Eleve::create([
                'matricule' => $matricule,
                'matricule_national' => $data['matricule_national'] ?? null,
                'nom' => $data['last_name'],
                'prenoms' => $prenoms,
                'sexe' => $this->mapGender($data['gender'] ?? null),
                'date_naissance' => $data['date_of_birth'] ?? null,
                'lieu_naissance' => $data['place_of_birth'] ?? null,
                'nationalite' => $data['nationality'] ?? null,
                'etablissement_origine' => $data['previous_school'] ?? null,
                'date_arrivee' => $data['arrival_date'] ?? null,
                'classe_precedente' => $data['previous_class'] ?? null,
                'photo_path' => $photoPath,
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

            if (filled($data['emergency_contact_name'] ?? null) && filled($data['emergency_contact_phone'] ?? null)) {
                EleveUrgence::create([
                    'eleve_id' => $eleve->id,
                    'nom_complet' => $data['emergency_contact_name'],
                    'lien' => 'AUTRE',
                    'telephone' => $data['emergency_contact_phone'],
                ]);
            }

            $this->createTutor($eleve->id, 'PERE', [
                'name' => $data['father_name'] ?? null,
                'phone' => $data['father_phone'] ?? null,
                'address' => $data['father_address'] ?? null,
                'occupation' => $data['father_occupation'] ?? null,
            ]);

            $this->createTutor($eleve->id, 'MERE', [
                'name' => $data['mother_name'] ?? null,
                'phone' => $data['mother_phone'] ?? null,
                'address' => $data['mother_address'] ?? null,
                'occupation' => $data['mother_occupation'] ?? null,
            ]);

            $guardianLink = $this->mapRelationship($data['guardian_relationship'] ?? null);
            $this->createTutor($eleve->id, $guardianLink, [
                'name' => $data['guardian_name'] ?? null,
                'phone' => $data['guardian_phone'] ?? null,
                'address' => $data['guardian_address'] ?? null,
                'occupation' => $data['guardian_occupation'] ?? null,
            ]);
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

        $tuteurs = EleveTuteur::query()
            ->where('eleve_id', $eleve->id)
            ->get();

        $tuteur = $tuteurs->firstWhere('lien', 'PERE')
            ?? $tuteurs->firstWhere('lien', 'MERE')
            ?? $tuteurs->firstWhere('lien', 'TUTEUR')
            ?? $tuteurs->first();

        $studentPayload = [
            'id' => $eleve->id,
            'admission_number' => $eleve->matricule,
            'date_of_birth' => $eleve->date_naissance,
            'address' => $contact?->adresse,
            'city' => $contact?->ville,
            'phone' => $contact?->telephone,
            'email' => $contact?->email,
            'registration_pdf_url' => route('students.registration.pdf', $eleve->id),
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

    public function registrationPdf(int $id)
    {
        $student = Eleve::query()->findOrFail($id);
        $inscription = Inscription::query()
            ->where('inscriptions.eleve_id', $student->id)
            ->leftJoin('classes', 'inscriptions.classe_id', '=', 'classes.id')
            ->leftJoin('annees_scolaires', 'inscriptions.annee_scolaire_id', '=', 'annees_scolaires.id')
            ->select(
                'inscriptions.*',
                'classes.nom as classe_nom',
                'annees_scolaires.libelle as annee_scolaire'
            )
            ->latest('inscriptions.date_inscription')
            ->first();

        $contacts = EleveContact::query()
            ->where('eleve_id', $student->id)
            ->first();

        $tuteurs = EleveTuteur::query()
            ->where('eleve_id', $student->id)
            ->get()
            ->groupBy('lien');

        $father = $tuteurs->get('PERE')?->first();
        $mother = $tuteurs->get('MERE')?->first();
        $guardian = $tuteurs->get('TUTEUR')?->first() ?? $tuteurs->get('AUTRE')?->first();

        $inscriptionCount = Inscription::query()
            ->where('eleve_id', $student->id)
            ->count();

        $photoPath = $student->photo_path ? storage_path('app/public/' . $student->photo_path) : null;

        $pdf = Pdf::loadView('students.pdf.registration', [
            'student' => $student,
            'inscription' => $inscription,
            'contacts' => $contacts,
            'father' => $father,
            'mother' => $mother,
            'guardian' => $guardian,
            'photoPath' => $photoPath,
            'isReEnrollment' => $inscriptionCount > 1,
        ]);

        $filename = sprintf('fiche-inscription-%s-%s.pdf', $student->nom, $student->prenoms);

        return $pdf->download($filename);
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

    private function storePhoto(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        return $request->file('photo')->store('photos/eleves', 'public');
    }

    private function createTutor(int $studentId, string $relationship, array $data): void
    {
        if (! filled($data['name'] ?? null) || ! filled($data['phone'] ?? null)) {
            return;
        }

        EleveTuteur::create([
            'eleve_id' => $studentId,
            'lien' => $relationship,
            'nom' => $data['name'],
            'prenoms' => null,
            'telephone_1' => $data['phone'],
            'email' => null,
            'profession' => $data['occupation'] ?? null,
            'adresse' => $data['address'] ?? null,
        ]);
    }

    private function validateContactBlock($validator, Request $request, string $prefix, string $label): void
    {
        $fields = [
            $request->input("{$prefix}_name"),
            $request->input("{$prefix}_phone"),
            $request->input("{$prefix}_address"),
            $request->input("{$prefix}_occupation"),
            $request->input("{$prefix}_relationship"),
        ];

        $hasData = collect($fields)->filter(fn ($value) => filled($value))->isNotEmpty();

        if (! $hasData) {
            return;
        }

        if (! $request->filled("{$prefix}_name")) {
            $validator->errors()->add("{$prefix}_name", "{$label} : le nom complet est requis.");
        }

        if (! $request->filled("{$prefix}_phone")) {
            $validator->errors()->add("{$prefix}_phone", "{$label} : le téléphone est requis.");
        }
    }
}
