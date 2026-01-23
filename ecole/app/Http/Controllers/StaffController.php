<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Staff;
use App\Models\StaffAssignment;
use App\Models\StaffContract;
use App\Models\Subject;
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
            'phone' => ['nullable', 'string', 'max:30'],
            'contract_type' => ['required', 'in:CDI,CDD,Vacation'],
            'hire_date' => ['required', 'date'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['integer', 'exists:subjects,id'],
            'contract_file' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $validator->after(function ($validator) use ($request) {
            $isTeacher = str_contains(strtolower($request->input('position', '')), 'enseignant');

            if ($isTeacher && empty($request->input('subjects'))) {
                $validator->errors()->add('subjects', 'Veuillez sélectionner au moins une matière.');
            }
        });

        $data = $validator->validate();

        $schoolId = School::query()->value('id');
        if (! $schoolId) {
            return back()->withErrors(['school_id' => "Aucune école n'est configurée."])
                ->withInput();
        }

        $nameParts = preg_split('/\s+/', trim($data['full_name']), 3);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? $nameParts[0] ?? '';
        $middleName = $nameParts[2] ?? null;

        $contractFile = $request->file('contract_file');
        $filePath = $contractFile?->store('documents/staff-contracts', 'public');

        DB::transaction(function () use ($data, $schoolId, $firstName, $lastName, $middleName, $filePath, $contractFile) {
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
            }
        });

        return redirect()
            ->route('staff.index')
            ->with('status', 'Le membre du personnel a été ajouté avec succès.');
    }

    public function show(int $id): JsonResponse
    {
        $staff = Staff::query()->findOrFail($id);

        $latestContract = StaffContract::query()
            ->where('staff_contracts.staff_id', $staff->id)
            ->leftJoin('documents', 'staff_contracts.document_id', '=', 'documents.id')
            ->select(
                'staff_contracts.contract_type',
                'staff_contracts.start_date',
                'staff_contracts.end_date',
                'staff_contracts.status',
                'documents.name',
                'documents.file_path'
            )
            ->latest('staff_contracts.start_date')
            ->first();

        $assignments = StaffAssignment::query()
            ->where('staff_assignments.staff_id', $staff->id)
            ->leftJoin('subjects', 'staff_assignments.subject_id', '=', 'subjects.id')
            ->leftJoin('classes', 'staff_assignments.class_id', '=', 'classes.id')
            ->select(
                'subjects.name as subject',
                'classes.name as class',
                'staff_assignments.start_date',
                'staff_assignments.end_date',
                'staff_assignments.status'
            )
            ->orderByDesc('staff_assignments.start_date')
            ->get();

        return response()->json([
            'staff' => $staff,
            'contract' => $latestContract,
            'assignments' => $assignments,
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
