<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalConnexion extends Model
{
    use HasFactory;

    protected $table = 'journal_connexions';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'email_tente',
        'date_connexion',
        'statut',
        'origine',
        'session_id',
        'ip_adresse',
        'user_agent',
    ];

    protected $casts = [
        'date_connexion' => 'datetime',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
