<?php

namespace Database\Seeders;

use App\Models\RefuelingOrder;
use Illuminate\Database\Seeder;

class RefuelingOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RefuelingOrder::factory()->count(5)->create();
    }
}
