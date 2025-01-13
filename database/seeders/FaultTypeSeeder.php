<?php

namespace Database\Seeders;

use App\Models\FaultType;
use Illuminate\Database\Seeder;

class FaultTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = database_path('./data/FaultTypes.json');
        $collection = json_decode(file_get_contents($filePath), true);

        foreach ($collection as $item) {
            FaultType::factory()->create([
                'category' => $item['FaultType'],
                'abbreviation' => $item['FaultAbbrev'],                
            ]);
        }
    }
}
