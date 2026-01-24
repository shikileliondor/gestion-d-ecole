<?php

namespace App\Services;

use App\Models\Niveau;
use Illuminate\Support\Facades\DB;

class NiveauService
{
    public function create(array $data): Niveau
    {
        return DB::transaction(function () use ($data) {
            return Niveau::create($data);
        });
    }

    public function update(Niveau $niveau, array $data): Niveau
    {
        return DB::transaction(function () use ($niveau, $data) {
            $niveau->fill($data);
            $niveau->save();

            return $niveau;
        });
    }
}
