<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParametreEcole extends Model
{
    use HasFactory;

    protected $table = 'parametres_ecole';

    protected $fillable = [
        'logo_path',
        'signature_path',
        'cachet_path',
        'facture_prefix',
        'recu_prefix',
        'matricule_prefix',
        'remises_actives',
        'plafond_remise',
        'validation_remise',
        'politique_impayes',
    ];

    protected $casts = [
        'remises_actives' => 'boolean',
        'plafond_remise' => 'integer',
    ];
}
