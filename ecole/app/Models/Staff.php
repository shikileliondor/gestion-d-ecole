<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function getStaffNumberAttribute(): ?string
    {
        return $this->code_personnel ?? null;
    }

    public function setStaffNumberAttribute(?string $value): void
    {
        $this->attributes['code_personnel'] = $value;
    }

    public function getFirstNameAttribute(): ?string
    {
        return $this->prenoms ?? null;
    }

    public function setFirstNameAttribute(?string $value): void
    {
        $this->attributes['prenoms'] = $value;
    }

    public function getLastNameAttribute(): ?string
    {
        return $this->nom ?? null;
    }

    public function setLastNameAttribute(?string $value): void
    {
        $this->attributes['nom'] = $value;
    }

    public function getPhoneAttribute(): ?string
    {
        return $this->telephone_1 ?? null;
    }

    public function setPhoneAttribute(?string $value): void
    {
        $this->attributes['telephone_1'] = $value;
    }

    public function getPositionAttribute(): ?string
    {
        return $this->poste ?? null;
    }

    public function setPositionAttribute(?string $value): void
    {
        $this->attributes['poste'] = $value;
    }

    public function getHireDateAttribute(): ?string
    {
        return $this->date_debut_service ?? null;
    }

    public function setHireDateAttribute(?string $value): void
    {
        $this->attributes['date_debut_service'] = $value;
    }

    public function getStatusAttribute(): ?string
    {
        return match ($this->statut) {
            'ACTIF' => 'active',
            'SUSPENDU' => 'suspended',
            'PARTI' => 'departed',
            default => null,
        };
    }

    public function setStatusAttribute(?string $value): void
    {
        if ($value === null) {
            return;
        }

        $map = [
            'active' => 'ACTIF',
            'inactive' => 'SUSPENDU',
            'suspended' => 'SUSPENDU',
            'departed' => 'PARTI',
            'terminated' => 'PARTI',
        ];

        $this->attributes['statut'] = $map[$value] ?? $value;
    }

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
