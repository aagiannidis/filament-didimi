<?php

namespace Database\Seeders;


use App\Models\FaultType;
use App\Models\VehicleType;
use Illuminate\Database\Seeder;
use App\Models\VehicleFaultTemplate;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VehicleFaultTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('./data/HeavyMachineryVehicleFaults.json');
        $collection = json_decode(file_get_contents($filePath), true);
        
        foreach ($collection as $item) {
            $vehicleFaultTemplate = VehicleFaultTemplate::factory()->create([                
                'title' => $item['Fault'],
                'description' => $item['Description'],
                'description_gr' => $item['Description_gr'],
                'precautions' => $item['Precaution'],
                'precautions_gr' => $item['Precaution_gr'],
                'priority' => strtolower($item['Priority']),
            ]);


            // Find the VehicleType ID using the VehicleSubType
            $vehicleType = VehicleType::where('category', $item['VehicleSubType'])->first();

            if ($vehicleType) {
                // Attach the VehicleType ID to the vehicleTypes relationship
                $vehicleFaultTemplate->vehicleTypes()->attach($vehicleType->id);
            }

            $faultType = FaultType::where('category', $item['FaultCategory'])->first();

            if ($faultType) {
                // Attach the VehicleType ID to the vehicleTypes relationship
                $vehicleFaultTemplate->faultTypes()->attach($faultType->id);
            }            
        }

        
    }
}
