<?php

namespace Database\Seeders;

use App\Models\Fee;
use App\Models\FeeClass;
use App\Models\SchoolClass;
use Illuminate\Database\Seeder;

class FeeClassSeeder extends Seeder
{
    public function run(): void
    {
        $fee = Fee::first();
        $class = SchoolClass::first();

        FeeClass::firstOrCreate(
            ['fee_id' => $fee?->id, 'class_id' => $class?->id],
            []
        );
    }
}
