<?php

namespace App\Services;

use App\Models\ClassSubject;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentClass;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ClassService
{
    public function createClass(array $data): SchoolClass
    {
        return DB::transaction(function () use ($data) {
            $schoolId = School::query()->value('id');

            if (! $schoolId) {
                throw ValidationException::withMessages([
                    'school_id' => "Aucune école n'est configurée.",
                ]);
            }

            return SchoolClass::create([
                'school_id' => $schoolId,
                'academic_year_id' => $data['academic_year_id'],
                'name' => $data['name'],
                'level' => $data['level'] ?? null,
                'series' => $data['series'] ?? null,
                'section' => $data['section'] ?? null,
                'room' => $data['room'] ?? null,
                'capacity' => $data['capacity'] ?? null,
                'manual_headcount' => $data['manual_headcount'] ?? null,
                'status' => $data['status'] ?? 'active',
            ]);
        });
    }

    public function updateHeadcount(SchoolClass $class, int $headcount): SchoolClass
    {
        $class->manual_headcount = $headcount;
        $class->save();

        return $class;
    }

    public function createSubject(array $data): Subject
    {
        return DB::transaction(function () use ($data) {
            $schoolId = School::query()->value('id');

            if (! $schoolId) {
                throw ValidationException::withMessages([
                    'school_id' => "Aucune école n'est configurée.",
                ]);
            }

            return Subject::create([
                'school_id' => $schoolId,
                'code' => $data['code'],
                'name' => $data['name'],
                'level' => $data['level'] ?? null,
                'series' => $data['series'] ?? null,
                'description' => $data['description'] ?? null,
                'credit_hours' => $data['credit_hours'] ?? null,
                'status' => $data['status'] ?? 'active',
            ]);
        });
    }

    public function assignSubject(SchoolClass $class, array $data): ClassSubject
    {
        return DB::transaction(function () use ($class, $data) {
            $subject = Subject::query()->findOrFail($data['subject_id']);

            if ($subject->school_id !== $class->school_id) {
                throw ValidationException::withMessages([
                    'subject_id' => "La matière sélectionnée n'appartient pas à la même école.",
                ]);
            }

            $teacherIds = array_unique(array_filter($data['teacher_ids'] ?? []));

            if (filled($data['teacher_id'] ?? null)) {
                $teacherIds[] = $data['teacher_id'];
            }

            $teachers = Staff::query()
                ->whereIn('id', $teacherIds)
                ->get();

            if ($teacherIds && $teachers->count() !== count($teacherIds)) {
                throw ValidationException::withMessages([
                    'teacher_ids' => 'Un ou plusieurs enseignants sont invalides.',
                ]);
            }

            foreach ($teachers as $teacher) {
                if ($teacher->school_id !== $class->school_id) {
                    throw ValidationException::withMessages([
                        'teacher_ids' => "Un ou plusieurs enseignants n'appartiennent pas à la même école.",
                    ]);
                }
            }

            $assignment = ClassSubject::updateOrCreate(
                [
                    'class_id' => $class->id,
                    'subject_id' => $subject->id,
                    'academic_year_id' => $class->academic_year_id,
                ],
                [
                    'teacher_id' => $data['teacher_id'] ?? null,
                    'coefficient' => $data['coefficient'] ?? 1,
                    'color' => $data['color'] ?? null,
                    'is_optional' => (bool) ($data['is_optional'] ?? false),
                ]
            );

            $assignment->teachers()->sync($teachers->pluck('id')->all());

            return $assignment;
        });
    }

    public function assignStudent(SchoolClass $class, array $data): StudentClass
    {
        return DB::transaction(function () use ($class, $data) {
            $student = Student::query()->findOrFail($data['student_id']);

            if ($student->school_id !== $class->school_id) {
                throw ValidationException::withMessages(array(
                    'student_id' => "L'élève sélectionné n'appartient pas à la même école.",
                ));
            }

            $alreadyAssigned = StudentClass::query()
                ->where('student_id', $student->id)
                ->where('academic_year_id', $class->academic_year_id)
                ->exists();

            if ($alreadyAssigned) {
                throw ValidationException::withMessages([
                    'student_id' => "Cet élève est déjà affecté à une classe pour cette année scolaire.",
                ]);
            }

            return StudentClass::create([
                'student_id' => $student->id,
                'class_id' => $class->id,
                'academic_year_id' => $class->academic_year_id,
                'start_date' => $data['start_date'] ?? null,
                'status' => $data['status'] ?? 'active',
                'assigned_at' => now(),
            ]);
        });
    }
}
