<?php

namespace Database\Seeders;

use App\Models\ParametreEcole;
use Illuminate\Database\Seeder;

class ParametreEcoleSeeder extends Seeder
{
    public function run(): void
    {
        ParametreEcole::query()->firstOrCreate([], [
            'facture_prefix' => 'FACT-{YYYY}-{####}',
            'recu_prefix' => 'REC-{YYYY}-{####}',
            'matricule_prefix' => 'LYC-{YY}-{####}',
            'remises_actives' => true,
            'plafond_remise' => 20,
            'validation_remise' => 'Direction uniquement',
            'politique_impayes' => 'BLOCK',
        ]);
    }
}
