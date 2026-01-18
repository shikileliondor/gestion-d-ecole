<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['slug' => 'admin'], ['name' => 'Administrateur', 'description' => 'Acces complet', 'is_system' => true]);
        Role::firstOrCreate(['slug' => 'teacher'], ['name' => 'Enseignant', 'description' => 'Gestion des cours', 'is_system' => false]);
        Role::firstOrCreate(['slug' => 'parent'], ['name' => 'Parent', 'description' => 'Acces parent', 'is_system' => false]);
    }
}
