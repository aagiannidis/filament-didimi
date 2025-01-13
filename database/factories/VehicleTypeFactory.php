<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\VehicleType;

class VehicleTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'classification' => $this->faker->randomElement(["commercial","industrial",""]),
            'category' => $this->faker->regexify('[A-Za-z0-9]{20}'),
        ];
    }
}
