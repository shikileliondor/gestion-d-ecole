<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        Subject::firstOrCreate(
            ['school_id' => $school?->id, 'code' => 'MATH'],
            ['name' => 'Mathematiques', 'description' => 'Cours de base', 'credit_hours' => 4]
        );
    }
}
