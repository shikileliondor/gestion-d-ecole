<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EleveContact extends Model
{
    use HasFactory;

    protected $table = 'eleve_contacts';

    protected $fillable = [
        'eleve_id',
        'telephone',
        'email',
        'adresse',
        'commune',
        'ville',
    ];
}
