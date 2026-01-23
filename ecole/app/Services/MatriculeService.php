<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\MatriculeSequence;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use RuntimeException;

class MatriculeService
{
    private const PREFIXES = [
        'student' => 'ELV',
        'enseignant' => 'ENS',
        'staff' => 'PER',
    ];

    public function generateForStudent(int $schoolId): string
    {
        return $this->generateMatricule('student', $schoolId);
    }

    public function generateForEnseignant(int $schoolId): string
    {
        return $this->generateMatricule('enseignant', $schoolId);
    }

    public function generateForStaff(int $schoolId): string
    {
        return $this->generateMatricule('staff', $schoolId);
    }

    private function generateMatricule(string $entityType, int $schoolId): string
    {
        $prefix = self::PREFIXES[$entityType] ?? null;
        if (! $prefix) {
            throw new RuntimeException('Type de matricule non pris en charge.');
        }

        $academicYearCode = $this->getActiveAcademicYearCode($schoolId);

        return DB::transaction(function () use ($entityType, $academicYearCode, $prefix) {
            $sequence = MatriculeSequence::query()
                ->where('entity_type', $entityType)
                ->where('academic_year_code', $academicYearCode)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                try {
                    $sequence = MatriculeSequence::create([
                        'entity_type' => $entityType,
                        'academic_year_code' => $academicYearCode,
                        'last_sequence' => 0,
                    ]);
                } catch (QueryException) {
                    $sequence = MatriculeSequence::query()
                        ->where('entity_type', $entityType)
                        ->where('academic_year_code', $academicYearCode)
                        ->lockForUpdate()
                        ->first();
                }
            }

            if (! $sequence) {
                throw new RuntimeException('Impossible de générer un matricule.');
            }

            $nextSequence = $sequence->last_sequence + 1;
            $sequence->update(['last_sequence' => $nextSequence]);

            return sprintf('%s-%s-%06d', $prefix, $academicYearCode, $nextSequence);
        });
    }

    private function getActiveAcademicYearCode(int $schoolId): string
    {
        $academicYear = AcademicYear::query()
            ->where('school_id', $schoolId)
            ->where(function ($query) {
                $query->where('is_current', true)
                    ->orWhere('status', 'active');
            })
            ->orderByDesc('is_current')
            ->orderByDesc('status')
            ->first();

        if (! $academicYear) {
            throw new RuntimeException("Aucune année scolaire active n'est définie.");
        }

        return $academicYear->name;
    }
}
