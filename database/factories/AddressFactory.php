<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Address;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    public static $streetNames = null;

    protected function loadDataset($jsonDataFile='database/data/GreekRoads.json'): array
    {
        $jsonDecodedData = getJsonData(base_path($jsonDataFile));
        return array_map(function($item) {
            return $item['street_name'];
        }, $jsonDecodedData);
    }

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        if (is_null(self::$streetNames)) {                    
            self::$streetNames = $this->loadDataset('database/data/GreekRoads.json');
        }
        
        return [
            'street_address' => self::$streetNames[rand(1,count(self::$streetNames)-1)],
            'street_number' => $this->faker->numberBetween(1,160),
            'unit_number' => $this->faker->numberBetween(1,10),
            'postal_code' => $this->faker->numberBetween(12500,16000),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'additional_info' => $this->faker->text(),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }

}
