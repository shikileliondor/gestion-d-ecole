<?php

namespace Database\Seeders;

use App\Models\AffectationEnseignant;
use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Enseignant;
use App\Models\Matiere;
use Illuminate\Database\Seeder;

class AffectationEnseignantSeeder extends Seeder
{
    public function run(): void
    {
        $annee = AnneeScolaire::query()->where('statut', 'ACTIVE')->first()
            ?? AnneeScolaire::query()->first();
        $enseignants = Enseignant::query()->pluck('id')->all();
        $matieres = Matiere::query()->pluck('id')->all();

        Classe::query()->each(function (Classe $classe) use ($annee, $enseignants, $matieres) {
            $matiereIds = $matieres;
            shuffle($matiereIds);
            $selected = array_slice($matiereIds, 0, 3);

            foreach ($selected as $matiereId) {
                AffectationEnseignant::query()->create([
                    'annee_scolaire_id' => $annee->id,
                    'enseignant_id' => $enseignants[array_rand($enseignants)],
                    'classe_id' => $classe->id,
                    'matiere_id' => $matiereId,
                ]);
            }
        });
    }
}
