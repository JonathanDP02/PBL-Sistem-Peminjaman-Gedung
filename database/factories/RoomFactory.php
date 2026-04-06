<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Building;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    /**
     * Generate ruangan dengan relasi building_id dan unit_id yang valid.
     */
    public function definition(): array
    {
        $tipeRuang = [
            'Lab Komputer',
            'Ruang Rapat',
            'Aula',
            'Auditorium',
            'Ruang MultiMedia',
        ];

        $nomorRuang = $this->faker->numberBetween(101, 310);

        return [
            // FK ke buildings — pastikan building sudah di-seed lebih dulu
            'building_id' => Building::inRandomOrder()->first()?->id ?? 1,
            // FK ke units — ruangan dimiliki oleh unit tertentu
            'unit_id'     => Unit::inRandomOrder()->first()?->id ?? 1,
            'room_name'   => $this->faker->randomElement($tipeRuang) . ' ' . $nomorRuang,
            'capacity'    => $this->faker->randomElement([30, 40, 50, 60, 100, 200]),
            'description' => $this->faker->optional()->sentence(),
        ];
    }
}