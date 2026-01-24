<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\EleveContact;
use Illuminate\Database\Seeder;

class EleveContactSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');

        Eleve::query()->each(function (Eleve $eleve) use ($faker) {
            EleveContact::query()->create([
                'eleve_id' => $eleve->id,
                'telephone' => $faker->phoneNumber(),
                'email' => $faker->safeEmail(),
                'adresse' => $faker->streetAddress(),
                'commune' => $faker->citySuffix(),
                'ville' => $faker->city(),
            ]);
        });
    }
}
