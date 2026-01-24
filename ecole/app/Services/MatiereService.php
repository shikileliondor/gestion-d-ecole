<?php

namespace App\Services;

use App\Models\Matiere;
use Illuminate\Support\Facades\DB;

class MatiereService
{
    public function create(array $data): Matiere
    {
        return DB::transaction(function () use ($data) {
            return Matiere::create($data);
        });
    }

    public function update(Matiere $matiere, array $data): Matiere
    {
        return DB::transaction(function () use ($matiere, $data) {
            $matiere->fill($data);
            $matiere->save();

            return $matiere;
        });
    }
}
