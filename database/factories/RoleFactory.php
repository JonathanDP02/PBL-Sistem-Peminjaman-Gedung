<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'role_id' => Str::uuid(),
            'name' => $this->faker->unique()->randomElement(['SuperAdmin', 'Admin_Unit', 'User', 'Approver']),
        ];
    }
}
