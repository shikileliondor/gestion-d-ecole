<?php

namespace Database\Seeders;

use App\Models\Enseignant;
use App\Models\EnseignantDocument;
use Illuminate\Database\Seeder;

class EnseignantDocumentSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $types = ['CNI', 'DIPLOME', 'CONTRAT', 'CV', 'AUTRE'];

        Enseignant::query()->each(function (Enseignant $enseignant) use ($faker, $types) {
            $count = $faker->numberBetween(1, 2);
            for ($i = 0; $i < $count; $i++) {
                EnseignantDocument::query()->create([
                    'enseignant_id' => $enseignant->id,
                    'type_document' => $faker->randomElement($types),
                    'libelle' => $faker->sentence(3),
                    'file_path' => 'documents/enseignants/' . $faker->uuid() . '.pdf',
                    'original_name' => $faker->word() . '.pdf',
                    'mime_type' => 'application/pdf',
                    'size_bytes' => $faker->numberBetween(10000, 500000),
                ]);
            }
        });
    }
}
