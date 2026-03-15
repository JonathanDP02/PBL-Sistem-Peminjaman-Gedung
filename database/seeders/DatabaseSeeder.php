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

        // 3. Buat 10 User (Otomatis akan mendapat role_id dan unit_id secara acak)
        User::factory()->count(10)->create();
    }
}
