<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition()
    {
        $name = $this->faker->randomElement([
            'Human Resources',
            'Information Technology',
            'Finance',
            'Operations',
            'Marketing',
            'Sales',
            'Research & Development',
            'Legal',
            'Customer Service',
            'Administration'
        ]);

        $code = strtoupper(substr(str_replace(' & ', '', $name), 0, 2));

        return [
            'name' => $name,
            'code' => $code,
            'description' => $this->faker->paragraph(),
            'manager_id' => User::factory(),
            'budget' => $this->faker->numberBetween(50000, 500000),
            'location' => $this->faker->randomElement(['Floor 1', 'Floor 2', 'Floor 3', 'Remote']),
            'email' => strtolower($code) . '@company.com',
            'phone' => $this->faker->phoneNumber(),
            'is_active' => $this->faker->boolean(90), // 90% chance of being active
        ];
    }

    /**
     * Indicate that the department is inactive.
     */
    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Indicate that the department has no manager.
     */
    public function withoutManager()
    {
        return $this->state(function (array $attributes) {
            return [
                'manager_id' => null,
            ];
        });
    }
}
