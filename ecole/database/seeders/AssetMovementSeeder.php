<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class AssetMovementSeeder extends Seeder
{
    public function run(): void
    {
        $asset = Asset::first();
        $staff = Staff::first();

        AssetMovement::firstOrCreate(
            ['asset_id' => $asset?->id, 'moved_at' => now()],
            ['moved_by' => $staff?->id, 'from_location' => 'Stock', 'to_location' => 'Salle info', 'condition' => 'bon']
        );
    }
}
