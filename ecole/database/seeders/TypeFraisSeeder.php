<?php

namespace Database\Seeders;

use App\Models\TypeFrais;
use Illuminate\Database\Seeder;

class TypeFraisSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['libelle' => 'Inscription', 'obligatoire' => true, 'actif' => true],
            ['libelle' => 'Scolarité', 'obligatoire' => true, 'actif' => true],
            ['libelle' => 'Cantine', 'obligatoire' => false, 'actif' => true],
            ['libelle' => 'Transport', 'obligatoire' => false, 'actif' => true],
            ['libelle' => 'Uniforme', 'obligatoire' => false, 'actif' => true],
        ];

        foreach ($types as $type) {
            TypeFrais::query()->create($type);
        }
    }
}
