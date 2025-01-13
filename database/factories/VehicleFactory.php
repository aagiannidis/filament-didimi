<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Support\Str;
use App\Traits\GeneratesPlateNumbers;
use Illuminate\Database\Eloquent\Factories\Factory;

class VehicleFactory extends Factory
{
    use GeneratesPlateNumbers;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'license_plate' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'vehicle_identification_no' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'engine_serial_no' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'chassis_serial_no' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'vehicle_manufacturer_id' => $this->faker->word(),
            'model' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'manufacture_date' => $this->faker->date(),
            'color' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'vehicle_type' => $this->faker->randomElement(['Car', 'Motorcycle', 'Truck', 'Bus', 'Van', 'SUV', 'Other']),
            'fuel_type' => $this->faker->randomElement(["Petrol", "Diesel", "Electric", "Hybrid", "Other"]),
            'emission_standard' => $this->faker->randomElement(["Other", "Euro 1","Euro 2","Euro 3","Euro 4","Euro 5","Euro 6"]),
            'weight' => $this->faker->numberBetween(1000, 10000),
            'seats' => $this->faker->numberBetween(1, 5),
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function (Vehicle $vehicle) {
            // Create 3 related addresses
            $vehicle->license_plate = $this->generateRandomPlate();
            $vehicle->vehicle_manufacturer_id = \App\Models\VehicleManufacturer::select('id')->inRandomOrder()->pluck('id')->first();
            $vehicle->model = \App\Models\VehicleManufacturer::find($vehicle->vehicle_manufacturer_id)->models()->inRandomOrder()->pluck('model')->first();
            // $company->addresses()->createMany(
            //     \App\Models\Address::factory(3)->make()->toArray()
            // );
        });
    }
}
