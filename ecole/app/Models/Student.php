<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory, SoftDeletes;

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
}
