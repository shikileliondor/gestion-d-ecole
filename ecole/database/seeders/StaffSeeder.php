<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        Staff::firstOrCreate(
            ['staff_number' => 'EMP001'],
            [
                'school_id' => $school?->id,
                'first_name' => 'Jean',
                'last_name' => 'Traore',
                'gender' => 'male',
                'email' => 'jean.traore@example.com',
                'position' => 'Enseignant',
                'department' => 'Maths',
                'hire_date' => '2022-09-01',
                'status' => 'active',
            ]
        );
    }
}
