<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Eleve extends Model
{
    use HasFactory;

    protected $table = 'eleves';

    protected $fillable = [
        'matricule',
        'matricule_national',
        'nom',
        'prenoms',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'etablissement_origine',
        'date_arrivee',
        'classe_precedente',
        'photo_path',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_arrivee' => 'date',
    ];
}
