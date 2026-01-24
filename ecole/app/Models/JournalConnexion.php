<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalConnexion extends Model
{
    use HasFactory;

    protected $table = 'journal_connexions';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'date_connexion',
        'ip_adresse',
        'user_agent',
    ];

    protected $casts = [
        'date_connexion' => 'datetime',
    ];
}
