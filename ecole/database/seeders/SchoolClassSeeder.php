<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class SchoolClassSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $academicYear = AcademicYear::first();

        SchoolClass::firstOrCreate(
            ['school_id' => $school?->id, 'academic_year_id' => $academicYear?->id, 'name' => '6e'],
            [
                'level' => 'College',
                'section' => 'A',
                'room' => 'Salle 1',
                'capacity' => 30,
                'status' => 'active',
            ]
        );
    }
}
