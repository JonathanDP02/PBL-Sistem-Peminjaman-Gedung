<?php

use App\Models\Building;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('room cannot be created without unit_id', function () {
    // Create a valid unit first since it's mandatory for users and rooms
    $unit = Unit::factory()->create();

    $user = User::factory()->create([
        'unit_id' => $unit->id,
    ]);

    $this->actingAs($user);

    // Attempt to create a room WITHOUT unit_id in the request
    $response = $this->postJson('/admin/api/rooms', [
        'building_id' => Building::factory()->create()->id,
        'room_name' => 'Test Room',
        'capacity' => 10,
        // 'unit_id' is missing here
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['unit_id']);
});
