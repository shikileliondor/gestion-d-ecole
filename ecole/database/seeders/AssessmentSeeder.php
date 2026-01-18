<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Assessment;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class AssessmentSeeder extends Seeder
{
    public function run(): void
    {
        $class = SchoolClass::first();
        $subject = Subject::first();
        $academicYear = AcademicYear::first();

        Assessment::firstOrCreate(
            ['class_id' => $class?->id, 'subject_id' => $subject?->id, 'academic_year_id' => $academicYear?->id, 'name' => 'Devoir 1'],
            ['type' => 'quiz', 'max_score' => 20, 'weight' => 1, 'assessment_date' => '2024-11-15']
        );
    }
}
