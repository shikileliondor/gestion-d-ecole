<?php

namespace Database\Seeders;

use App\Models\Inscription;
use App\Models\Remise;
use Illuminate\Database\Seeder;

class RemiseSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $types = ['BOURSE', 'REDUCTION', 'GESTE'];

        Inscription::query()->each(function (Inscription $inscription) use ($faker, $types) {
            if ($faker->boolean(30)) {
                Remise::query()->create([
                    'inscription_id' => $inscription->id,
                    'type_remise' => $faker->randomElement($types),
                    'montant' => $faker->randomFloat(2, 5000, 20000),
                    'motif' => $faker->sentence(4),
                ]);
            }
        });
    }
}
