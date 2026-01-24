<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recu extends Model
{
    use HasFactory;

    protected $table = 'recus';

    protected $fillable = [
        'numero_recu',
        'paiement_id',
        'date_emission',
        'montant',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'montant' => 'decimal:2',
    ];
}
