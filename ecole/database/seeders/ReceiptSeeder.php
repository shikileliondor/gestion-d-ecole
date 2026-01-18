<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Receipt;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReceiptSeeder extends Seeder
{
    public function run(): void
    {
        $payment = Payment::first();
        $user = User::where('email', 'admin@example.com')->first();

        Receipt::firstOrCreate(
            ['payment_id' => $payment?->id, 'receipt_number' => 'RCT-001'],
            ['issued_at' => now(), 'issued_by' => $user?->id, 'file_path' => 'receipts/RCT-001.pdf']
        );
    }
}
