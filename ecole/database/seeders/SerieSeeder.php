<?php

namespace Database\Seeders;

use App\Models\Serie;
use Illuminate\Database\Seeder;

class SerieSeeder extends Seeder
{
    public function run(): void
    {
        $series = [
            ['code' => 'A', 'libelle' => 'Lettres', 'actif' => true],
            ['code' => 'C', 'libelle' => 'Scientifique', 'actif' => true],
            ['code' => 'D', 'libelle' => 'Sciences de la vie', 'actif' => true],
        ];

        foreach ($series as $serie) {
            Serie::query()->create($serie);
        }
    }
}
