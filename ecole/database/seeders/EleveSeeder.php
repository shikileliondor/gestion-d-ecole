<?php

namespace Database\Seeders;

use App\Models\Eleve;
use Illuminate\Database\Seeder;

class EleveSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');

        for ($i = 1; $i <= 30; $i++) {
            Eleve::query()->create([
                'matricule' => sprintf('ELV%04d', $i),
                'nom' => strtoupper($faker->lastName()),
                'prenoms' => $faker->firstName(),
                'sexe' => $faker->randomElement(['M', 'F', 'AUTRE']),
                'date_naissance' => $faker->dateTimeBetween('-18 years', '-10 years')->format('Y-m-d'),
                'lieu_naissance' => $faker->city(),
                'nationalite' => $faker->country(),
                'photo_path' => null,
            ]);
        }
    }
}
