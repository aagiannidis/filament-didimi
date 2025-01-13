<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\VehicleCheck;

class VehicleCheckFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleCheck::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'check_date' => $this->faker->date(),
            'check_type' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'check_result' => $this->faker->randomElement(["pass","fail"]),
            'vehicle_id' => Asset::factory(),
            'asset_id' => Asset::factory(),
        ];
    }
}
