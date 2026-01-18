<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Grade;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $assessment = Assessment::first();
        $student = Student::first();
        $staff = Staff::first();

        Grade::firstOrCreate(
            ['assessment_id' => $assessment?->id, 'student_id' => $student?->id],
            ['score' => 15.5, 'remark' => 'Bon travail', 'graded_at' => now(), 'graded_by' => $staff?->id]
        );
    }
}
