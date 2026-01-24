<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $table = 'evaluations';

    protected $fillable = [
        'annee_scolaire_id',
        'classe_id',
        'matiere_id',
        'type',
        'titre',
        'date_evaluation',
        'note_sur',
    ];

    protected $casts = [
        'date_evaluation' => 'date',
        'note_sur' => 'decimal:2',
    ];
}
