<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Niveau;
use App\Models\Serie;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    public function run(): void
    {
        $annee = AnneeScolaire::query()->where('statut', 'ACTIVE')->first()
            ?? AnneeScolaire::query()->first();

        $niveaux = Niveau::query()->orderBy('ordre')->get();
        $series = Serie::query()->pluck('id')->all();

        foreach ($niveaux as $niveau) {
            for ($i = 1; $i <= 2; $i++) {
                Classe::query()->create([
                    'annee_scolaire_id' => $annee->id,
                    'niveau_id' => $niveau->id,
                    'serie_id' => $series ? ($niveau->ordre >= 5 ? $series[array_rand($series)] : null) : null,
                    'nom' => sprintf('%s %d', $niveau->code, $i),
                    'effectif_max' => 40,
                    'actif' => true,
                ]);
            }
        }
    }
}
