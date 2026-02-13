<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    use HasFactory;

    protected $table = 'paiements';

    protected $fillable = [
        'inscription_id',
        'facture_id',
        'frais_inscription_id',
        'montant_paye',
        'date_paiement',
        'mode_paiement',
        'mode_paiement_libre',
        'reference',
        'motif_annulation',
        'annule_le',
        'annule_par',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'annule_le' => 'datetime',
        'montant_paye' => 'decimal:2',
    ];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }
}
