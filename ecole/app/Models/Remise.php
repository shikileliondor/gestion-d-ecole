<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Remise extends Model
{
    use HasFactory;

    protected $table = 'remises';

    protected $fillable = [
        'inscription_id',
        'type_remise',
        'montant',
        'motif',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
    ];
}
