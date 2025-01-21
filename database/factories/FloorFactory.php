<?php

namespace Database\Factories;

use App\Models\Floor;
use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

class FloorFactory extends Factory
{
    protected $model = Floor::class;

    public function definition()
    {
        static $floorNumber = 1;

        return [
            // 'id' => $this->faker->uuid(),
            'building_id' => Building::random()->id ?? 1,
            'number' => $floorNumber++,
            'name' => function (array $attributes) {
                $suffixes = ['st', 'nd', 'rd', 'th'];
                $suffix = $attributes['number'] <= 3 ? 
                    $suffixes[$attributes['number'] - 1] : 
                    $suffixes[3];
                return $attributes['number'] . $suffix . ' Floor';
            },
            'floor_plan_url' => $this->faker->optional(0.7)->imageUrl(1024, 1024, 'floor plan'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Configure the factory to create a ground floor.
     */
    public function groundFloor()
    {
        return $this->state(function (array $attributes) {
            return [
                'number' => 0,
                'name' => 'Ground Floor',
            ];
        });
    }

    /**
     * Configure the factory to create a basement floor.
     */
    public function basement()
    {
        return $this->state(function (array $attributes) {
            return [
                'number' => -1,
                'name' => 'Basement',
            ];
        });
    }

    /**
     * Reset the floor number counter
     */
    public function resetFloorNumber()
    {
        static $floorNumber = 1;
        return $this;
    }

     /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterCreating(function (Floor $floor) {
            $floor->rooms()->createMany(
                Room::factory()
                    ->count($this->faker->numberBetween(4, 10))
                    ->make()
                    // ->map(function ($room, $index) {
                    //     $room->number = $index + 1;
                    //     return $room;
                    // })
                    ->toArray()
            );        
        });
    }
}
