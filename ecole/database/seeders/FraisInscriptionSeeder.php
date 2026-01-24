<?php

namespace Database\Seeders;

use App\Models\Frais;
use App\Models\FraisInscription;
use App\Models\Inscription;
use Illuminate\Database\Seeder;

class FraisInscriptionSeeder extends Seeder
{
    public function run(): void
    {
        Inscription::query()->each(function (Inscription $inscription) {
            $fraisList = Frais::query()
                ->where('niveau_id', $inscription->classe->niveau_id)
                ->get();

            foreach ($fraisList as $frais) {
                FraisInscription::query()->create([
                    'inscription_id' => $inscription->id,
                    'frais_id' => $frais->id,
                    'montant_du' => $frais->montant,
                    'statut' => 'IMPAYE',
                ]);
            }
        });
    }
}
