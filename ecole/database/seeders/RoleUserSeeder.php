<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->first();
        $adminUser = User::where('email', 'admin@example.com')->first();

        RoleUser::firstOrCreate(
            ['role_id' => $adminRole?->id, 'user_id' => $adminUser?->id],
            ['assigned_at' => now()]
        );
    }
}
