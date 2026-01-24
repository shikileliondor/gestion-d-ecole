<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frais extends Model
{
    use HasFactory;

    protected $table = 'frais';

    protected $fillable = [
        'annee_scolaire_id',
        'niveau_id',
        'type_frais_id',
        'periodicite',
        'montant',
        'actif',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'actif' => 'boolean',
    ];
}
