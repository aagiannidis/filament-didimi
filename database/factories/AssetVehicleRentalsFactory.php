<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\AssetVehicleRentals;
use App\Models\VehicleRental;

class AssetVehicleRentalsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssetVehicleRentals::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'vehicle_rental_id' => VehicleRental::factory(),
        ];
    }
}
