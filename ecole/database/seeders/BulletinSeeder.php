<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Bulletin;
use App\Models\Student;
use Illuminate\Database\Seeder;

class BulletinSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first();
        $academicYear = AcademicYear::first();

        Bulletin::firstOrCreate(
            ['student_id' => $student?->id, 'academic_year_id' => $academicYear?->id, 'term' => 'T1'],
            ['average_score' => 15.2, 'rank' => 1, 'status' => 'published', 'published_at' => now()]
        );
    }
}
