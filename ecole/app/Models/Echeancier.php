<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Echeancier extends Model
{
    use HasFactory;

    protected $table = 'echeanciers';

    protected $fillable = [
        'frais_inscription_id',
        'montant_prevu',
        'date_echeance',
        'statut',
    ];

    protected $casts = [
        'montant_prevu' => 'decimal:2',
        'date_echeance' => 'date',
    ];
}
