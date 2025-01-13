<?php

namespace Database\Seeders;

use App\Models\VehicleCheck;
use Illuminate\Database\Seeder;

class VehicleCheckSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VehicleCheck::factory()->count(5)->create();
    }
}
