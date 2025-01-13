<?php

namespace Database\Seeders;

use App\Models\VehicleManufacturer;
use Illuminate\Database\Seeder;

class VehicleManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $filePath = database_path('./data/VehicleManufacturersData.json');
        $manufacturers = json_decode(file_get_contents($filePath), true);

        foreach ($manufacturers as $manufacturer) {
            VehicleManufacturer::factory()->create([
                'name' => $manufacturer['name'],
                'country' => $manufacturer['country'],
                'type' => $manufacturer['type']
            ]);
        }
    }
}
