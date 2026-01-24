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
        'nom',
        'prenoms',
        'sexe',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'photo_path',
    ];

    protected $casts = [
        'date_naissance' => 'date',
    ];
}
