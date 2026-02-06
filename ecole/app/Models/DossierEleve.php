<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DossierEleve extends Model
{
    use HasFactory;

    protected $table = 'dossiers_eleves';

    protected $fillable = [
        'eleve_id',
        'statut',
        'date_ouverture',
        'date_derniere_reouverture',
    ];

    protected $casts = [
        'date_ouverture' => 'date',
        'date_derniere_reouverture' => 'date',
    ];

    public function eleve(): BelongsTo
    {
        return $this->belongsTo(Eleve::class);
    }

    public function inscriptions(): HasMany
    {
        return $this->hasMany(Inscription::class, 'eleve_id', 'eleve_id');
    }
}
