<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\StudentClass;
use Illuminate\Database\Seeder;

class StudentClassSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first();
        $class = SchoolClass::first();
        $academicYear = AcademicYear::first();

        StudentClass::firstOrCreate(
            ['student_id' => $student?->id, 'class_id' => $class?->id, 'academic_year_id' => $academicYear?->id],
            ['start_date' => '2024-09-05', 'status' => 'active', 'assigned_at' => now()]
        );
    }
}
