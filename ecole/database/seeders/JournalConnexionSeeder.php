<?php

namespace Database\Seeders;

use App\Models\JournalConnexion;
use App\Models\User;
use Illuminate\Database\Seeder;

class JournalConnexionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $users = User::query()->pluck('id')->all();

        for ($i = 0; $i < 20; $i++) {
            JournalConnexion::query()->create([
                'user_id' => $users[array_rand($users)],
                'date_connexion' => $faker->dateTimeBetween('-1 month', 'now')->format('Y-m-d H:i:s'),
                'ip_adresse' => $faker->ipv4(),
                'user_agent' => $faker->userAgent(),
            ]);
        }
    }
}
