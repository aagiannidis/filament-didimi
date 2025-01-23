<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Floor;
use App\Models\WallPort;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition()
    {
        $types = ['OFFICE', 'MEETING_ROOM', 'COMMON_AREA', 'UTILITY'];
        $type = $this->faker->randomElement($types);

        // Capacity varies based on room type
        $capacity = match($type) {
            'OFFICE' => $this->faker->numberBetween(1, 4),
            'MEETING_ROOM' => $this->faker->numberBetween(6, 20),
            'COMMON_AREA' => $this->faker->numberBetween(10, 50),
            'UTILITY' => 0,
            default => 0,
        };

        // Area (in square meters) varies based on room type and capacity
        $area = match($type) {
            'OFFICE' => $capacity * 5 + $this->faker->numberBetween(5, 10),
            'MEETING_ROOM' => $capacity * 2.5 + $this->faker->numberBetween(10, 20),
            'COMMON_AREA' => $capacity * 2 + $this->faker->numberBetween(20, 40),
            'UTILITY' => $this->faker->numberBetween(5, 15),
            default => 10,
        };

        return [
            //'id' => $this->faker->uuid(),
            'floor_id' => Floor::inRandomOrder()->first()->id ?? 1,
            'number' => function (array $attributes) {
                $floor = Floor::find($attributes['floor_id']);
                return $floor->number . str_pad($this->faker->unique()->numberBetween(1, 99), 2, '0', STR_PAD_LEFT);
            },
            'name' => function (array $attributes) use ($type) {
                return match($type) {
                    'OFFICE' => 'Office ' . $attributes['number'],
                    'MEETING_ROOM' => 'Meeting Room ' . $attributes['number'],
                    'COMMON_AREA' => 'Common Area ' . $attributes['number'],
                    'UTILITY' => 'Utility Room ' . $attributes['number'],
                    default => 'Room ' . $attributes['number'],
                };
            },
            'type' => $type,
            'capacity' => $capacity,
            'area_sqm' => $area,
            'status' => $this->faker->randomElement(['ACTIVE', 'MAINTENANCE', 'INACTIVE']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the factory to create an office room.
     */
    public function office()
    {
        return $this->state(function (array $attributes) {
            $capacity = $this->faker->numberBetween(1, 4);
            return [
                'type' => 'OFFICE',
                'capacity' => $capacity,
                'area_sqm' => $capacity * 5 + $this->faker->numberBetween(5, 10),
            ];
        });
    }

    /**
     * Configure the factory to create a meeting room.
     */
    public function meetingRoom()
    {
        return $this->state(function (array $attributes) {
            $capacity = $this->faker->numberBetween(6, 20);
            return [
                'type' => 'MEETING_ROOM',
                'capacity' => $capacity,
                'area_sqm' => $capacity * 2.5 + $this->faker->numberBetween(10, 20),
            ];
        });
    }

    /**
     * Configure the factory to create a utility room.
     */
    public function utility()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'UTILITY',
                'capacity' => 0,
                'area_sqm' => $this->faker->numberBetween(5, 15),
            ];
        });
    }

    /**
     * Configure the factory to create a common area.
     */
    public function commonArea()
    {
        return $this->state(function (array $attributes) {
            $capacity = $this->faker->numberBetween(10, 50);
            return [
                'type' => 'COMMON_AREA',
                'capacity' => $capacity,
                'area_sqm' => $capacity * 2 + $this->faker->numberBetween(20, 40),
            ];
        });
    }

     /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Room $room) {
            // This ensures any floors created are properly associated

                $room->wallPorts()->createMany(
                    WallPort::factory()
                        ->count($this->faker->numberBetween(4, 10))
                        ->make()
                        ->toArray()
                );

        });
    }
}
