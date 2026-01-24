<?php

namespace Database\Seeders;

use App\Models\Export;
use App\Models\User;
use Illuminate\Database\Seeder;

class ExportSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $types = [
            'BULLETIN',
            'RELEVE_NOTES',
            'FACTURE',
            'RECU',
            'LISTE_CLASSE',
            'RAPPORT_SCOLARITE',
            'RAPPORT_FINANCIER',
        ];
        $formats = ['PDF', 'EXCEL'];

        $users = User::query()->pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            Export::query()->create([
                'user_id' => $users[array_rand($users)],
                'type_export' => $faker->randomElement($types),
                'reference_id' => $faker->boolean(60) ? $faker->numberBetween(1, 100) : null,
                'format' => $faker->randomElement($formats),
                'file_path' => 'exports/' . $faker->uuid() . '.' . strtolower($faker->randomElement($formats)),
                'created_at' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
