<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Evaluation;
use App\Models\Matiere;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $annee = AnneeScolaire::query()->where('statut', 'ACTIVE')->first()
            ?? AnneeScolaire::query()->first();
        $matieres = Matiere::query()->pluck('id')->all();
        $types = ['INTERRO', 'DEVOIR', 'COMPOSITION', 'ORAL', 'PRATIQUE'];

        Classe::query()->each(function (Classe $classe) use ($faker, $annee, $matieres, $types) {
            $matiereIds = $matieres;
            shuffle($matiereIds);
            $selected = array_slice($matiereIds, 0, 2);

            foreach ($selected as $matiereId) {
                Evaluation::query()->create([
                    'annee_scolaire_id' => $annee->id,
                    'classe_id' => $classe->id,
                    'matiere_id' => $matiereId,
                    'type' => $faker->randomElement($types),
                    'titre' => $faker->sentence(3),
                    'date_evaluation' => $faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
                    'note_sur' => 20,
                ]);
            }
        });
    }
}
