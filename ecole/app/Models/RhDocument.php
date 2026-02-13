<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RhDocument extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Urgences',
        'Contrats',
        'Pièces administratives',
        'Notes internes',
        'Paie',
        'Autres',
    ];

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_urgent' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
