<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Frais;
use App\Models\Niveau;
use App\Models\TypeFrais;
use Illuminate\Database\Seeder;

class FraisSeeder extends Seeder
{
    public function run(): void
    {
        $annee = AnneeScolaire::query()->where('statut', 'ACTIVE')->first()
            ?? AnneeScolaire::query()->first();
        $types = TypeFrais::query()->pluck('id')->all();
        $periodicites = ['UNIQUE', 'MENSUEL', 'TRIMESTRIEL', 'ANNUEL'];

        Niveau::query()->each(function (Niveau $niveau) use ($annee, $types, $periodicites) {
            foreach ($types as $typeId) {
                Frais::query()->create([
                    'annee_scolaire_id' => $annee->id,
                    'niveau_id' => $niveau->id,
                    'type_frais_id' => $typeId,
                    'periodicite' => $periodicites[array_rand($periodicites)],
                    'montant' => 50000 + ($niveau->ordre * 5000),
                    'actif' => true,
                ]);
            }
        });
    }
}
