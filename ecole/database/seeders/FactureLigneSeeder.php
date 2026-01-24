<?php

namespace Database\Seeders;

use App\Models\Facture;
use App\Models\FactureLigne;
use App\Models\FraisInscription;
use Illuminate\Database\Seeder;

class FactureLigneSeeder extends Seeder
{
    public function run(): void
    {
        Facture::query()->each(function (Facture $facture) {
            $fraisInscriptions = FraisInscription::query()
                ->where('inscription_id', $facture->inscription_id)
                ->get();

            foreach ($fraisInscriptions as $fraisInscription) {
                FactureLigne::query()->create([
                    'facture_id' => $facture->id,
                    'type_frais_id' => $fraisInscription->frais->type_frais_id,
                    'montant' => $fraisInscription->montant_du,
                ]);
            }
        });
    }
}
