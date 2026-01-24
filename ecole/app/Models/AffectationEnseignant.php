<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectationEnseignant extends Model
{
    use HasFactory;

    protected $table = 'affectations_enseignants';

    protected $fillable = [
        'annee_scolaire_id',
        'enseignant_id',
        'classe_id',
        'matiere_id',
    ];
}
