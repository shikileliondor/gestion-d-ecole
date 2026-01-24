<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facture extends Model
{
    use HasFactory;

    protected $table = 'factures';

    protected $fillable = [
        'numero_facture',
        'inscription_id',
        'date_emission',
        'montant_total',
        'statut',
        'commentaire',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'montant_total' => 'decimal:2',
    ];
}
