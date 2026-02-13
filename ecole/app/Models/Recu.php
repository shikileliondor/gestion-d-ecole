<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recu extends Model
{
    use HasFactory;

    protected $table = 'recus';

    protected $fillable = [
        'numero_recu',
        'paiement_id',
        'date_emission',
        'montant',
        'motif_annulation',
        'annule_le',
        'annule_par',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'annule_le' => 'datetime',
        'montant' => 'decimal:2',
    ];

    public function paiement(): BelongsTo
    {
        return $this->belongsTo(Paiement::class);
    }
}
