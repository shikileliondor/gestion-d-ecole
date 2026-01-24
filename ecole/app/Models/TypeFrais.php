<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeFrais extends Model
{
    use HasFactory;

    protected $table = 'types_frais';

    protected $fillable = [
        'libelle',
        'obligatoire',
        'actif',
    ];

    protected $casts = [
        'obligatoire' => 'boolean',
        'actif' => 'boolean',
    ];
}
