<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\School;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $academicYear = AcademicYear::first();

        Fee::firstOrCreate(
            ['school_id' => $school?->id, 'academic_year_id' => $academicYear?->id, 'name' => 'Scolarite'],
            ['amount' => 150000, 'fee_type' => 'annuel', 'due_date' => '2024-12-15', 'status' => 'active']
        );
    }
}
