<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FraisInscription extends Model
{
    use HasFactory;

    protected $table = 'frais_inscriptions';

    protected $fillable = [
        'inscription_id',
        'frais_id',
        'montant_du',
        'statut',
    ];

    protected $casts = [
        'montant_du' => 'decimal:2',
    ];

    public function frais(): BelongsTo
    {
        return $this->belongsTo(Frais::class);
    }
}
