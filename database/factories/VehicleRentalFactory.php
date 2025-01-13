<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\VehicleRental;

class VehicleRentalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = VehicleRental::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'rental_date' => $this->faker->date(),
            'return_date' => $this->faker->date(),
            'rental_cost' => $this->faker->randomFloat(2, 0, 99999999.99),
            'rental_status' => $this->faker->randomElement(["rented","returned"]),
            'vehicle_id' => Asset::factory(),
            'asset_id' => Asset::factory(),
        ];
    }
}
