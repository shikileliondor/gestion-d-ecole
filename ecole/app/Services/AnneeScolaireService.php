<?php

namespace App\Services;

use App\Models\AnneeScolaire;
use Illuminate\Support\Facades\DB;

class AnneeScolaireService
{
    public function create(array $data): AnneeScolaire
    {
        return DB::transaction(function () use ($data) {
            return AnneeScolaire::create($data);
        });
    }

    public function update(AnneeScolaire $anneeScolaire, array $data): AnneeScolaire
    {
        return DB::transaction(function () use ($anneeScolaire, $data) {
            $anneeScolaire->fill($data);
            $anneeScolaire->save();

            return $anneeScolaire;
        });
    }
}
