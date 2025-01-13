<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\Company;
use App\Models\RefuelingOrder;
use App\Models\User;

class RefuelingOrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RefuelingOrder::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),            
            'company_id' => Company::factory(),
            'asset_id' => Asset::factory(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->date(),
            'fuel_type' => $this->faker->randomElement(["Petrol","Diesel"]),
            'fuel_qty' => $this->faker->numberBetween(0, 100),
            'state' => $this->faker->randomElement(["created","submitted","approved_by_officer","approved_by_manager"]),
        ];
    }
}
