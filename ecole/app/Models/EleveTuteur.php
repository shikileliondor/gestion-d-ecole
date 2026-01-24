<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EleveTuteur extends Model
{
    use HasFactory;

    protected $table = 'eleve_tuteurs';

    protected $fillable = [
        'eleve_id',
        'lien',
        'nom',
        'prenoms',
        'telephone_1',
        'telephone_2',
        'email',
        'profession',
        'adresse',
    ];
}
