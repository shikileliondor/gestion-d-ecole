<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AnneeScolaireSeeder extends Seeder
{
    public function run(): void
    {
        $annees = [
            [
                'libelle' => '2022-2023',
                'date_debut' => Carbon::create(2022, 9, 1),
                'date_fin' => Carbon::create(2023, 6, 30),
                'statut' => 'ARCHIVEE',
            ],
            [
                'libelle' => '2023-2024',
                'date_debut' => Carbon::create(2023, 9, 1),
                'date_fin' => Carbon::create(2024, 6, 30),
                'statut' => 'CLOTUREE',
            ],
            [
                'libelle' => '2024-2025',
                'date_debut' => Carbon::create(2024, 9, 1),
                'date_fin' => Carbon::create(2025, 6, 30),
                'statut' => 'ACTIVE',
            ],
        ];

        foreach ($annees as $annee) {
            AnneeScolaire::query()->create($annee);
        }
    }
}
