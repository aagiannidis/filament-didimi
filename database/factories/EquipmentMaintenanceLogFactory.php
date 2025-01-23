<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\EquipmentMaintenanceLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentMaintenanceLogFactory extends Factory
{
    protected $model = EquipmentMaintenanceLog::class;

    public function definition(): array
    {
        $maintenanceTypes = [
            'Routine Check',
            'Repair',
            'Upgrade',
            'Cleaning',
            'Software Update',
            'Hardware Replacement',
        ];

        return [
            'equipment_id' => Equipment::factory(),
            'maintenance_type' => $this->faker->randomElement($maintenanceTypes),
            'description' => $this->faker->paragraph(),
            'performed_by' => User::factory(),
            'maintenance_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'cost' => $this->faker->optional()->randomFloat(2, 50, 1000),
            'status' => $this->faker->randomElement(['Completed', 'In Progress', 'Scheduled']),
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}
