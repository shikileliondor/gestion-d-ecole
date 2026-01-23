<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function contracts()
    {
        return $this->hasMany(StaffContract::class);
    }

    public function assignments()
    {
        return $this->hasMany(StaffAssignment::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }
}
