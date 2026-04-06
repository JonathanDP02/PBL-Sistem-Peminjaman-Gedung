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
            // gunakan unit acak jika sudah ada, jika belum buat unit baru agar FK tetap valid
            'unit_id' => Unit::query()->inRandomOrder()->value('id') ?? Unit::factory(),
            'name'    => $this->faker->randomElement($jabatan),
        ];
    }
}