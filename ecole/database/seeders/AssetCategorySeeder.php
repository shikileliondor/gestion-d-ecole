<?php

namespace Database\Seeders;

use App\Models\AssetCategory;
use Illuminate\Database\Seeder;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        AssetCategory::firstOrCreate(
            ['code' => 'IT'],
            ['name' => 'Informatique', 'description' => 'Materiel informatique', 'depreciation_rate' => 10]
        );
    }
}
