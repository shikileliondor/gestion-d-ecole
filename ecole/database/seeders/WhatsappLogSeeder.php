<?php

namespace Database\Seeders;

use App\Models\WhatsappLog;
use Illuminate\Database\Seeder;

class WhatsappLogSeeder extends Seeder
{
    public function run(): void
    {
        WhatsappLog::firstOrCreate(
            ['recipient_phone' => '+2250102030406', 'message' => 'Information WhatsApp'],
            ['status' => 'sent', 'provider' => 'MockWA', 'provider_reference' => 'WA-001', 'sent_at' => now()]
        );
    }
}
