<?php

namespace App\Http\Controllers;

use App\Models\Enseignant;
use App\Models\EnseignantDocument;
use App\Models\School;
use App\Services\MatriculeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class EnseignantController extends Controller
{
    public function index(): View
    {
        $enseignants = Enseignant::query()
            ->orderBy('nom')
            ->orderBy('prenoms')
            ->get();

        return view('teachers.index', compact('enseignants'));
    }

    public function create(): View
    {
        return view('teachers.create', $this->formOptions());
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateEnseignant($request);

        $schoolId = School::query()->value('id');
        if (! $schoolId) {
            return back()->withErrors(['school_id' => "Aucune école n'est configurée."])
                ->withInput();
        }

        try {
            $matricule = app(MatriculeService::class)->generateForEnseignant($schoolId);
        } catch (RuntimeException $exception) {
            return back()->withErrors(['academic_year' => $exception->getMessage()])
                ->withInput();
        }

        $enseignant = Enseignant::create(array_merge($data, [
            'matricule' => $matricule,
        ]));

        return redirect()
            ->route('teachers.show', $enseignant)
            ->with('status', "L'enseignant a été créé avec succès.");
    }

    public function show(Enseignant $enseignant): View
    {
        $enseignant->load('documents');

        return view('teachers.show', [
            'enseignant' => $enseignant,
            'documentTypes' => EnseignantDocument::TYPES,
        ]);
    }

    public function edit(Enseignant $enseignant): View
    {
        return view('teachers.edit', array_merge(
            ['enseignant' => $enseignant],
            $this->formOptions()
        ));
    }

    public function update(Request $request, Enseignant $enseignant): RedirectResponse
    {
        $data = $this->validateEnseignant($request, $enseignant);

        $enseignant->update($data);

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
                'required',
                'string',
                'max:50',
                Rule::unique('enseignants', 'code_enseignant')->ignore($enseignant?->id),
            ],
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'sexe' => ['nullable', Rule::in(Enseignant::SEXES)],
            'date_naissance' => ['nullable', 'date'],
            'photo_url' => ['nullable', 'string', 'max:255'],
            'telephone_1' => ['required', 'string', 'max:30'],
            'telephone_2' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'specialite' => ['required', 'string', 'max:255'],
            'niveau_enseignement' => ['nullable', Rule::in(Enseignant::NIVEAUX)],
            'qualification' => ['nullable', 'string', 'max:255'],
            'type_enseignant' => ['required', Rule::in(Enseignant::TYPES)],
            'date_debut_service' => ['required', 'date'],
            'date_fin_service' => ['nullable', 'date', 'after_or_equal:date_debut_service'],
            'statut' => ['required', Rule::in(Enseignant::STATUTS)],
            'num_cni' => ['nullable', 'string', 'max:100'],
            'date_expiration_cni' => ['nullable', 'date'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_lien' => ['nullable', Rule::in(Enseignant::CONTACT_LIENS)],
            'contact_urgence_tel' => ['nullable', 'string', 'max:30'],
            'mode_paiement' => ['nullable', Rule::in(Enseignant::MODES_PAIEMENT)],
            'numero_paiement' => ['nullable', 'string', 'max:100'],
            'salaire_base' => ['nullable', 'numeric', 'min:0'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if (filled($request->input('mode_paiement')) && ! filled($request->input('numero_paiement'))) {
                $validator->errors()->add('numero_paiement', 'Le numéro de paiement est obligatoire si un mode est sélectionné.');
            }

            if ($request->input('statut') === 'PARTI' && ! filled($request->input('date_fin_service'))) {
                $validator->errors()->add('date_fin_service', 'La date de fin de service est requise si le statut est "PARTI".');
            }
        });

        return $validator->validate();
    }
}
