<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'inscription_id',
        'frais_inscription_id',
        'montant_paye',
        'date_paiement',
        'mode_paiement',
        'reference',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'montant_paye' => 'decimal:2',
    ];
}
