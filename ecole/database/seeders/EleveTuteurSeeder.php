<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\EleveTuteur;
use Illuminate\Database\Seeder;

class EleveTuteurSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $liens = ['PERE', 'MERE', 'TUTEUR', 'AUTRE'];

        Eleve::query()->each(function (Eleve $eleve) use ($faker, $liens) {
            $count = $faker->numberBetween(1, 2);
            for ($i = 0; $i < $count; $i++) {
                EleveTuteur::query()->create([
                    'eleve_id' => $eleve->id,
                    'lien' => $faker->randomElement($liens),
                    'nom' => strtoupper($faker->lastName()),
                    'prenoms' => $faker->firstName(),
                    'telephone_1' => $faker->phoneNumber(),
                    'telephone_2' => $faker->boolean(30) ? $faker->phoneNumber() : null,
                    'email' => $faker->boolean(60) ? $faker->safeEmail() : null,
                    'profession' => $faker->jobTitle(),
                    'adresse' => $faker->streetAddress(),
                ]);
            }
        });
    }
}
