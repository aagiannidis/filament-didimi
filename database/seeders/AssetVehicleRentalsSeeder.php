<?php

namespace Database\Seeders;

use App\Models\AssetVehicleRentals;
use Illuminate\Database\Seeder;

class AssetVehicleRentalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssetVehicleRentals::factory()->count(5)->create();
    }
}
