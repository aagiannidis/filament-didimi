<?php

namespace Database\Factories;

use App\Models\Equipment;
use App\Models\EquipmentAssignmentLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentAssignmentLogFactory extends Factory
{
    protected $model = EquipmentAssignmentLog::class;

    public function definition(): array
    {
        $assignedDate = $this->faker->dateTimeBetween('-2 years', 'now');
        $returnedDate = $this->faker->optional(0.7)->dateTimeBetween($assignedDate, 'now');

        return [
            'equipment_id' => Equipment::factory(),
            'assigned_to' => User::factory(),
            'assigned_by' => User::factory(),
            'assigned_date' => $assignedDate,
            'returned_date' => $returnedDate,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes) => [
            'returned_date' => null,
        ]);
    }
}
