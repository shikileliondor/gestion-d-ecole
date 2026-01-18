<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Timetable;
use Illuminate\Database\Seeder;

class TimetableSeeder extends Seeder
{
    public function run(): void
    {
        $class = SchoolClass::first();
        $academicYear = AcademicYear::first();

        Timetable::firstOrCreate(
            ['class_id' => $class?->id, 'academic_year_id' => $academicYear?->id, 'term' => 'T1'],
            ['week_start_date' => '2024-10-07', 'status' => 'published']
        );
    }
}
