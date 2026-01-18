<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Mass assignable fields to prevent unsafe writes.
     */
    protected $fillable = [
        'school_id',
        'academic_year_id',
        'admission_number',
        'first_name',
        'last_name',
        'middle_name',
        'gender',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'religion',
        'blood_type',
        'address',
        'city',
        'country',
        'phone',
        'email',
        'photo_path',
        'enrollment_date',
        'previous_school',
        'needs_special_care',
        'medical_notes',
        'emergency_contact_name',
        'emergency_contact_phone',
        'status',
    ];

    /**
     * Attribute casting for date fields.
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'enrollment_date' => 'date',
        'needs_special_care' => 'boolean',
    ];

    /**
     * Student-to-class assignments.
     */
    public function studentClasses(): HasMany
    {
        return $this->hasMany(StudentClass::class);
    }

    /**
     * Parents/guardians linked to the student.
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(ParentProfile::class, 'student_parents', 'student_id', 'parent_id')
            ->withPivot(['is_primary', 'has_custody'])
            ->withTimestamps();
    }

    /**
     * Full name accessor for UI rendering.
     */
    public function getFullNameAttribute(): string
    {
        return trim(sprintf('%s %s', $this->last_name, $this->first_name));
    }
}
