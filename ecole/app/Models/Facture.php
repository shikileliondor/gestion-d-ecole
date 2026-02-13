<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Facture extends Model
{
    use HasFactory;

    protected $table = 'factures';

    protected $fillable = [
        'numero_facture',
        'inscription_id',
        'profil_facturable',
        'type_facture',
        'date_emission',
        'date_validation',
        'montant_total',
        'statut',
        'commentaire',
        'motif_annulation',
    ];

    protected $casts = [
        'date_emission' => 'date',
        'date_validation' => 'date',
        'montant_total' => 'decimal:2',
    ];

    public function inscription(): BelongsTo
    {
        return $this->belongsTo(Inscription::class);
    }

    public function lignes(): HasMany
    {
        return $this->hasMany(FactureLigne::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
