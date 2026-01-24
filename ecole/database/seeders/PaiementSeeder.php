<?php

namespace Database\Seeders;

use App\Models\Paiement;
use App\Models\FraisInscription;
use Illuminate\Database\Seeder;

class PaiementSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $modes = ['CASH', 'DEPOT', 'VIREMENT_INTERNE'];

        FraisInscription::query()->each(function (FraisInscription $fraisInscription) use ($faker, $modes) {
            if ($faker->boolean(70)) {
                Paiement::query()->create([
                    'inscription_id' => $fraisInscription->inscription_id,
                    'frais_inscription_id' => $fraisInscription->id,
                    'montant_paye' => $faker->randomFloat(2, 10000, (float) $fraisInscription->montant_du),
                    'date_paiement' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                    'mode_paiement' => $faker->randomElement($modes),
                    'reference' => $faker->boolean(50) ? strtoupper($faker->bothify('REF-####')) : null,
                ]);
            }
        });
    }
}
