<?php

namespace App\Services;

use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\MatriculeSequence;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;
use RuntimeException;

class MatriculeService
{
    private const ENTITY_MAP = [
        'eleve' => Eleve::class,
        'enseignant' => Enseignant::class,
        'staff' => User::class,
        'patrimoine' => null,
    ];

    public function generateForStudent(?string $enrollmentDate = null): string
    {
        $year = $this->resolveYear($enrollmentDate);

        return $this->generateMatricule('eleve', $year);
    }

    public function generateForEnseignant(): string
    {
        return $this->generateMatricule('enseignant', $this->resolveYear());
    }

    public function generateForStaff(): string
    {
        return $this->generateMatricule('staff', $this->resolveYear());
    }

    public function generateForPatrimoine(): string
    {
        return $this->generateMatricule('patrimoine', $this->resolveYear());
    }

    private function generateMatricule(string $entityType, string $year): string
    {
        if (! array_key_exists($entityType, self::ENTITY_MAP)) {
            throw new RuntimeException('Type de matricule non pris en charge.');
        }

        return DB::transaction(function () use ($entityType, $year) {
            $sequence = MatriculeSequence::query()
                ->where('entity_type', $entityType)
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            if (! $sequence) {
                try {
                    $sequence = MatriculeSequence::create([
                        'entity_type' => $entityType,
                        'year' => $year,
                        'last_sequence' => 0,
                    ]);
                } catch (QueryException) {
                    $sequence = MatriculeSequence::query()
                        ->where('entity_type', $entityType)
                        ->where('year', $year)
                        ->lockForUpdate()
                        ->first();
                }
            }

            if (! $sequence) {
                throw new RuntimeException('Impossible de générer un matricule.');
            }

            do {
                $nextSequence = $sequence->last_sequence + 1;
                $sequence->update(['last_sequence' => $nextSequence]);
                $candidate = sprintf('%s-%05d', $year, $nextSequence);
            } while ($this->matriculeExists($entityType, $candidate));

            return $candidate;
        });
    }

    private function resolveYear(?string $date = null): string
    {
        $carbon = $date ? Carbon::parse($date) : Carbon::now();

        return $carbon->format('Y');
    }

    private function matriculeExists(string $entityType, string $candidate): bool
    {
        $modelClass = self::ENTITY_MAP[$entityType] ?? null;

        if (! $modelClass) {
            return false;
        }

        return $modelClass::query()->where('matricule', $candidate)->exists();
    }
}
