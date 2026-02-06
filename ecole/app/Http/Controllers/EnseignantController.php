<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\EnseignantDocument;
use App\Models\Matiere;
use App\Services\MatriculeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnseignantController extends Controller
{
    public function index(): View
    {
        $enseignants = Enseignant::query()
            ->orderBy('nom')
            ->orderBy('prenoms')
            ->get()
            ->each(function (Enseignant $enseignant) {
                $enseignant->setAttribute('code_enseignant', $enseignant->matricule);
                $enseignant->setAttribute('photo_url', $enseignant->photo_path ? Storage::url($enseignant->photo_path) : null);
            });

        $matieres = Matiere::query()->orderBy('nom')->get();

        return view('teachers.index', compact('enseignants', 'matieres'));
    }

    public function create(): View
    {
        return view('teachers.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateEnseignant($request);

        $data['code_enseignant'] = app(MatriculeService::class)->generateForEnseignant();
        $photoPath = $this->storePhoto($request);
        if ($photoPath) {
            $data['photo_path'] = $photoPath;
        }

        $enseignant = Enseignant::create($this->mapEnseignantData($data));
        $enseignant->setAttribute('code_enseignant', $enseignant->matricule);
        $this->storeDocuments($request, $enseignant);

        return redirect()
            ->route('teachers.index')
            ->with('status', "L'enseignant a été créé avec succès.");
    }

    public function show(Request $request, Enseignant $enseignant): View|JsonResponse
    {
        $enseignant->load('documents');
        $enseignant->setAttribute('code_enseignant', $enseignant->matricule);
        $enseignant->setAttribute('photo_url', $enseignant->photo_path ? Storage::url($enseignant->photo_path) : null);

        if ($request->wantsJson()) {
            return response()->json([
                'teacher' => [
                    'id' => $enseignant->id,
                    'staff_number' => $enseignant->matricule,
                    'first_name' => $enseignant->prenoms,
                    'last_name' => $enseignant->nom,
                    'email' => $enseignant->email,
                    'telephone_1' => $enseignant->telephone_1,
                    'telephone_2' => $enseignant->telephone_2,
                    'specialite' => $enseignant->specialite,
                    'type_enseignant' => $enseignant->type_enseignant,
                    'statut' => $enseignant->statut,
                    'photo_url' => $enseignant->photo_url,
                ],
                'documents' => $enseignant->documents->map(fn (EnseignantDocument $document) => [
                    'id' => $document->id,
                    'libelle' => $document->libelle,
                    'url' => $document->fichier_url ? Storage::url($document->fichier_url) : null,
                ]),
            ]);
        }

        return view('teachers.show', [
            'enseignant' => $enseignant,
            'documentTypes' => EnseignantDocument::TYPES,
        ]);
    }

    public function edit(Enseignant $enseignant): View
    {
        $enseignant->setAttribute('code_enseignant', $enseignant->matricule);
        $enseignant->setAttribute('photo_url', $enseignant->photo_path ? Storage::url($enseignant->photo_path) : null);

        return view('teachers.edit', array_merge(
            ['enseignant' => $enseignant],
            $this->formOptions()
        ));
    }

    public function update(Request $request, Enseignant $enseignant): RedirectResponse
    {
        $data = $this->validateEnseignant($request, $enseignant);

        $photoPath = $this->storePhoto($request, $enseignant->photo_path);
        if ($photoPath) {
            $data['photo_path'] = $photoPath;
        }

        $enseignant->update($this->mapEnseignantData($data, $enseignant));
        $this->storeDocuments($request, $enseignant);

        return redirect()
            ->route('teachers.show', $enseignant)
            ->with('status', "La fiche enseignant a été mise à jour.");
    }

    public function destroy(Enseignant $enseignant): RedirectResponse
    {
        $enseignant->delete();

        return redirect()
            ->route('teachers.index')
            ->with('status', "L'enseignant a été supprimé.");
    }

    public function archive(Enseignant $enseignant): RedirectResponse
    {
        $enseignant->update(['statut' => 'PARTI']);

        return redirect()
            ->route('teachers.index')
            ->with('status', "L'enseignant a été archivé.");
    }

    public function storeDocument(Request $request, Enseignant $enseignant): RedirectResponse
    {
        $data = Validator::make($request->all(), [
            'type_document' => ['required', Rule::in(EnseignantDocument::TYPES)],
            'libelle' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'fichier' => ['required', 'file', 'mimes:pdf,jpeg,jpg,png,doc,docx', 'max:10240'],
        ])->validate();

        $file = $request->file('fichier');
        $path = $file->store('documents/enseignants', 'public');

        $enseignant->documents()->create([
            'type_document' => $data['type_document'],
            'libelle' => $data['libelle'],
            'description' => $data['description'] ?? null,
            'fichier_url' => $path,
            'mime_type' => $file?->getClientMimeType(),
            'taille' => $file?->getSize(),
        ]);

        return redirect()
            ->route('teachers.show', $enseignant)
            ->with('status', 'Le document a été ajouté.');
    }

    public function destroyDocument(Enseignant $enseignant, EnseignantDocument $document): RedirectResponse
    {
        if ($document->enseignant_id !== $enseignant->id) {
            return redirect()
                ->route('teachers.show', $enseignant)
                ->withErrors(['document' => 'Document introuvable.']);
        }

        if ($document->fichier_url) {
            Storage::disk('public')->delete($document->fichier_url);
        }

        $document->delete();

        return redirect()
            ->route('teachers.show', $enseignant)
            ->with('status', 'Le document a été supprimé.');
    }

    private function formOptions(): array
    {
        return [
            'sexes' => Enseignant::SEXES,
            'types' => Enseignant::TYPES,
            'statuts' => Enseignant::STATUTS,
            'niveaux' => Enseignant::NIVEAUX,
            'modesPaiement' => Enseignant::MODES_PAIEMENT,
            'contactLiens' => Enseignant::CONTACT_LIENS,
        ];
    }

    private function validateEnseignant(Request $request, ?Enseignant $enseignant = null): array
    {
        $validator = Validator::make($request->all(), [
            'code_enseignant' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('enseignants', 'matricule')->ignore($enseignant?->id),
            ],
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'sexe' => ['nullable', Rule::in(Enseignant::SEXES)],
            'telephone_1' => ['required', 'string', 'max:30'],
            'telephone_2' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'specialite' => ['required', 'string', 'max:255'],
            'type_enseignant' => ['required', Rule::in(Enseignant::TYPES)],
            'date_debut_service' => ['required', 'date'],
            'date_fin_service' => ['nullable', 'date', 'after_or_equal:date_debut_service'],
            'statut' => ['required', Rule::in(Enseignant::STATUTS)],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,jpeg,jpg,png,doc,docx', 'max:10240'],
            'documents_labels' => ['nullable', 'array'],
            'documents_labels.*' => ['nullable', 'string', 'max:255'],
        ]);

        return $validator->validate();
    }

    private function mapEnseignantData(array $data, ?Enseignant $enseignant = null): array
    {
        return [
            'matricule' => $data['code_enseignant'] ?? $enseignant?->matricule,
            'nom' => $data['nom'],
            'prenoms' => $data['prenoms'],
            'sexe' => $data['sexe'] ?? null,
            'telephone_1' => $data['telephone_1'],
            'telephone_2' => $data['telephone_2'] ?? null,
            'email' => $data['email'] ?? null,
            'specialite' => $data['specialite'],
            'photo_path' => $data['photo_path'] ?? $enseignant?->photo_path,
            'type_enseignant' => $data['type_enseignant'],
            'date_debut_service' => $data['date_debut_service'],
            'date_fin_service' => $data['date_fin_service'] ?? null,
            'statut' => $data['statut'],
        ];
    }

    private function storePhoto(Request $request, ?string $currentPath = null): ?string
    {
        if (!$request->hasFile('photo')) {
            return $currentPath;
        }

        if ($currentPath) {
            Storage::disk('public')->delete($currentPath);
        }

        return $request->file('photo')->store('photos/enseignants', 'public');
    }

    private function storeDocuments(Request $request, Enseignant $enseignant): void
    {
        $documents = $request->file('documents', []);
        $labels = $request->input('documents_labels', []);
        if (!$documents) {
            return;
        }

        foreach ($documents as $index => $document) {
            $path = $document->store('documents/enseignants', 'public');
            $label = $labels[$index] ?? null;
            $libelle = $label ? trim($label) : pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME);

            $enseignant->documents()->create([
                'type_document' => 'AUTRE',
                'libelle' => $libelle ?: pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME),
                'fichier_url' => $path,
                'mime_type' => $document->getClientMimeType(),
                'taille' => $document->getSize(),
            ]);
        }
    }
}
