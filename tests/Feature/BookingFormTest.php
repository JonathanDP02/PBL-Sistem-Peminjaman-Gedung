<?php

use App\Models\Building;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('displays booking form with buildings and workflows for authenticated user', function () {
    $unit = Unit::factory()->create();
    $user = User::factory()->create([
        'unit_id' => $unit->id,
        'position_id' => null,
    ]);

    $building = Building::factory()->create();
    $room = Room::factory()->create(['building_id' => $building->id, 'unit_id' => $unit->id]);
    $workflow = Workflow::factory()->create(['unit_id' => $unit->id, 'room_id' => null]);

    $response = $this->actingAs($user)->get(route('booking'));

    $response->assertStatus(200);
    $response->assertViewHas('buildings');
    $response->assertViewHas('workflows');

    // Check that data is present in the view
    $buildings = $response->viewData('buildings');
    $workflows = $response->viewData('workflows');

    expect($buildings)->not->toBeEmpty();
    expect($workflows)->not->toBeEmpty();
});

it('requires authentication to access booking form', function () {
    $response = $this->get(route('booking'));

    $response->assertRedirect(route('login'));
});
