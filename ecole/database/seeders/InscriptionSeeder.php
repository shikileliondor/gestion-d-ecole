<?php

namespace Database\Seeders;

use App\Models\AnneeScolaire;
use App\Models\Classe;
use App\Models\Eleve;
use App\Models\Inscription;
use Illuminate\Database\Seeder;

class InscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');

        $annee = AnneeScolaire::query()->where('statut', 'ACTIVE')->first()
            ?? AnneeScolaire::query()->first();
        $classes = Classe::query()->pluck('id')->all();
        $statuts = ['INSCRIT', 'REDOUBLANT', 'TRANSFERE', 'ABANDON', 'EXCLU'];

        Eleve::query()->each(function (Eleve $eleve) use ($faker, $annee, $classes, $statuts) {
            Inscription::query()->create([
                'annee_scolaire_id' => $annee->id,
                'eleve_id' => $eleve->id,
                'classe_id' => $classes[array_rand($classes)],
                'date_inscription' => $faker->dateTimeBetween('-8 months', '-1 month')->format('Y-m-d'),
                'statut' => $faker->randomElement($statuts),
            ]);
        });
    }
}
