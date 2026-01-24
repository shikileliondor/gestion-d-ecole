<?php

namespace Database\Seeders;

use App\Models\Echeancier;
use App\Models\FraisInscription;
use Illuminate\Database\Seeder;

class EcheancierSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $statuts = ['A_PAYER', 'PAYE', 'RETARD'];

        FraisInscription::query()->each(function (FraisInscription $fraisInscription) use ($faker, $statuts) {
            $parts = $faker->numberBetween(1, 3);
            $montant = (float) $fraisInscription->montant_du / $parts;

            for ($i = 1; $i <= $parts; $i++) {
                Echeancier::query()->create([
                    'frais_inscription_id' => $fraisInscription->id,
                    'montant_prevu' => $montant,
                    'date_echeance' => $faker->dateTimeBetween('now', '+4 months')->format('Y-m-d'),
                    'statut' => $faker->randomElement($statuts),
                ]);
            }
        });
    }
}
