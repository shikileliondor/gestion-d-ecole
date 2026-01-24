<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentFinancier extends Model
{
    use HasFactory;

    protected $table = 'documents_financiers';

    protected $fillable = [
        'type_document',
        'reference_id',
        'file_path',
        'original_name',
        'mime_type',
        'size_bytes',
    ];

    protected $casts = [
        'size_bytes' => 'integer',
    ];
}
