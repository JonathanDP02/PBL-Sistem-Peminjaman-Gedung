<?php

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

class RoomTest extends TestCase
{
    use RefreshDatabase;
    public function test_room_cannot_be_created_without_unit_id()
    {
        Role::create(['name' => 'SuperAdmin']);
        Unit::factory()->create();

        $user = User::factory()->superAdmin()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/rooms', [
            'building_id' => 1,
            'room_name' => 'Test Room',
            'capacity' => 10
        ]);

        $response->assertStatus(422);

        $response->assertJsonValidationErrors(['unit_id']);
    }
}