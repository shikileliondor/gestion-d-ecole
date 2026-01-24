<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use App\Models\Inscription;
use App\Models\Note;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');

        Evaluation::query()->each(function (Evaluation $evaluation) use ($faker) {
            $inscriptions = Inscription::query()
                ->where('classe_id', $evaluation->classe_id)
                ->pluck('id')
                ->all();

            $selection = array_slice($inscriptions, 0, 10);

            foreach ($selection as $inscriptionId) {
                Note::query()->create([
                    'evaluation_id' => $evaluation->id,
                    'inscription_id' => $inscriptionId,
                    'valeur' => $faker->randomFloat(2, 5, 20),
                ]);
            }
        });
    }
}
