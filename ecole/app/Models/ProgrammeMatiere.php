<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeMatiere extends Model
{
    use HasFactory;

    protected $table = 'programme_matieres';

    protected $fillable = [
        'annee_scolaire_id',
        'niveau_id',
        'serie_id',
        'matiere_id',
        'coefficient',
        'obligatoire',
        'actif',
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
        'obligatoire' => 'boolean',
        'actif' => 'boolean',
    ];
}
