<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use App\Models\Unit;
use App\Models\Position;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Password default untuk semua akun hasil seed.
     * Semua akun pakai: password123
     */
    protected static string $password = '12345';

    public function definition(): array
    {
        return [
            'unit_id'     => Unit::inRandomOrder()->first()?->id ?? 1,
            'position_id' => null, // default null; di-override saat seeding approver
            'role_id'     => Role::where('name', 'User')->first()?->id ?? Role::factory()->create(['name' => 'User'])->id,
            'name'        => $this->faker->name(),
            'email'       => $this->faker->unique()->safeEmail(),
            'password'    => Hash::make(self::$password),
        ];
    }

    // ── State Methods: memudahkan seeder memanggil ->superAdmin(), ->adminUnit(), dll. ──

    /** State: SuperAdmin Pusat */
    public function superAdmin(): static
    {
        return $this->state(fn(array $attr) => [
            'role_id'     => Role::where('name', 'SuperAdmin')->first()?->id ?? 1,
            'position_id' => null,
        ]);
    }

    /** State: Admin Unit (Jurusan) */
    public function adminUnit(int $unitId): static
    {
        return $this->state(fn(array $attr) => [
            'role_id' => Role::where('name', 'Admin_Unit')->first()?->id ?? 2,
            'unit_id' => $unitId,
        ]);
    }

    /** State: Approver dengan posisi jabatan tertentu */
    public function approver(int $unitId, int $positionId): static
    {
        return $this->state(fn(array $attr) => [
            'role_id'     => Role::where('name', 'Approver')->first()?->id ?? 4,
            'unit_id'     => $unitId,
            'position_id' => $positionId,
        ]);
    }

    /** State: User biasa (peminjam) */
    public function userBiasa(int $unitId): static
    {
        return $this->state(fn(array $attr) => [
            'role_id'     => Role::where('name', 'User')->first()?->id ?? 3,
            'unit_id'     => $unitId,
            'position_id' => null,
        ]);
    }
}