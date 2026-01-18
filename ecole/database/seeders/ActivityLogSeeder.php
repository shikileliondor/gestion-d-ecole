<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class ActivityLogSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $user = User::where('email', 'admin@example.com')->first();

        ActivityLog::firstOrCreate(
            ['action' => 'seeded', 'subject_type' => 'school', 'subject_id' => $school?->id],
            ['user_id' => $user?->id, 'description' => 'Donnees de demarrage inserees', 'ip_address' => '127.0.0.1', 'user_agent' => 'Seeder']
        );
    }
}
