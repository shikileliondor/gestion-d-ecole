<?php

namespace App\Services;

use App\Models\Classe;
use Illuminate\Support\Facades\DB;

class ClasseService
{
    public function create(array $data): Classe
    {
        return DB::transaction(function () use ($data) {
            return Classe::create($data);
        });
    }

    public function update(Classe $classe, array $data): Classe
    {
        return DB::transaction(function () use ($classe, $data) {
            $classe->fill($data);
            $classe->save();

            return $classe;
        });
    }
}
