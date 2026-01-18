<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\School;
use Illuminate\Database\Seeder;

class AcademicYearSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        AcademicYear::firstOrCreate(
            ['school_id' => $school?->id, 'name' => '2024-2025'],
            [
                'start_date' => '2024-09-02',
                'end_date' => '2025-06-30',
                'is_current' => true,
                'status' => 'active',
            ]
        );
    }
}
