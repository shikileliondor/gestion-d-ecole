<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Remise extends Model
{
    use HasFactory;

    protected $table = 'remises';

    protected $fillable = [
        'inscription_id',
        'facture_id',
        'periode_id',
        'type_remise',
        'type_calcul',
        'valeur',
        'montant_applique',
        'montant',
        'motif',
        'description',
        'accordee_par',
        'validee_par',
        'validee_le',
        'motif_annulation',
        'annule_le',
        'annule_par',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'valeur' => 'decimal:2',
        'montant_applique' => 'decimal:2',
        'validee_le' => 'datetime',
        'annule_le' => 'datetime',
    ];

    public function facture(): BelongsTo
    {
        return $this->belongsTo(Facture::class);
    }

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public function periode(): BelongsTo
    {
        return $this->belongsTo(Periode::class);
    }
}
