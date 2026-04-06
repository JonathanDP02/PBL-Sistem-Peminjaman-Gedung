<?php

namespace Database\Factories;

use App\Models\Position;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

class PositionFactory extends Factory
{
    protected $model = Position::class;

    /**
     * Daftar jabatan yang realistis sesuai konteks kampus.
     * Digunakan saat generate acak (bukan dari seeder langsung).
     */
    public function definition(): array
    {
        $jabatan = [
            'Ketua Jurusan',
            'Sekretaris Jurusan',
            'Kaprodi',
            'Wakil Direktur',
            'Kepala Bagian Umum',
            'Ketua Organisasi',
            'Sekretaris Organisasi',
            'Bendahara',
        ];

        return [
            // unit_id harus sudah ada; default ambil random dari unit yang ada
            'unit_id' => Unit::inRandomOrder()->first()?->id ?? 1,
            'name'    => $this->faker->randomElement($jabatan),
        ];
    }
}