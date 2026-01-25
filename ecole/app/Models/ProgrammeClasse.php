<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeClasse extends Model
{
    use HasFactory;

    protected $table = 'programmes_classes';

    protected $fillable = [
        'annee_scolaire_id',
        'classe_id',
        'matiere_id',
        'actif',
    ];

    protected $casts = [
        'actif' => 'boolean',
    ];
}
