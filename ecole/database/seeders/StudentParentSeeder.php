<?php

namespace Database\Seeders;

use App\Models\ParentProfile;
use App\Models\Student;
use App\Models\StudentParent;
use Illuminate\Database\Seeder;

class StudentParentSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first();
        $parent = ParentProfile::first();

        StudentParent::firstOrCreate(
            ['student_id' => $student?->id, 'parent_id' => $parent?->id],
            ['is_primary' => true, 'has_custody' => true]
        );
    }
}
