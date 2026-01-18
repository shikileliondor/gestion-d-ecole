<?php

namespace Database\Seeders;

use App\Models\Appreciation;
use App\Models\Bulletin;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class AppreciationSeeder extends Seeder
{
    public function run(): void
    {
        $bulletin = Bulletin::first();
        $teacher = Staff::first();

        Appreciation::firstOrCreate(
            ['bulletin_id' => $bulletin?->id, 'teacher_id' => $teacher?->id],
            ['content' => 'Eleve serieux et assidu.']
        );
    }
}
