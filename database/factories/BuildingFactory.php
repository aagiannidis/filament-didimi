<?php

namespace Database\Factories;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    protected $model = Building::class;

    public function definition()
    {
        return [
            //'id' => $this->faker->uuid(),
            'name' => $this->faker->company(),
            'description' => '',
            'code' => function (array $attributes) {
                return strtoupper(substr($attributes['name'] ?? $this->faker->company(), 0, 1));
            },

            // 'address' => $this->faker->streetAddress(),
            // 'city' => $this->faker->city(),
            // 'country' => $this->faker->country(),
            'total_floors' => $this->faker->numberBetween(1, 6),
            'total_capacity' => function (array $attributes) {
                return $attributes['total_floors'] * $this->faker->numberBetween(20, 40);
            },
            'current_occupancy' => function (array $attributes) {
                return (int) ($attributes['total_capacity'] * $this->faker->numberBetween(60, 90) / 100);
            },
            'status' => $this->faker->randomElement(['ACTIVE', 'MAINTENANCE', 'INACTIVE']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Building $building) {
            // This ensures any floors created are properly associated
            if ($building->floors()->count() === 0) {
                $building->floors()->createMany(
                    Floor::factory()
                        ->count($building->total_floors)
                        ->make()
                        ->map(function ($floor, $index) {
                            $floor->number = $index + 1;
                            return $floor;
                        })
                        ->toArray()
                );
            }
        });
    }
}
