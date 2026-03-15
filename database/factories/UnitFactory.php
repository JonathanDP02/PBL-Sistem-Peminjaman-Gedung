<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'unit_id' => Str::uuid(),
            'parent_id' => null, // Biarkan null dulu untuk master
            'level' => $this->faker->randomElement(['Pusat', 'Jurusan', 'Organisasi']),
            'unit_name' => $this->faker->company(),
        ];
    }
}
