<?php

namespace Database\Seeders;

use App\Models\JournalAction;
use App\Models\User;
use Illuminate\Database\Seeder;

class JournalActionSeeder extends Seeder
{
    public function run(): void
    {
        $faker = fake('fr_FR');
        $actions = ['CREATE', 'UPDATE', 'DELETE', 'EXPORT'];

        $users = User::query()->pluck('id')->all();

        for ($i = 0; $i < 20; $i++) {
            JournalAction::query()->create([
                'user_id' => $users[array_rand($users)],
                'action' => $faker->randomElement($actions),
                'table_cible' => $faker->randomElement(['eleves', 'inscriptions', 'factures', 'paiements']),
                'enregistrement_id' => $faker->numberBetween(1, 100),
                'anciennes_valeurs' => ['champ' => 'ancienne'],
                'nouvelles_valeurs' => ['champ' => 'nouvelle'],
                'ip_adresse' => $faker->ipv4(),
                'user_agent' => $faker->userAgent(),
            ]);
        }
    }
}
