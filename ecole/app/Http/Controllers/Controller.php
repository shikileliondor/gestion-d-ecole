<?php

namespace App\Http\Controllers;

use App\Models\AnneeScolaire;

abstract class Controller
{
    protected function activeAcademicYear(): ?AnneeScolaire
    {
        return AnneeScolaire::query()
            ->where('statut', 'ACTIVE')
            ->orderByDesc('date_debut')
            ->first();
    }

    protected function resolveAcademicYearId(?int $requestedId = null): ?int
    {
        if ($requestedId) {
            return $requestedId;
        }

        $active = $this->activeAcademicYear();

        if ($active) {
            return $active->id;
        }

        return AnneeScolaire::query()
            ->orderByDesc('date_debut')
            ->value('id');
    }
}
