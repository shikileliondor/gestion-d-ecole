<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Support\Facades\DB;

class StudentService
{
    public function create(array $data): Student
    {
        return DB::transaction(function () use ($data) {
            return Student::create($data);
        });
    }

    public function update(Student $student, array $data): Student
    {
        return DB::transaction(function () use ($student, $data) {
            $student->fill($data);
            $student->save();

            return $student;
        });
    }
}
