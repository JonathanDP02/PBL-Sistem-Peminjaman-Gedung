<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Building;
use App\Models\Role;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    Storage::fake('private');

    // Setup roles
    $rolePeminjam = Role::create(['name' => 'Peminjam']);
    $rolePenyetuju = Role::create(['name' => 'Penyetuju']);

    // Setup unit
    $this->unit = Unit::create(['unit_name' => 'Teknologi Informasi', 'level' => 'Jurusan']);

    // Setup users
    $this->user = User::factory()->create([
        'role_id' => $rolePeminjam->id,
        'unit_id' => $this->unit->id,
    ]);

    // Setup building & room
    $this->building = Building::factory()->create();
    $this->room = Room::factory()->create([
        'building_id' => $this->building->id,
        'unit_id' => $this->unit->id,
    ]);

    // Setup workflow
    $this->workflow = Workflow::factory()->create([
        'unit_id' => $this->unit->id,
        'room_id' => null, // General Workflow
    ]);

    // Mandatory requirements
    $this->req1 = WorkflowRequirement::create([
        'workflow_id' => $this->workflow->id,
        'document_name' => 'Proposal Kegiatan',
        'is_mandatory' => true,
    ]);
});

test('it successfully creates a single-day booking', function () {
    $date = now()->addDays(10)->format('Y-m-d');

    $response = $this->actingAs($this->user)->post(route('booking.store'), [
        'room_id' => $this->room->id,
        'event_name' => 'Single Day Event',
        'event_description' => 'Test event description',
        'event_scope' => 'Internal',
        'booking_date' => $date,
        'start_time' => '08:00',
        'end_time' => '12:00',
        'requirement_'.$this->req1->id => UploadedFile::fake()->create('proposal.pdf', 100),
    ]);

    $response->assertStatus(201);

    $booking = Booking::where('event_name', 'Single Day Event')->firstOrFail();
    expect($booking->room_id)->toBe($this->room->id);
    expect($booking->booking_date->format('Y-m-d'))->toBe($date);
    expect($booking->booking_end_date->format('Y-m-d'))->toBe($date);
    expect(date('H:i', strtotime($booking->start_time)))->toBe('08:00');
    expect(date('H:i', strtotime($booking->end_time)))->toBe('12:00');
    expect($booking->status)->toBe('Pending');
});

test('it successfully creates a multi-day booking', function () {
    $startDate = now()->addDays(10)->format('Y-m-d');
    $endDate = now()->addDays(12)->format('Y-m-d');

    $response = $this->actingAs($this->user)->post(route('booking.store'), [
        'room_id' => $this->room->id,
        'event_name' => 'Multi Day Event',
        'event_description' => 'Test event description',
        'event_scope' => 'Internal',
        'booking_date' => $startDate,
        'booking_end_date' => $endDate,
        'start_time' => '08:00',
        'end_time' => '17:00',
        'requirement_'.$this->req1->id => UploadedFile::fake()->create('proposal.pdf', 100),
    ]);

    $response->assertStatus(201);

    $booking = Booking::where('event_name', 'Multi Day Event')->firstOrFail();
    expect($booking->room_id)->toBe($this->room->id);
    expect($booking->booking_date->format('Y-m-d'))->toBe($startDate);
    expect($booking->booking_end_date->format('Y-m-d'))->toBe($endDate);
    expect(date('H:i', strtotime($booking->start_time)))->toBe('08:00');
    expect(date('H:i', strtotime($booking->end_time)))->toBe('17:00');
    expect($booking->status)->toBe('Pending');
});

test('it detects conflicts when two multi-day bookings overlap in both date and time', function () {
    $startDate = now()->addDays(10)->format('Y-m-d');
    $endDate = now()->addDays(12)->format('Y-m-d');

    // Create the first booking
    Booking::create([
        'user_id' => $this->user->id,
        'room_id' => $this->room->id,
        'workflow_id' => $this->workflow->id,
        'event_name' => 'First Multi-Day Event',
        'booking_date' => $startDate,
        'booking_end_date' => $endDate,
        'start_time' => '08:00',
        'end_time' => '17:00',
        'status' => 'Pending',
    ]);

    // Try to create overlapping booking (overlaps on June 11th and 12th, and time overlaps)
    $overlapStartDate = now()->addDays(11)->format('Y-m-d');
    $overlapEndDate = now()->addDays(13)->format('Y-m-d');

    $response = $this->actingAs($this->user)->post(route('booking.store'), [
        'room_id' => $this->room->id,
        'event_name' => 'Overlapping Event',
        'event_scope' => 'Internal',
        'booking_date' => $overlapStartDate,
        'booking_end_date' => $overlapEndDate,
        'start_time' => '10:00',
        'end_time' => '12:00',
        'requirement_'.$this->req1->id => UploadedFile::fake()->create('proposal.pdf', 100),
    ]);

    $response->assertStatus(409);
    $response->assertJsonStructure(['error']);
});

test('it allows non-overlapping times on overlapping dates', function () {
    $startDate = now()->addDays(10)->format('Y-m-d');
    $endDate = now()->addDays(12)->format('Y-m-d');

    Booking::create([
        'user_id' => $this->user->id,
        'room_id' => $this->room->id,
        'workflow_id' => $this->workflow->id,
        'event_name' => 'Morning Event',
        'booking_date' => $startDate,
        'booking_end_date' => $endDate,
        'start_time' => '08:00',
        'end_time' => '12:00',
        'status' => 'Pending',
    ]);

    // Evening event on the same dates should be allowed
    $response = $this->actingAs($this->user)->post(route('booking.store'), [
        'room_id' => $this->room->id,
        'event_name' => 'Evening Event',
        'event_scope' => 'Internal',
        'booking_date' => $startDate,
        'booking_end_date' => $endDate,
        'start_time' => '13:00',
        'end_time' => '17:00',
        'requirement_'.$this->req1->id => UploadedFile::fake()->create('proposal.pdf', 100),
    ]);

    $response->assertStatus(201);
});

test('it bypasses approval and hard-locks if event_name is maintenance', function () {
    $startDate = now()->addDays(10)->format('Y-m-d');
    $endDate = now()->addDays(12)->format('Y-m-d');

    $response = $this->actingAs($this->user)->post(route('booking.store'), [
        'room_id' => $this->room->id,
        'event_name' => 'maintenance',
        'event_scope' => 'Internal',
        'booking_date' => $startDate,
        'booking_end_date' => $endDate,
        'start_time' => '08:00',
        'end_time' => '17:00',
        'requirement_'.$this->req1->id => UploadedFile::fake()->create('proposal.pdf', 100),
    ]);

    $response->assertStatus(201);

    $booking = Booking::where('event_name', 'maintenance')->firstOrFail();
    expect($booking->room_id)->toBe($this->room->id);
    expect($booking->booking_date->format('Y-m-d'))->toBe($startDate);
    expect($booking->booking_end_date->format('Y-m-d'))->toBe($endDate);
    expect($booking->status)->toBe('Approved');
    expect($booking->current_step)->toBe(0);
});
