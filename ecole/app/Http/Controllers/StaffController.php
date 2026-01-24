<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            'code_personnel' => ['required', 'string', 'max:50'],
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
        ])->validate();

        $fullName = trim($data['prenoms'] . ' ' . $data['nom']);

        User::create([
            'name' => $fullName,
            'email' => $data['email'],
            'password' => Hash::make(Str::random(16)),
        ]);

        return redirect()
            ->route('staff.index')
            ->with('status', 'Le membre du personnel a été ajouté avec succès.');
    }

    public function show(int $id): JsonResponse
    {
        $user = User::query()->findOrFail($id);

        $nameParts = preg_split('/\s+/', trim($user->name), 2);
        $firstName = $nameParts[0] ?? '';
        $lastName = $nameParts[1] ?? '';

        return response()->json([
            'staff' => [
                'id' => $user->id,
                'staff_number' => (string) $user->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'position' => null,
                'telephone_1' => null,
                'telephone_2' => null,
                'email' => $user->email,
                'adresse' => null,
                'commune' => null,
                'statut' => 'ACTIF',
            ],
            'contract' => null,
            'assignments' => [],
            'documents' => [],
        ]);
    }

    private function renderStaffList(): View
    {
        $staffMembers = User::query()
            ->orderBy('name')
            ->get()
            ->each(function (User $user) {
                $parts = preg_split('/\s+/', trim($user->name), 2);
                $user->setAttribute('code_personnel', $user->id);
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
