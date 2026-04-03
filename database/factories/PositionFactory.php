<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Unit; // Wajib dipanggil untuk relasi

class PositionFactory extends Factory
{
    public function definition(): array
    {
        $positions = [
            'Ketua Jurusan', 
            'Sekretaris Jurusan', 
            'Kepala Program Studi', 
            'Ketua BEM', 
            'Ketua Himpunan',
            'Kepala Laboratorium',
            'Staff Administrasi'
        ];

        return [
            'name' => $this->faker->randomElement($positions),
            // Ambil ID unit secara acak yang sudah ada di database
            'unit_id' => Unit::inRandomOrder()->first()->id, 
        ];
    }
}