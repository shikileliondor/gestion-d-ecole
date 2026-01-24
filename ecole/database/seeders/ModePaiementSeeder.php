<?php

namespace Database\Seeders;

use App\Models\ModePaiement;
use Illuminate\Database\Seeder;

class ModePaiementSeeder extends Seeder
{
    public function run(): void
    {
        $modes = [
            'Espèces',
            'Mobile Money',
            'Virement bancaire',
            'Chèque',
        ];

        foreach ($modes as $mode) {
            ModePaiement::query()->firstOrCreate(
                ['libelle' => $mode],
                ['actif' => true]
            );
        }
    }
}
