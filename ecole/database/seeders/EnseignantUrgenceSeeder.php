<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use App\Models\EnseignantUrgence;
use Illuminate\Database\Seeder;

class EnseignantUrgenceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $liens = ['PERE', 'MERE', 'CONJOINT', 'FRERE_SOEUR', 'AUTRE'];

        Enseignant::query()->each(function (Enseignant $enseignant) use ($faker, $liens) {
            EnseignantUrgence::query()->create([
                'enseignant_id' => $enseignant->id,
                'nom_complet' => $faker->name(),
                'lien' => $faker->randomElement($liens),
                'telephone' => $faker->phoneNumber(),
            ]);
        });
    }
}
