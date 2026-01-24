<?php

namespace Database\Seeders;

use App\Models\Facture;
use App\Models\FraisInscription;
use App\Models\Inscription;
use Illuminate\Database\Seeder;

class FactureSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');

        Inscription::query()->each(function (Inscription $inscription) use ($faker) {
            $total = FraisInscription::query()
                ->where('inscription_id', $inscription->id)
                ->sum('montant_du');

            Facture::query()->create([
                'numero_facture' => 'FAC-' . strtoupper($faker->bothify('####??')),
                'inscription_id' => $inscription->id,
                'date_emission' => $faker->dateTimeBetween('-2 months', 'now')->format('Y-m-d'),
                'montant_total' => $total > 0 ? $total : $faker->numberBetween(50000, 150000),
                'statut' => $faker->randomElement(['EMISE', 'PARTIELLE', 'PAYEE', 'ANNULEE']),
                'commentaire' => $faker->boolean(30) ? $faker->sentence(6) : null,
            ]);
        });
    }
}
