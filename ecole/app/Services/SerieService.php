<?php

namespace App\Services;

use App\Models\Serie;
use Illuminate\Support\Facades\DB;

class SerieService
{
    public function create(array $data): Serie
    {
        return DB::transaction(function () use ($data) {
            return Serie::create($data);
        });
    }

    public function update(Serie $serie, array $data): Serie
    {
        return DB::transaction(function () use ($serie, $data) {
            $serie->fill($data);
            $serie->save();

            return $serie;
        });
    }
}
