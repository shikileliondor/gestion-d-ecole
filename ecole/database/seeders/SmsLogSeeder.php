<?php

namespace Database\Seeders;

use App\Models\SmsLog;
use Illuminate\Database\Seeder;

class SmsLogSeeder extends Seeder
{
    public function run(): void
    {
        SmsLog::firstOrCreate(
            ['recipient_phone' => '+2250102030406', 'message' => 'Rappel paiement'],
            ['status' => 'sent', 'provider' => 'MockSMS', 'provider_reference' => 'SMS-001', 'sent_at' => now()]
        );
    }
}
