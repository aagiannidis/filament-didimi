<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Building;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Sequence;


class BuildingSeeder extends Seeder
{
    public int $maxRecords = 10;
    public int $jobSize = 0;
    
    public function __construct($jobSize = 0) {
        $this->jobSize = $jobSize;    
    } 

    public function run(): void
    {

        $inpjobSize = 0;

        if ($this->jobSize===0) {
            $inpjobSize = (int)$this->command->ask('Please enter how many buildings to create (max='.$this->maxRecords.')...');

            if ($inpjobSize > $this->maxRecords) {
                $this->command->error('Sorry but you have specified more than then allowable number of records to create...');    
                return;
            }

            if ($inpjobSize < 1) {
                $this->command->error('Please specify a positive integer value for the number of records to be created...');    
                return;
            }
        }
        
        $this->jobSize = $inpjobSize;
        
        $this->command->info('Will now create '.$this->jobSize.' buildings...');

        $this->command->getOutput()->progressStart($this->jobSize);

        for ($i = 0; $i < $this->jobSize; $i++) {
        
            try {
                DB::beginTransaction();

                $newBuilding = Building::factory()
                    //->count(1)    # if you use count, it will return a collection
                    // ->withSpecialParams()                    
                    // ->suspended()    # set some properties
                    // ->state([        # of add dynamically externally here
                    //     'last_name' => 'Abigail Otwell',
                    // ])                    
                    ->create();

                // newLocations is now a collection
                $newLocations = Address::factory()->count(3)->create();       
                // get all the ids for the generated records
                $newLocation_ids = $newLocations->pluck('id')->all();
                // and define the extra pivot properties to be saved when attaching these addresses to the building.
                $sync_data = [];
                for($i = 0; $i < count($newLocation_ids); $i++) {
                    $sync_data[$newLocation_ids[$i]] = ['type' => 'work', 'is_correspondence'=>true];
                }

                $newBuilding->addresses()->sync($sync_data);

                /* or do it inversely as well
                $addressRecord = Address::factory()->create();
                $newBuilding = Building::factory()->create();
                $addressRecordId = 1;
                $addressRecord->buildings()->sync([$addressRecordId => ['type' => 'work', 'is_correspondence'=>false] ]);
                */

                
                 DB::commit();
                
            } catch (\Exception $e) {
                DB::rollback();
                $this->command->error("\n".'Seeder exception:'.$e->getMessage());                
                break;
            }      

            $this->command->getOutput()->progressAdvance();
        }
        $this->command->getOutput()->progressFinish();
        $this->command->info('Task completed successfully...');
    }
}
