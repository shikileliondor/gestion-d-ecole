<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentHistorySeeder extends Seeder
{
    public function run(): void
    {
        $payment = Payment::first();
        $user = User::where('email', 'admin@example.com')->first();

        PaymentHistory::firstOrCreate(
            ['payment_id' => $payment?->id, 'status' => 'partial'],
            ['amount' => 50000, 'recorded_at' => now(), 'recorded_by' => $user?->id]
        );
    }
}
