<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\AssetVehicleChecks;
use App\Models\VehicleCheck;

class AssetVehicleChecksFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AssetVehicleChecks::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'asset_id' => Asset::factory(),
            'vehicle_check_id' => VehicleCheck::factory(),
        ];
    }
}
