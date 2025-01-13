<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\VehicleFaultTemplate;

class VehicleFaultTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleFaultTemplate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text(),
            'description_gr' => $this->faker->text(),
            'precautions' => $this->faker->text(),
            'precautions_gr' => $this->faker->text(),
            'priority' => $this->faker->randomElement(["low","medium","high"]),
        ];
    }
}
