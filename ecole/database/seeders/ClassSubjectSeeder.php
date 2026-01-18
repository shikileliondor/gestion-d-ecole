<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\ClassSubject;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class ClassSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $class = SchoolClass::first();
        $subject = Subject::first();
        $academicYear = AcademicYear::first();
        $teacher = Staff::first();

        ClassSubject::firstOrCreate(
            ['class_id' => $class?->id, 'subject_id' => $subject?->id, 'academic_year_id' => $academicYear?->id],
            ['teacher_id' => $teacher?->id, 'coefficient' => 2]
        );
    }
}
