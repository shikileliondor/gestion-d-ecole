<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';

    protected $fillable = [
        'evaluation_id',
        'inscription_id',
        'periode_id',
        'valeur',
        'statut',
        'date_saisie',
    ];

    protected $casts = [
        'valeur' => 'decimal:2',
        'date_saisie' => 'date',
    ];
}
