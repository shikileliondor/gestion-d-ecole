<?php

namespace Database\Seeders;

use App\Models\Matiere;
use Illuminate\Database\Seeder;

class MatiereSeeder extends Seeder
{
    public function run(): void
    {
        $matieres = [
            ['nom' => 'Mathématiques', 'code' => 'MATH', 'actif' => true],
            ['nom' => 'Français', 'code' => 'FR', 'actif' => true],
            ['nom' => 'Anglais', 'code' => 'ANG', 'actif' => true],
            ['nom' => 'Histoire-Géographie', 'code' => 'HG', 'actif' => true],
            ['nom' => 'Physique-Chimie', 'code' => 'PC', 'actif' => true],
            ['nom' => 'SVT', 'code' => 'SVT', 'actif' => true],
            ['nom' => 'Philosophie', 'code' => 'PHILO', 'actif' => true],
            ['nom' => 'EPS', 'code' => 'EPS', 'actif' => true],
            ['nom' => 'Informatique', 'code' => 'INFO', 'actif' => true],
            ['nom' => 'Économie', 'code' => 'ECO', 'actif' => true],
        ];

        foreach ($matieres as $matiere) {
            Matiere::query()->create($matiere);
        }
    }
}
