<?php

namespace Database\Seeders;

use App\Models\ParentProfile;
use Illuminate\Database\Seeder;

class ParentProfileSeeder extends Seeder
{
    public function run(): void
    {
        ParentProfile::firstOrCreate(
            ['first_name' => 'Moussa', 'last_name' => 'Kone'],
            [
                'gender' => 'male',
                'relationship' => 'pere',
                'phone' => '+2250102030406',
                'email' => 'parent@example.com',
                'is_primary' => true,
            ]
        );
    }
}
