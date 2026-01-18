<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        School::firstOrCreate(
            ['code' => 'ECOLE-001'],
            [
                'name' => 'Ecole Demo',
                'type' => 'Secondaire',
                'registration_number' => 'REG-2024-001',
                'address' => '1 Rue Principale',
                'city' => 'Abidjan',
                'country' => 'Cote d'Ivoire',
                'phone' => '+2250102030405',
                'email' => 'contact@ecole-demo.test',
                'website' => 'https://ecole-demo.test',
                'founded_at' => '2010-09-01',
                'created_by' => $admin?->id,
                'updated_by' => $admin?->id,
            ]
        );
    }
}
