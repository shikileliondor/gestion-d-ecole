<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Ranking;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Seeder;

class RankingSeeder extends Seeder
{
    public function run(): void
    {
        $class = SchoolClass::first();
        $academicYear = AcademicYear::first();
        $student = Student::first();

        Ranking::firstOrCreate(
            ['class_id' => $class?->id, 'academic_year_id' => $academicYear?->id, 'term' => 'T1', 'student_id' => $student?->id],
            ['rank' => 1, 'average_score' => 15.2]
        );
    }
}
