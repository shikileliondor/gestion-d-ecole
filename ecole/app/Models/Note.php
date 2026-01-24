<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    protected $table = 'notes';

    protected $fillable = [
        'evaluation_id',
        'inscription_id',
        'valeur',
    ];

    protected $casts = [
        'valeur' => 'decimal:2',
    ];
}
