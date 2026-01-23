<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Staff;
use App\Models\StaffDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        return $this->renderStaffList();
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'code_personnel' => ['required', 'string', 'max:50', 'unique:staff,code_personnel'],
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'sexe' => ['nullable', 'in:M,F,AUTRE'],
            'date_naissance' => ['nullable', 'date'],
            'photo_url' => ['nullable', 'string', 'max:255'],
            'telephone_1' => ['required', 'string', 'max:30'],
            'telephone_2' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'adresse' => ['nullable', 'string', 'max:255'],
            'commune' => ['nullable', 'string', 'max:255'],
            'categorie_personnel' => [
                'required',
                'in:ADMINISTRATION,SURVEILLANCE,INTENDANCE,COMPTABILITE,TECHNIQUE,SERVICE',
            ],
            'poste' => ['required', 'string', 'max:255'],
            'type_contrat' => ['required', 'in:CDI,CDD,VACATAIRE,STAGE'],
            'date_debut_service' => ['required', 'date'],
            'date_fin_service' => ['nullable', 'date'],
            'statut' => ['required', 'in:ACTIF,SUSPENDU,PARTI'],
            'num_cni' => ['nullable', 'string', 'max:100'],
            'date_expiration_cni' => ['nullable', 'date'],
            'contact_urgence_nom' => ['nullable', 'string', 'max:255'],
            'contact_urgence_lien' => ['nullable', 'in:PERE,MERE,CONJOINT,FRERE_SOEUR,TUTEUR,AUTRE'],
            'contact_urgence_tel' => ['nullable', 'string', 'max:30'],
            'mode_paiement' => ['nullable', 'in:MOBILE_MONEY,VIREMENT,CASH'],
            'numero_paiement' => ['nullable', 'string', 'max:100', 'required_with:mode_paiement'],
            'salaire_base' => ['nullable', 'numeric', 'min:0'],
            'documents' => ['nullable', 'array'],
            'documents.*.type_document' => ['required_with:documents.*.fichier', 'in:CNI,CONTRAT,DIPLOME,CV,ATTESTATION,AUTRE'],
            'documents.*.libelle' => ['required_with:documents.*.fichier', 'string', 'max:255'],
            'documents.*.description' => ['nullable', 'string', 'max:1000'],
            'documents.*.fichier' => ['required_with:documents.*.libelle', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->input('statut') === 'PARTI' && ! $request->filled('date_fin_service')) {
                $validator->errors()->add('date_fin_service', 'La date de fin de service est requise pour un personnel parti.');
            }
        });

        $data = $validator->validate();

        $schoolId = School::query()->value('id');
        if (! $schoolId) {
            return back()->withErrors(['school_id' => "Aucune école n'est configurée."])
                ->withInput();
        }

        DB::transaction(function () use ($data, $schoolId, $request) {
            $staff = Staff::create([
                'school_id' => $schoolId,
                'code_personnel' => $data['code_personnel'],
                'nom' => $data['nom'],
                'prenoms' => $data['prenoms'],
                'sexe' => $data['sexe'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'photo_url' => $data['photo_url'] ?? null,
                'telephone_1' => $data['telephone_1'],
                'telephone_2' => $data['telephone_2'] ?? null,
                'email' => $data['email'] ?? null,
                'adresse' => $data['adresse'] ?? null,
                'commune' => $data['commune'] ?? null,
                'categorie_personnel' => $data['categorie_personnel'],
                'poste' => $data['poste'],
                'type_contrat' => $data['type_contrat'],
                'date_debut_service' => $data['date_debut_service'],
                'date_fin_service' => $data['date_fin_service'] ?? null,
                'statut' => $data['statut'],
                'num_cni' => $data['num_cni'] ?? null,
                'date_expiration_cni' => $data['date_expiration_cni'] ?? null,
                'contact_urgence_nom' => $data['contact_urgence_nom'] ?? null,
                'contact_urgence_lien' => $data['contact_urgence_lien'] ?? null,
                'contact_urgence_tel' => $data['contact_urgence_tel'] ?? null,
                'mode_paiement' => $data['mode_paiement'] ?? null,
                'numero_paiement' => $data['numero_paiement'] ?? null,
                'salaire_base' => $data['salaire_base'] ?? null,
            ]);

            $documents = $data['documents'] ?? [];
            foreach ($documents as $index => $documentData) {
                $uploadedFile = $request->file("documents.$index.fichier");

                if (! $uploadedFile) {
                    continue;
                }

                $documentPath = $uploadedFile->store('documents/staff', 'public');

                StaffDocument::create([
                    'staff_id' => $staff->id,
                    'type_document' => $documentData['type_document'],
                    'libelle' => $documentData['libelle'],
                    'description' => $documentData['description'] ?? null,
                    'fichier_url' => $documentPath,
                    'mime_type' => $uploadedFile->getClientMimeType(),
                    'taille' => $uploadedFile->getSize(),
                ]);
            }
        });

        return redirect()
            ->route('staff.index')
            ->with('status', 'Le membre du personnel a été ajouté avec succès.');
    }

    public function show(int $id): JsonResponse
    {
        $staff = Staff::query()->findOrFail($id);

        $documents = StaffDocument::query()
            ->where('staff_documents.staff_id', $staff->id)
            ->select(
                'staff_documents.type_document',
                'staff_documents.libelle',
                'staff_documents.description',
                'staff_documents.fichier_url',
                'staff_documents.mime_type',
                'staff_documents.taille',
                'staff_documents.created_at'
            )
            ->orderByDesc('staff_documents.created_at')
            ->get()
            ->map(function ($document) {
                $document->url = Storage::disk('public')->url($document->fichier_url);
                return $document;
            });

        return response()->json([
            'staff' => $staff,
            'documents' => $documents,
        ]);
    }

    private function renderStaffList(): View
    {
        $staffMembers = Staff::query()
            ->orderBy('staff.nom')
            ->orderBy('staff.prenoms')
            ->get();

        return view('staff.cards', [
            'staffMembers' => $staffMembers,
            'title' => 'Gestion du personnel',
            'subtitle' => 'Suivi du personnel administratif et technique',
            'ctaLabel' => 'Ajouter un membre',
            'identifierLabel' => 'Code personnel',
            'profileTitle' => 'Fiche personnel',
            'formEyebrow' => 'Nouveau personnel',
            'formTitle' => 'Ajouter un membre du personnel',
        ]);
    }
}
