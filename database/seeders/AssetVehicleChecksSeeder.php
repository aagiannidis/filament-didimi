<?php

namespace Database\Seeders;

use App\Models\AssetVehicleChecks;
use Illuminate\Database\Seeder;

class AssetVehicleChecksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssetVehicleChecks::factory()->count(5)->create();
    }
}
