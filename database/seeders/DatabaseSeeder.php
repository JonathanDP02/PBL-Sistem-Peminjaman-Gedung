<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Unit;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat 3 Role Master
        Role::factory()->count(4)->create();

        // 2. Buat 5 Unit Master
        Unit::factory()->count(5)->create();

        // Ambil Role dan Unit yang baru saja dibuat untuk dimasukkan ke User
        $role = Role::first();
        $unit = Unit::first();

        // 3. Buat 1 User Khusus untuk Testing Login secara Manual (tanpa Factory)
        User::create([
            'user_id' => \Illuminate\Support\Str::uuid(),
            'role_id' => $role->role_id,
            'unit_id' => $unit->unit_id,
            'name' => 'SuperAdmin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345'),
        ]);
    }
}
