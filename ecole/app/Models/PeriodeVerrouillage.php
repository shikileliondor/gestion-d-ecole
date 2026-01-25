<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeVerrouillage extends Model
{
    use HasFactory;

    protected $table = 'periode_verrouillages';

    protected $fillable = [
        'annee_scolaire_id',
        'classe_id',
        'periode_id',
        'verrouille',
    ];

    protected $casts = [
        'verrouille' => 'boolean',
    ];
}
