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
        // 1. Buat 4 Role Master
        Role::factory()->count(4)->create();

        // 2. Buat 5 Unit Master
        Unit::factory()->count(5)->create();

        // Ambil role dan unit yang akan dipakai user manual
        $superAdminRole = Role::where('name', 'SuperAdmin')->first() ?? Role::first();
        $userRole = Role::where('name', 'User')->first() ?? Role::first();
        $unit = Unit::first();

        // 3. Buat 1 User SuperAdmin untuk testing login manual
        User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'role_id' => $superAdminRole->id,
            'unit_id' => $unit->id,
            'name' => 'Bapak SuperAdmin',
            'password' => bcrypt('12345'),
        ]);

        // 4. Contoh user biasa (manual)
        User::updateOrCreate([
            'email' => 'user@gmail.com',
        ], [    
            'role_id' => $userRole->id,
            'unit_id' => $unit->id,
            'name' => 'Mahasiswa User',
            'password' => bcrypt('12345'),
        ]);
    }
}
