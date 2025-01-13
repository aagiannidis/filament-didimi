<?php

namespace Database\Seeders;

use App\Models\VehicleRental;
use Illuminate\Database\Seeder;

class VehicleRentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleRental::factory()->count(5)->create();
    }
}
