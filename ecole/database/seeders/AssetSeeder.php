<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\School;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $school = School::first();
        $category = AssetCategory::first();
        $staff = Staff::first();

        Asset::firstOrCreate(
            ['code' => 'AST-001'],
            [
                'school_id' => $school?->id,
                'asset_category_id' => $category?->id,
                'name' => 'Laptop Lenovo',
                'serial_number' => 'SN-12345',
                'purchase_date' => '2023-08-15',
                'purchase_cost' => 350000,
                'current_value' => 300000,
                'location' => 'Salle info',
                'condition' => 'bon',
                'status' => 'active',
                'assigned_to' => $staff?->id,
            ]
        );
    }
}
