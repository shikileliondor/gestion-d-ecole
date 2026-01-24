<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnseignantUrgence extends Model
{
    use HasFactory;

    protected $table = 'enseignant_urgence';

    protected $fillable = [
        'enseignant_id',
        'nom_complet',
        'lien',
        'telephone',
    ];
}
