<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatriculeSequence extends Model
{
    protected $fillable = [
        'entity_type',
        'year',
        'last_sequence',
    ];
}
