<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\EleveUrgence;
use Illuminate\Database\Seeder;

class EleveUrgenceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $liens = ['PERE', 'MERE', 'TUTEUR', 'FRERE_SOEUR', 'AUTRE'];

        Eleve::query()->each(function (Eleve $eleve) use ($faker, $liens) {
            EleveUrgence::query()->create([
                'eleve_id' => $eleve->id,
                'nom_complet' => $faker->name(),
                'lien' => $faker->randomElement($liens),
                'telephone' => $faker->phoneNumber(),
            ]);
        });
    }
}
