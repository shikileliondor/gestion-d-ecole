<?php

namespace Database\Seeders;

use App\Models\Paiement;
use App\Models\Recu;
use Illuminate\Database\Seeder;

class RecuSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');

        Paiement::query()->each(function (Paiement $paiement) use ($faker) {
            Recu::query()->create([
                'numero_recu' => 'RCU-' . strtoupper($faker->bothify('####??')),
                'paiement_id' => $paiement->id,
                'date_emission' => $paiement->date_paiement,
                'montant' => $paiement->montant_paye,
            ]);
        });
    }
}
