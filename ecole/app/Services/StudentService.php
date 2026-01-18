<?php

namespace App\Services;

use App\Models\Student;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StudentService
{
    /**
     * Retrieve a paginated list of students for the index view.
     */
    public function list(array $filters): LengthAwarePaginator
    {
        $query = Student::query()
            ->with([
                'studentClasses.class',
                'parents' => fn ($builder) => $builder->select('parents.id', 'first_name', 'last_name', 'phone'),
            ])
            ->when($filters['school_id'] ?? null, fn ($builder, $schoolId) => $builder->where('school_id', $schoolId))
            ->when($filters['academic_year_id'] ?? null, fn ($builder, $yearId) => $builder->where('academic_year_id', $yearId))
            ->when($filters['status'] ?? null, fn ($builder, $status) => $builder->where('status', $status))
            ->when($filters['search'] ?? null, function ($builder, $search) {
                $builder->where(function ($subQuery) use ($search) {
                    $subQuery->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('admission_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name');

        $students = $query->paginate($filters['per_page'] ?? 15);

        return $students->through(fn (Student $student) => $this->mapStudent($student));
    }

    /**
     * Retrieve the details of a single student.
     */
    public function getDetails(Student $student): array
    {
        $student->load([
            'studentClasses.class',
            'parents' => fn ($builder) => $builder->select('parents.id', 'first_name', 'last_name', 'phone'),
        ]);

        return $this->mapStudent($student);
    }

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

    /**
     * Remove a student safely.
     */
    public function delete(Student $student): void
    {
        DB::transaction(function () use ($student) {
            $student->delete();
        });
    }

    /**
     * Normalize student data for UI rendering.
     */
    private function mapStudent(Student $student): array
    {
        $primaryParent = $student->parents
            ->sortByDesc(fn ($parent) => (int) ($parent->pivot->is_primary ?? false))
            ->first();

        $currentClass = $student->studentClasses
            ->sortByDesc(fn ($studentClass) => $studentClass->assigned_at ?? $studentClass->start_date ?? $studentClass->created_at)
            ->first();

        $statusMeta = $this->statusBadge($student->status);

        return [
            'id' => $student->id,
            'admission_number' => $student->admission_number,
            'full_name' => $student->full_name,
            'class_name' => $currentClass?->class?->name ?? 'Non assignée',
            'status' => $student->status,
            'status_label' => $statusMeta['label'],
            'status_class' => $statusMeta['class'],
            'average' => '--',
            'date_of_birth' => $student->date_of_birth?->format('d/m/Y') ?? 'Non renseignée',
            'phone' => $student->phone ?? 'Non renseigné',
            'parent_name' => $primaryParent
                ? trim("{$primaryParent->first_name} {$primaryParent->last_name}")
                : 'Non renseigné',
            'parent_phone' => $primaryParent?->phone ?? 'Non renseigné',
            'address' => $student->address ?? 'Non renseignée',
        ];
    }

    /**
     * Provide consistent status badges.
     */
    private function statusBadge(?string $status): array
    {
        return match ($status) {
            'active' => ['label' => 'Actif', 'class' => 'bg-green-100 text-green-700'],
            'suspended' => ['label' => 'Suspendu', 'class' => 'bg-amber-100 text-amber-700'],
            'transferred' => ['label' => 'Transféré', 'class' => 'bg-blue-100 text-blue-700'],
            'graduated' => ['label' => 'Diplômé', 'class' => 'bg-purple-100 text-purple-700'],
            default => ['label' => 'Inactif', 'class' => 'bg-gray-100 text-gray-600'],
        };
    }
}
