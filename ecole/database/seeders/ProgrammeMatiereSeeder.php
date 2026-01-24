<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Matiere;
use App\Models\Niveau;
use App\Models\ProgrammeMatiere;
use App\Models\Serie;
use Illuminate\Database\Seeder;

class ProgrammeMatiereSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $annee = AnneeScolaire::query()->where('statut', 'ACTIVE')->first()
            ?? AnneeScolaire::query()->first();

        $matieres = Matiere::query()->pluck('id')->all();
        $series = Serie::query()->pluck('id')->all();

        Niveau::query()->each(function (Niveau $niveau) use ($faker, $annee, $matieres, $series) {
            $matiereIds = $matieres;
            shuffle($matiereIds);
            $selected = array_slice($matiereIds, 0, 5);

            foreach ($selected as $matiereId) {
                ProgrammeMatiere::query()->create([
                    'annee_scolaire_id' => $annee->id,
                    'niveau_id' => $niveau->id,
                    'serie_id' => $niveau->ordre >= 5 && $series ? $series[array_rand($series)] : null,
                    'matiere_id' => $matiereId,
                    'coefficient' => $faker->randomFloat(2, 1, 5),
                    'obligatoire' => $faker->boolean(70),
                    'actif' => true,
                ]);
            }
        });
    }
}
