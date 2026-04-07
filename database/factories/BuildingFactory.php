<?php

namespace Database\Factories;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildingFactory extends Factory
{
    protected $model = Building::class;

    /**
     * Nama gedung kampus yang realistis.
     */
    public function definition(): array
    {
        // Pool nama gedung kampus politeknik/universitas
        $gedung = [
            'Gedung Administrasi Niaga',
            'Gedung Akuntansi',
            'Gedung Mesin',
            'Gedung Sipil',
            'Gedung Elektro',
            'Gedung Teknologi Informasi',
            'Graha Polinema',
        ];


        return [
            'building_name' => $this->faker->unique()->randomElement($gedung)
        ];
    }
}