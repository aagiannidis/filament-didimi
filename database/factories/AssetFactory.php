<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Asset;
use App\Models\Vehicle;
use Faker;

class AssetFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asset::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $localisedfaker = Faker\Factory::create('el_GR');

        return [
            'asset_reference' => $this->faker->regexify('[A-Za-z0-9]{50}'),
            'license_plate' => "ΥΑΟ".$localisedfaker->regexify('[0-9]{4}'),
            'date_of_purchase' => $this->faker->date(),
            'cost_of_purchase' => $this->faker->randomFloat(2, 0, 99999999.99),
            'condition' => $this->faker->randomElement(["new","used","damaged"]),
            'vehicle_id' => Vehicle::select('id')->inRandomOrder()->first(),
        ];
    }
}

/*

    ucfirst(faker->text(20))
    faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now', $timezone = null)	Generate a DateTime object for a date between two given dates.

    foreach($hotels as $hotel) {
        $hotel->rooms()->saveMany(
            Room::factory()->count(5)->make()
        );
    }

    return [
        // this will create a new room type and assign it to the room
        'room_type_id' => factory(RoomType::class)->create()
    ]

*/
