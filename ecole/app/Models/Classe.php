<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'annee_scolaire_id',
        'niveau_id',
        'serie_id',
        'nom',
        'effectif_max',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];
}
