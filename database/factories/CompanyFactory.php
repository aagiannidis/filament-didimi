<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'alias' => $this->faker->word(),
            'vat_number' => $this->faker->numerify('################'),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->word(),
            'type' => $this->faker->randomElement(["partner","supplier","manufacturer","service_provider"]),
            'industry' => $this->faker->randomElement(["general_supplies","fuel_and_energy","parts","servicing"]),
            'is_active' => $this->faker->boolean(),
            'notes' => $this->faker->text(),
            'tags' => '{}',
        ];
    }
}
