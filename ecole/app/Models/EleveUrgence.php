<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EleveUrgence extends Model
{
    use HasFactory;

    protected $table = 'eleve_urgence';

    protected $fillable = [
        'eleve_id',
        'nom_complet',
        'lien',
        'telephone',
    ];
}
