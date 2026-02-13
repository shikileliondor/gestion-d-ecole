<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalAction extends Model
{
    use HasFactory;

    protected $table = 'journal_actions';

    public const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'action',
        'table_cible',
        'enregistrement_id',
        'anciennes_valeurs',
        'nouvelles_valeurs',
        'ip_adresse',
        'user_agent',
    ];

    protected $casts = [
        'anciennes_valeurs' => 'array',
        'nouvelles_valeurs' => 'array',
        'created_at' => 'datetime',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}
