<?php

namespace Database\Seeders;

use App\Models\VehicleModel;
use Illuminate\Database\Seeder;

class VehicleModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //VehicleModel::factory()->count(5)->create();
        $filePath = database_path('./data/VehicleManufacturersModelsData.json');
        $manufacturers = json_decode(file_get_contents($filePath), true);

        foreach ($manufacturers as $manufacturer) {
            
            $models = collect($manufacturer['best_selling_models']);
            foreach ($models as $model) {
                VehicleModel::factory()->create([
                    'model' => $model,
                    'vehicle_manufacturer_id' => \App\Models\VehicleManufacturer::where('name',$manufacturer['name'])->pluck('id')->first()
                ]);
            }
        }
    }
}
