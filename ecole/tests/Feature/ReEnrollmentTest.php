<?php

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Inscription;
use App\Models\Niveau;
use App\Models\User;

it('does not propose a class from a previous academic year for re-enrollment', function () {
    $user = User::factory()->create();

    $previousYear = AnneeScolaire::create([
        'libelle' => '2024-2025',
        'date_debut' => '2024-09-01',
        'date_fin' => '2025-07-01',
        'statut' => 'CLOTUREE',
    ]);

    $activeYear = AnneeScolaire::create([
        'libelle' => '2025-2026',
        'date_debut' => '2025-09-01',
        'date_fin' => '2026-07-01',
        'statut' => 'ACTIVE',
    ]);

    $niveau = Niveau::create([
        'code' => '3E',
        'ordre' => 3,
        'actif' => true,
    ]);

    $previousClass = Classe::create([
        'annee_scolaire_id' => $previousYear->id,
        'niveau_id' => $niveau->id,
        'serie_id' => null,
        'nom' => '3E 1',
        'effectif_max' => 40,
        'actif' => true,
    ]);

    $student = Eleve::create([
        'matricule' => 'MAT0001',
        'nom' => 'CHEVALIER',
        'prenoms' => 'Mathilde',
    ]);

    Inscription::create([
        'annee_scolaire_id' => $previousYear->id,
        'eleve_id' => $student->id,
        'classe_id' => $previousClass->id,
        'date_inscription' => '2024-09-02',
        'statut' => 'INSCRIT',
    ]);

    $response = $this->actingAs($user)->get(route('students.re-enrollments', [
        'student_id' => $student->id,
    ]));

    $response->assertOk();
    $response->assertSee("Aucune classe recommandée n'est disponible pour l'année active.");
    $response->assertDontSee('Valider la réinscription');

    expect(Inscription::query()->where('annee_scolaire_id', $activeYear->id)->count())->toBe(0);
});
