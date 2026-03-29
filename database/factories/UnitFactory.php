<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'parent_id' => null, // Biarkan null dulu untuk master
            'level' => $this->faker->randomElement(['Pusat', 'Jurusan', 'Organisasi']),
            'unit_name' => $this->faker->company(),
        ];
    }
}
