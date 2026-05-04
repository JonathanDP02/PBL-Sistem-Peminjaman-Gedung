<?php

use App\Models\Building;
use App\Models\Position;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
    $this->adminUnit = User::where('email', 'admin.ti@spacein.test')->first();
    $this->jurusanTI = Unit::where('unit_name', 'Jurusan Teknologi Informasi')->first();
});

// --- WEB ROUTES TESTING ---

it('allows admin_unit to access dashboard via web', function () {
    $response = $this->actingAs($this->adminUnit)->get('/dashboard');
    if ($response->status() !== 200) {
        $response->dump();
    }
    $response->assertStatus(200);
});

it('allows admin_unit to access workflows builder via web', function () {
    $response = $this->actingAs($this->adminUnit)->get('/admin_unit/workflows-builder');
    if ($response->status() !== 200) {
        $response->dump();
    }
    $response->assertStatus(200);
});

it('allows admin_unit to access workflows index via web', function () {
    $response = $this->actingAs($this->adminUnit)->get('/admin_unit/workflows-index');
    if ($response->status() !== 200) {
        $response->dump();
    }
    $response->assertStatus(200);
});

it('allows admin_unit to access manajemen ruangan via web', function () {
    $response = $this->actingAs($this->adminUnit)->get('/admin_unit/manajemen-ruangan');
    if ($response->status() !== 200) {
        $response->dump();
    }
    $response->assertStatus(200);
});

it('allows admin_unit to access pemblokiran ruangan via web', function () {
    $response = $this->actingAs($this->adminUnit)->get('/admin_unit/pemblokiran-ruangan');
    if ($response->status() !== 200) {
        $response->dump();
    }
    $response->assertStatus(200);
});

// --- API ROUTES TESTING ---

it('allows admin_unit to get workflows via api', function () {
    $response = $this->actingAs($this->adminUnit)->getJson('/admin_unit/api/workflows');
    $response->assertStatus(200);
    $response->assertJsonIsArray();
});

it('allows admin_unit to create a workflow via api', function () {
    $response = $this->actingAs($this->adminUnit)->postJson('/admin_unit/api/workflows', [
        'name' => 'Workflow Baru TI',
        'description' => 'Deskripsi workflow baru',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('workflows', [
        'name' => 'Workflow Baru TI',
        'unit_id' => $this->jurusanTI->id,
    ]);
});

it('allows admin_unit to sync workflow details (steps & requirements) via api', function () {
    // Create a fresh workflow without approvals/bookings
    $workflow = Workflow::create([
        'unit_id' => $this->jurusanTI->id,
        'name' => 'Workflow Sync Test',
        'description' => 'Test',
    ]);
    $position = Position::where('unit_id', $this->jurusanTI->id)->first();

    $response = $this->actingAs($this->adminUnit)->postJson('/admin_unit/api/workflows/'.$workflow->id.'/sync-details', [
        'steps' => [
            [
                'position_id' => $position->id,
                'requires_attachment' => false,
            ],
        ],
        'requirements' => [
            [
                'document_name' => 'Surat Pengantar Sync',
                'is_mandatory' => true,
            ],
        ],
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('workflow_steps', [
        'workflow_id' => $workflow->id,
        'position_id' => $position->id,
        'step_order' => 1,
    ]);
    $this->assertDatabaseHas('workflow_requirements', [
        'workflow_id' => $workflow->id,
        'document_name' => 'Surat Pengantar Sync',
        'is_mandatory' => true,
    ]);
});

it('allows admin_unit to get rooms via api', function () {
    $response = $this->actingAs($this->adminUnit)->getJson('/admin_unit/api/rooms');
    $response->assertStatus(200);
});

it('allows admin_unit to create a room via web form', function () {
    $building = Building::first();
    $response = $this->actingAs($this->adminUnit)->post('/admin_unit/rooms', [
        'building_id' => $building->id,
        'room_name' => 'Ruang Test Unit',
        'capacity' => 50,
        'description' => 'Deskripsi Ruang Test',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('rooms', [
        'room_name' => 'Ruang Test Unit',
        'unit_id' => $this->jurusanTI->id,
    ]);
});

it('allows admin_unit to block a room (maintenance) via web form', function () {
    $room = Room::where('unit_id', $this->jurusanTI->id)->first();

    $response = $this->actingAs($this->adminUnit)->post('/admin_unit/pemblokiran-ruangan', [
        'room_id' => $room->id,
        'mulai_dari' => now()->addDay()->format('Y-m-d H:i'),
        'hingga' => now()->addDay()->addHours(2)->format('Y-m-d H:i'),
        'alasan' => 'Maintenance Server',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('bookings', [
        'room_id' => $room->id,
        'event_name' => '[MAINTENANCE HARD-LOCK]',
        'status' => 'Approved',
    ]);
});

it('allows admin_unit to update its room via web form', function () {
    $room = Room::where('unit_id', $this->jurusanTI->id)->first();

    $response = $this->actingAs($this->adminUnit)->put('/admin_unit/rooms/'.$room->id, [
        'building_id' => $room->building_id,
        'room_name' => 'Room Updated Name',
        'capacity' => 100,
        'description' => 'Updated Description',
    ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('rooms', [
        'id' => $room->id,
        'room_name' => 'Room Updated Name',
    ]);
});

it('allows admin_unit to delete its room via api', function () {
    $room = Room::create([
        'building_id' => Building::first()->id,
        'unit_id' => $this->jurusanTI->id,
        'room_name' => 'Room to Delete',
        'capacity' => 10,
    ]);

    $response = $this->actingAs($this->adminUnit)->deleteJson('/admin_unit/api/rooms/'.$room->id);

    $response->assertStatus(200);
    $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
});

it('prevents admin_unit from deleting other units room', function () {
    $otherUnit = Unit::create(['unit_name' => 'Other Unit', 'level' => 'Jurusan']);
    $otherRoom = Room::create([
        'building_id' => Building::first()->id,
        'unit_id' => $otherUnit->id,
        'room_name' => 'Other Unit Room',
        'capacity' => 10,
    ]);

    $response = $this->actingAs($this->adminUnit)->deleteJson('/admin_unit/api/rooms/'.$otherRoom->id);

    $response->assertStatus(403);
    $this->assertDatabaseHas('rooms', ['id' => $otherRoom->id]);
});

it('allows admin_unit to update workflow via api', function () {
    $workflow = Workflow::create([
        'unit_id' => $this->jurusanTI->id,
        'name' => 'Workflow Update Test',
    ]);

    $response = $this->actingAs($this->adminUnit)->putJson('/admin_unit/api/workflows/'.$workflow->id, [
        'name' => 'Updated Workflow Name',
        'description' => 'Updated Desc',
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('workflows', [
        'id' => $workflow->id,
        'name' => 'Updated Workflow Name',
    ]);
});

it('allows admin_unit to delete workflow via api', function () {
    $workflow = Workflow::create([
        'unit_id' => $this->jurusanTI->id,
        'name' => 'Workflow Delete Test',
    ]);

    $response = $this->actingAs($this->adminUnit)->deleteJson('/admin_unit/api/workflows/'.$workflow->id);

    $response->assertStatus(200);
    $this->assertDatabaseMissing('workflows', ['id' => $workflow->id]);
});
