<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first();
        $fee = Fee::first();
        $receiver = User::where('email', 'admin@example.com')->first();

        Payment::firstOrCreate(
            ['student_id' => $student?->id, 'fee_id' => $fee?->id],
            [
                'amount_paid' => 50000,
                'balance_due' => 100000,
                'payment_date' => '2024-12-01',
                'method' => 'cash',
                'reference' => 'PAY-001',
                'status' => 'partial',
                'received_by' => $receiver?->id,
            ]
        );
    }
}
