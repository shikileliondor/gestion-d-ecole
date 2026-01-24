<?php

namespace App\Services;

use App\Models\Eleve;
use Illuminate\Support\Facades\DB;

class EleveService
{
    public function create(array $data): Eleve
    {
        return DB::transaction(function () use ($data) {
            return Eleve::create($data);
        });
    }

    public function update(Eleve $eleve, array $data): Eleve
    {
        return DB::transaction(function () use ($eleve, $data) {
            $eleve->fill($data);
            $eleve->save();

            return $eleve;
        });
    }
}
