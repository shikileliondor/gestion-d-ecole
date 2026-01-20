<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolClass extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $guarded = [];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function studentAssignments(): HasMany
    {
        return $this->hasMany(StudentClass::class, 'class_id');
    }

    public function subjectAssignments(): HasMany
    {
        return $this->hasMany(ClassSubject::class, 'class_id');
    }
}
