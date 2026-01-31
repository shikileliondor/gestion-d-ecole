<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periodes';

    protected $fillable = [
        'libelle',
        'type',
        'ordre',
        'actif',
        'annee_scolaire_id',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'actif' => 'boolean',
        'ordre' => 'integer',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];
}
