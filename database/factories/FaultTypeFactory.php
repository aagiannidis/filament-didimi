<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\FaultType;

class FaultTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FaultType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'category' => $this->faker->randomElement(["engine","electical","kinetics","mechanics","tooling","hydraulics","bodywork","other"]),
            'abbreviation' => $this->faker->randomElement(["ENG","ELE","KIN","MEC","TOO","HYD","BOD","OTH"]),
        ];
    }
}
