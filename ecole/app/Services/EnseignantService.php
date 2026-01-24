<?php

namespace App\Services;

use App\Models\Enseignant;
use Illuminate\Support\Facades\DB;

class EnseignantService
{
    public function create(array $data): Enseignant
    {
        return DB::transaction(function () use ($data) {
            return Enseignant::create($data);
        });
    }

    public function update(Enseignant $enseignant, array $data): Enseignant
    {
        return DB::transaction(function () use ($enseignant, $data) {
            $enseignant->fill($data);
            $enseignant->save();

            return $enseignant;
        });
    }
}
