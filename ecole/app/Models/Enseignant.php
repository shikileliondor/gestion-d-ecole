<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Enseignant extends Model
{
    use HasFactory;

    public const SEXES = ['M', 'F', 'Autre'];
    public const TYPES = ['PERMANENT', 'VACATAIRE', 'STAGIAIRE'];
    public const STATUTS = ['ACTIF', 'SUSPENDU', 'PARTI'];
    public const NIVEAUX = ['COLLEGE', 'LYCEE', 'COLLEGE_LYCEE'];
    public const MODES_PAIEMENT = ['MOBILE_MONEY', 'VIREMENT', 'CASH'];
    public const CONTACT_LIENS = ['PERE', 'MERE', 'CONJOINT', 'FRERE_SOEUR', 'TUTEUR', 'AUTRE'];

    protected $guarded = [];

    protected $casts = [
        'date_naissance' => 'date',
        'date_debut_service' => 'date',
        'date_fin_service' => 'date',
        'date_expiration_cni' => 'date',
        'salaire_base' => 'decimal:2',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(EnseignantDocument::class);
    }
}
