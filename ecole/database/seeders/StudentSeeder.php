<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\Student;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $academicYear = AcademicYear::first();

        Student::firstOrCreate(
            ['admission_number' => '2024-KOA'],
            [
                'school_id' => $school?->id,
                'academic_year_id' => $academicYear?->id,
                'first_name' => 'Awa',
                'last_name' => 'Kone',
                'gender' => 'female',
                'date_of_birth' => '2011-05-14',
                'enrollment_date' => '2024-09-05',
                'status' => 'active',
            ]
        );
    }
}
