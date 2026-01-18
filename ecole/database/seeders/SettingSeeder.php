<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();

        Setting::firstOrCreate(
            ['school_id' => $school?->id, 'group' => 'general', 'key' => 'theme'],
            ['value' => 'default', 'type' => 'string', 'is_public' => true]
        );
    }
}
