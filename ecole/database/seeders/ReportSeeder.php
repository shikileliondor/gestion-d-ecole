<?php

namespace Database\Seeders;

use App\Models\Report;
use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $user = User::where('email', 'admin@example.com')->first();

        Report::firstOrCreate(
            ['school_id' => $school?->id, 'type' => 'financial', 'title' => 'Rapport des frais'],
            ['description' => 'Synthese des paiements', 'period_start' => '2024-09-01', 'period_end' => '2024-12-31', 'generated_by' => $user?->id, 'status' => 'ready']
        );
    }
}
