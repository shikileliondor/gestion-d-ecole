<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\StaffAssignment;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class StaffAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $staff = Staff::where('staff_number', 'EMP001')->first();
        $subject = Subject::query()->first();
        $class = SchoolClass::query()->first();

        if (! $staff || ! $subject) {
            return;
        }

        StaffAssignment::firstOrCreate(
            ['staff_id' => $staff->id, 'subject_id' => $subject->id],
            [
                'class_id' => $class?->id,
                'start_date' => '2023-09-01',
                'assigned_at' => now(),
                'status' => 'active',
            ]
        );
    }
}
