<?php

namespace App\Http\Controllers;

use App\Models\StaffDocument;
use App\Models\User;
use App\Services\MatriculeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function index(): View
    {
        return $this->renderStaffList();
    }

    public function store(Request $request): RedirectResponse
    {
        $data = Validator::make($request->all(), [
            'code_personnel' => ['nullable', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'mimes:pdf,jpeg,jpg,png,doc,docx', 'max:10240'],
            'documents_labels' => ['nullable', 'array'],
            'documents_labels.*' => ['nullable', 'string', 'max:255'],
        ])->validate();

        $fullName = trim($data['prenoms'] . ' ' . $data['nom']);
        $matricule = app(MatriculeService::class)->generateForStaff();

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos/staff', 'public');
        }

        $user = User::create([
            'name' => $fullName,
            'matricule' => $matricule,
            'email' => $data['email'],
            'password' => Hash::make(Str::random(16)),
            'photo_path' => $photoPath,
        ]);

        if (!empty($data['documents'])) {
            $labels = $request->input('documents_labels', []);
            foreach ($data['documents'] as $index => $document) {
                $path = $document->store('documents/staff', 'public');
                $label = trim((string) ($labels[$index] ?? ''));
                StaffDocument::create([
                    'user_id' => $user->id,
                    'libelle' => $label !== '' ? $label : pathinfo($document->getClientOriginalName(), PATHINFO_FILENAME),
                    'file_path' => $path,
                    'original_name' => $document->getClientOriginalName(),
                    'mime_type' => $document->getClientMimeType(),
                    'size_bytes' => $document->getSize(),
                ]);
            }
        }

        return redirect()
            ->route('staff.index')
            ->with('status', 'Le membre du personnel a été ajouté avec succès.');
    }

    public function show(int $id): JsonResponse
    {
        $user = User::query()
            ->with('documents')
            ->findOrFail($id);

        $nameParts = preg_split('/\s+/', trim($user->name), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        return response()->json([
            'staff' => [
                'id' => $user->id,
                'staff_number' => $user->matricule ?? (string) $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'position' => null,
                'telephone_1' => null,
                'telephone_2' => null,
                'email' => $user->email,
                'adresse' => null,
                'commune' => null,
                'statut' => 'ACTIF',
                'photo_url' => $user->photo_path ? Storage::url($user->photo_path) : null,
            ],
            'contract' => null,
            'assignments' => [],
            'documents' => $user->documents->map(fn (StaffDocument $document) => [
                'id' => $document->id,
                'libelle' => $document->libelle,
                'url' => $document->file_path ? Storage::url($document->file_path) : null,
                'original_name' => $document->original_name,
            ]),
        ]);
    }

    private function renderStaffList(): View
    {
        $staffMembers = User::query()
            ->orderBy('name')
            ->get()
            ->each(function (User $user) {
                $parts = preg_split('/\s+/', trim($user->name), 2);
                $user->setAttribute('code_personnel', $user->matricule ?? $user->id);
                $user->setAttribute('nom', $parts[1] ?? $parts[0] ?? '');
                $user->setAttribute('prenoms', $parts[0] ?? '');
                $user->setAttribute('poste', null);
                $user->setAttribute('categorie_personnel', null);
                $user->setAttribute('telephone_1', null);
                $user->setAttribute('statut', 'ACTIF');
            });

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
