<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\WallPort;
use Illuminate\Database\Eloquent\Factories\Factory;

class WallPortFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WallPort::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['DATA', 'VOICE']);
        $location = $this->faker->randomElement(['NORTH', 'SOUTH', 'EAST', 'WEST']);

        return [
            'room_id' => Room::random()->id ?? 1,
            'port_number' => function (array $attributes) {
                $room = Room::find($attributes['room_id']);
                $portCount = WallPort::where('room_id', $room->id)->count();
                return sprintf('%s-W-%02d', $room->number, $portCount + 1);
            },
            'type' => $type,
            'location' => $location,
            'status' => $this->faker->randomElement(['ACTIVE', 'INACTIVE', 'FAULTY']),
            'speed' => $type === 'DATA' ? $this->faker->randomElement(['100Mbps', '1000Mbps', '10Gbps']) : null,
            'extension' => $type === 'VOICE' ? $this->faker->numerify('####') : null,
            'last_tested_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the wall port is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ACTIVE',
        ]);
    }

    /**
     * Indicate that the wall port is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'INACTIVE',
        ]);
    }

    /**
     * Indicate that the wall port is faulty.
     */
    public function faulty(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'FAULTY',
        ]);
    }

    /**
     * Configure the wall port as a data port.
     */
    public function dataPort(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'DATA',
            'speed' => $this->faker->randomElement(['100Mbps', '1000Mbps', '10Gbps']),
            'extension' => null,
        ]);
    }

    /**
     * Configure the wall port as a voice port.
     */
    public function voicePort(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'VOICE',
            'speed' => null,
            'extension' => $this->faker->numerify('####'),
        ]);
    }
}
