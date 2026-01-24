<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use Illuminate\Database\Seeder;

class EnseignantSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $types = ['PERMANENT', 'VACATAIRE', 'STAGIAIRE'];
        $statuts = ['ACTIF', 'SUSPENDU', 'PARTI'];

        for ($i = 1; $i <= 12; $i++) {
            Enseignant::query()->create([
                'matricule' => sprintf('ENS%04d', $i),
                'nom' => strtoupper($faker->lastName()),
                'prenoms' => $faker->firstName(),
                'sexe' => $faker->randomElement(['M', 'F', 'AUTRE']),
                'telephone_1' => $faker->phoneNumber(),
                'telephone_2' => $faker->boolean(40) ? $faker->phoneNumber() : null,
                'email' => $faker->safeEmail(),
                'specialite' => $faker->jobTitle(),
                'photo_path' => null,
                'type_enseignant' => $faker->randomElement($types),
                'date_debut_service' => $faker->dateTimeBetween('-6 years', '-1 years')->format('Y-m-d'),
                'date_fin_service' => $faker->boolean(15) ? $faker->dateTimeBetween('-6 months', '+6 months')->format('Y-m-d') : null,
                'statut' => $faker->randomElement($statuts),
            ]);
        }
    }
}
