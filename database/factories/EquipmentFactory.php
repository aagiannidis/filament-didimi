<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Equipment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition(): array
    {
        $types = ['Laptop', 'Desktop', 'Monitor', 'Printer', 'Phone', 'Tablet', 'Server', 'Network Switch'];
        $manufacturers = ['Dell', 'HP', 'Lenovo', 'Apple', 'Samsung', 'Cisco', 'Brother'];
        $assignedDate = $this->faker->optional()->dateTimeBetween('-2 years', 'now');

        return [
            'type' => $this->faker->randomElement($types),
            'model' => $this->faker->bothify('##??-####'),
            'serial_number' => $this->faker->unique()->bothify('SN-########'),
            'assigned_to' => $assignedDate ? User::factory() : null,
            'assigned_date' => $assignedDate,
            'status' => $this->faker->randomElement(['ACTIVE', 'MAINTENANCE', 'RETIRED']),
            'manufacturer' => $this->faker->randomElement($manufacturers),
            'purchase_date' => $this->faker->dateTimeBetween('-3 years', '-6 months'),
            'warranty_expiry' => $this->faker->dateTimeBetween('now', '+3 years'),
            'purchase_cost' => $this->faker->randomFloat(2, 500, 5000),
            'notes' => $this->faker->optional()->paragraph(),
            'specifications' => $this->generateSpecifications(),
            'department_id' => Department::factory()->withoutManager(),
            'location_id' => Room::factory(),
        ];
    }

    protected function generateSpecifications(): array
    {
        return [
            'processor' => $this->faker->randomElement(['Intel i5', 'Intel i7', 'Intel i9', 'AMD Ryzen 5', 'AMD Ryzen 7']),
            'ram' => $this->faker->randomElement(['8GB', '16GB', '32GB', '64GB']),
            'storage' => $this->faker->randomElement(['256GB SSD', '512GB SSD', '1TB SSD', '2TB HDD']),
            'os' => $this->faker->randomElement(['Windows 11 Pro', 'Windows 10 Pro', 'macOS', 'Linux']),
            'screen_size' => $this->faker->optional()->randomElement(['13"', '14"', '15.6"', '17"']),
        ];
    }

    public function active(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ACTIVE',
        ]);
    }

    public function maintenance(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'MAINTENANCE',
        ]);
    }

    public function retired(): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'RETIRED',
        ]);
    }
}
