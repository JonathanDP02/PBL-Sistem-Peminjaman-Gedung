<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Building;
use App\Models\Unit;

class RoomFactory extends Factory
{
    public function definition(): array
    {
        $roomTypes = ['Ruang Teori', 'Laboratorium Komputer', 'Ruang Rapat', 'Studio'];
        $roomName = $this->faker->randomElement($roomTypes) . ' ' . $this->faker->bothify('?-##'); 

        return [
            // Disesuaikan dengan Migration: room_name
            'room_name' => $roomName,
            'capacity' => $this->faker->randomElement([20, 30, 40, 50, 100]),
            'building_id' => Building::inRandomOrder()->first()->id,
            'unit_id' => Unit::inRandomOrder()->first()->id,
            'description' => $this->faker->sentence(),
        ];
    }
}