<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    public function definition(): array
    {
        $buildings = [
            'Gedung Sipil', 'Gedung Mesin', 'Gedung Elektro', 
            'Gedung Akuntansi', 'Gedung Administrasi Bisnis', 
            'Gedung Teknologi Informasi', 'Auditorium', 'Graha Polinema'
        ];

        return [
            // Disesuaikan dengan Migration: building_name
            'building_name' => $this->faker->randomElement($buildings),
            'location' => $this->faker->address(),
        ];
    }
}