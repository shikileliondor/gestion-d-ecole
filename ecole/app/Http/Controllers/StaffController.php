<?php

namespace App\Http\Controllers;

use App\Models\Document;
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
        return $this->renderStaffList(false);
    }

    public function teachers(): View
    {
        return $this->renderStaffList(true);
    }

    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'position' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'contract_type' => ['required', 'in:CDI,CDD,Vacation'],
            'hire_date' => ['required', 'date'],
            'subjects' => ['nullable', 'array'],
            'subjects.*' => ['integer', 'exists:subjects,id'],
            'contract_file' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->input('position') === 'Enseignant' && empty($request->input('subjects'))) {
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
                'staff_number' => $this->generateStaffNumber(),
                'first_name' => $firstName,
                'last_name' => $lastName,
                'middle_name' => $middleName,
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null,
                'position' => $data['position'],
                'hire_date' => $data['hire_date'],
                'status' => 'active',
            ]);

            $document = Document::create([
                'school_id' => $schoolId,
                'category' => 'contrat_personnel',
                'name' => 'Contrat ' . $staff->staff_number,
                'description' => 'Contrat de travail du personnel',
                'file_path' => $filePath ?? 'documents/staff-contracts/contrat.pdf',
                'mime_type' => $contractFile?->getClientMimeType(),
                'size' => $contractFile?->getSize(),
                'is_public' => false,
                'status' => 'active',
            ]);

            StaffContract::create([
                'staff_id' => $staff->id,
                'document_id' => $document->id,
                'contract_type' => strtolower($data['contract_type']),
                'start_date' => $data['hire_date'],
                'status' => 'active',
            ]);

            if (! empty($data['subjects'])) {
                foreach ($data['subjects'] as $subjectId) {
                    StaffAssignment::create([
                        'staff_id' => $staff->id,
                        'subject_id' => $subjectId,
                        'class_id' => null,
                        'start_date' => $data['hire_date'],
                        'assigned_at' => now(),
                        'status' => 'active',
                    ]);
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

    public function downloadContract(int $id)
    {
        $contract = StaffContract::query()
            ->where('staff_contracts.id', $id)
            ->leftJoin('documents', 'staff_contracts.document_id', '=', 'documents.id')
            ->select('documents.file_path', 'documents.name')
            ->firstOrFail();

        $filePath = $contract->file_path;
        if (! $filePath || ! Storage::disk('public')->exists($filePath)) {
            return back()->withErrors(['contract' => 'Le contrat est indisponible.']);
        }

        return Storage::disk('public')->download($filePath, ($contract->name ?? 'contrat') . '.pdf');
    }

    private function generateStaffNumber(): string
    {
        $lastStaffNumber = Staff::query()
            ->where('staff_number', 'like', 'EMP%')
            ->orderByDesc('staff_number')
            ->value('staff_number');

        if (! $lastStaffNumber) {
            return 'EMP001';
        }

        $number = (int) preg_replace('/\D/', '', $lastStaffNumber);
        $nextNumber = str_pad((string) ($number + 1), 3, '0', STR_PAD_LEFT);

        return 'EMP' . $nextNumber;
    }

    private function renderStaffList(bool $onlyTeachers): View
    {
        $staffMembers = Staff::query()
            ->select('staff.*')
            ->addSelect([
                'contract_type' => StaffContract::query()
                    ->select('contract_type')
                    ->whereColumn('staff_contracts.staff_id', 'staff.id')
                    ->latest('staff_contracts.start_date')
                    ->limit(1),
                'contract_id' => StaffContract::query()
                    ->select('id')
                    ->whereColumn('staff_contracts.staff_id', 'staff.id')
                    ->latest('staff_contracts.start_date')
                    ->limit(1),
            ])
            ->when($onlyTeachers, function ($query) {
                $query->where('position', 'Enseignant');
            }, function ($query) {
                $query->where(function ($filter) {
                    $filter->whereNull('position')
                        ->orWhere('position', '!=', 'Enseignant');
                });
            })
            ->with(['assignments.subject'])
            ->orderBy('staff.last_name')
            ->orderBy('staff.first_name')
            ->get();

        $subjects = Subject::query()
            ->orderBy('name')
            ->get();

        return view('staff.cards', [
            'staffMembers' => $staffMembers,
            'subjects' => $subjects,
            'title' => $onlyTeachers ? 'Gestion des professeurs' : 'Gestion du personnel',
            'subtitle' => $onlyTeachers
                ? 'Suivi des professeurs et de leurs matières'
                : 'Suivi des contrats et affectations pédagogiques',
            'ctaLabel' => $onlyTeachers ? 'Ajouter un professeur' : 'Ajouter un membre',
            'identifierLabel' => $onlyTeachers ? 'Identifiant professeur' : 'Identifiant personnel',
            'profileTitle' => $onlyTeachers ? 'Fiche professeur' : 'Fiche personnel',
            'formEyebrow' => $onlyTeachers ? 'Nouveau professeur' : 'Nouveau personnel',
            'formTitle' => $onlyTeachers ? 'Ajouter un professeur' : 'Ajouter un membre du personnel',
            'defaultPosition' => $onlyTeachers ? 'Enseignant' : '',
            'isTeacherList' => $onlyTeachers,
        ]);
    }
}
