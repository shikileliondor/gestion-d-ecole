<?php

namespace Database\Seeders;

use App\Models\Niveau;
use Illuminate\Database\Seeder;

class NiveauSeeder extends Seeder
{
    public function run(): void
    {
        $niveaux = [
            ['code' => '6E', 'ordre' => 1, 'actif' => true],
            ['code' => '5E', 'ordre' => 2, 'actif' => true],
            ['code' => '4E', 'ordre' => 3, 'actif' => true],
            ['code' => '3E', 'ordre' => 4, 'actif' => true],
            ['code' => '2N', 'ordre' => 5, 'actif' => true],
            ['code' => '1E', 'ordre' => 6, 'actif' => true],
            ['code' => 'TLE', 'ordre' => 7, 'actif' => true],
        ];

        foreach ($niveaux as $niveau) {
            Niveau::query()->create($niveau);
        }
    }
}
