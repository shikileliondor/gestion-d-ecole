<?php

namespace App\Support;

use App\Models\User;

class AccountingRole
{
    public const ADMIN = 'ADMIN';
    public const COMPTABLE = 'COMPTABLE';
    public const CAISSIER = 'CAISSIER';
    public const LECTURE = 'LECTURE';

    public static function canManageInvoices(?User $user): bool
    {
        return in_array($user?->role, [self::ADMIN, self::COMPTABLE], true);
    }

    public static function canRegisterPayments(?User $user): bool
    {
        return in_array($user?->role, [self::ADMIN, self::COMPTABLE, self::CAISSIER], true);
    }

    public static function canCancel(?User $user): bool
    {
        return in_array($user?->role, [self::ADMIN, self::COMPTABLE], true);
    }

    public static function canReadReports(?User $user): bool
    {
        return in_array($user?->role, [self::ADMIN, self::COMPTABLE, self::CAISSIER, self::LECTURE], true);
    }
}
