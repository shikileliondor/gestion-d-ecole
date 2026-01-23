<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EnseignantDocument extends Model
{
    use HasFactory;

    public const TYPES = ['CNI', 'DIPLOME', 'CONTRAT', 'CV', 'COURS', 'AUTRE'];

    protected $guarded = [];

    public function enseignant(): BelongsTo
    {
        return $this->belongsTo(Enseignant::class);
    }
}
