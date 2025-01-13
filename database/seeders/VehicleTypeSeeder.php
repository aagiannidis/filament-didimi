<?php

namespace Database\Seeders;

use App\Models\VehicleType;
use Illuminate\Database\Seeder;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('./data/VehicleTypes.json');
        $collection = json_decode(file_get_contents($filePath), true);

        foreach ($collection as $item) {
            VehicleType::factory()->create([
                'classification' => $item['VehicleType'],
                'category' => $item['VehicleSubType'],                
            ]);
        }
    }
}
