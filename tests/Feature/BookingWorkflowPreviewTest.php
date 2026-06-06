<?php

use App\Models\Booking;
use App\Models\Position;
use App\Models\Role;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowStep;
use App\Services\WorkflowBridgeService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(DatabaseSeeder::class);
    $this->bridgeService = new WorkflowBridgeService;
});

it('resolves correct workflow steps for Andi (HMTI) booking TI Room', function () {
    $user = User::where('email', 'user@spacein.test')->firstOrFail();
    $room = Room::where('room_name', 'Ruang Kelas TI')->firstOrFail();
    $workflow = Workflow::where('name', 'Peminjaman JTI')->firstOrFail();

    $booking = Booking::create([
        'user_id' => $user->id,
        'room_id' => $room->id,
        'workflow_id' => $workflow->id,
        'event_name' => 'Seminar HMTI',
        'booking_date' => now()->addDays(5)->format('Y-m-d'),
        'start_time' => '08:00',
        'end_time' => '10:00',
        'status' => 'Pending',
        'current_step' => 1,
        'event_scope' => 'Internal',
    ]);

    $steps = $this->bridgeService->resolveStepChain($booking, 'Internal');
    expect($steps)->not->toBeEmpty();

    $labels = collect($steps)->map(fn ($s) => $s['tier_label'])->toArray();

    // Andi is HMTI (Organisasi). Expected flow:
    // 1. Internal (HMTI) -> Ketua HMTI
    // 2. BEM (BEM Polinema) -> Presiden BEM Polinema
    // 3. DPK (Jurusan Teknologi Informasi) -> DPK TI
    // 4. Pemilik Ruangan (Jurusan Teknologi Informasi) -> Ketua Jurusan TI
    expect($labels[0])->toContain('Internal (HMTI)');
    expect($labels[1])->toContain('BEM (BEM Polinema)');
    expect($labels[2])->toContain('DPK (Jurusan Teknologi Informasi)');
    expect($labels[3])->toContain('Pemilik Ruangan (Jurusan Teknologi Informasi)');
});

it('resolves correct workflow steps for Doni (WRI) booking TI Room', function () {
    $user = User::where('email', 'user.wri@spacein.test')->firstOrFail();
    $room = Room::where('room_name', 'Ruang Kelas TI')->firstOrFail();
    $workflow = Workflow::where('name', 'Peminjaman JTI')->firstOrFail();

    $booking = Booking::create([
        'user_id' => $user->id,
        'room_id' => $room->id,
        'workflow_id' => $workflow->id,
        'event_name' => 'WRI Bootcamp',
        'booking_date' => now()->addDays(6)->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '12:00',
        'status' => 'Pending',
        'current_step' => 1,
        'event_scope' => 'Internal',
    ]);

    $steps = $this->bridgeService->resolveStepChain($booking, 'Internal');
    expect($steps)->not->toBeEmpty();

    $labels = collect($steps)->map(fn ($s) => $s['tier_label'])->toArray();

    // WRI is a sub-organization under HMTI. Expected flow:
    // 1. Internal (Workshop Riset Informatika) -> Ketua WRI
    // 2. Induk (HMTI) -> Ketua HMTI
    // 3. BEM (BEM Polinema) -> Presiden BEM Polinema
    // 4. DPK (Jurusan Teknologi Informasi) -> DPK TI
    // 5. Pemilik Ruangan (Jurusan Teknologi Informasi) -> Ketua Jurusan TI
    expect($labels[0])->toContain('Internal (Workshop Riset Informatika)');
    expect($labels[1])->toContain('Induk (HMTI)');
    expect($labels[2])->toContain('BEM (BEM Polinema)');
    expect($labels[3])->toContain('DPK (Jurusan Teknologi Informasi)');
    expect($labels[4])->toContain('Pemilik Ruangan (Jurusan Teknologi Informasi)');
});

it('resolves correct workflow steps for Citra (HME) booking Graha Polinema', function () {
    $user = User::where('email', 'citra@spacein.test')->firstOrFail();
    $room = Room::where('room_name', 'Graha Polinema')->firstOrFail();
    $workflow = Workflow::where('name', 'Peminjaman Graha Polinema')->firstOrFail();

    // Dynamically seed workflow for HME
    $hmeUnit = Unit::where('unit_name', 'HME')->firstOrFail();
    $posHme = Position::where('unit_id', $hmeUnit->id)->firstOrFail();
    $wfHme = Workflow::create([
        'unit_id' => $hmeUnit->id,
        'name' => 'Alur HME',
        'description' => 'Alur umum HME',
    ]);
    WorkflowStep::create([
        'workflow_id' => $wfHme->id,
        'position_id' => $posHme->id,
        'step_order' => 1,
        'requires_attachment' => false,
    ]);

    // Dynamically seed DPK position and user for Jurusan Teknik Elektro
    $elektroUnit = Unit::where('unit_name', 'Jurusan Teknik Elektro')->firstOrFail();
    $posDpkElektro = Position::create([
        'unit_id' => $elektroUnit->id,
        'name' => 'DPK Elektro',
    ]);
    User::create([
        'unit_id' => $elektroUnit->id,
        'position_id' => $posDpkElektro->id,
        'role_id' => Role::where('name', 'Penyetuju')->firstOrFail()->id,
        'name' => 'Pembina Elektro',
        'email' => 'dpk.elektro@spacein.test',
        'password' => Hash::make('12345'),
    ]);

    $booking = Booking::create([
        'user_id' => $user->id,
        'room_id' => $room->id,
        'workflow_id' => $workflow->id,
        'event_name' => 'Konser HME',
        'booking_date' => now()->addDays(7)->format('Y-m-d'),
        'start_time' => '13:00',
        'end_time' => '17:00',
        'status' => 'Pending',
        'current_step' => 1,
        'event_scope' => 'Internal',
    ]);

    $steps = $this->bridgeService->resolveStepChain($booking, 'Internal');
    expect($steps)->not->toBeEmpty();

    $labels = collect($steps)->map(fn ($s) => $s['tier_label'])->toArray();

    // HME is Organisasi under Elektro. Room is Graha (Pusat). Expected flow:
    // 1. Internal (HME) -> Ketua HME
    // 2. BEM (BEM Polinema) -> Presiden BEM Polinema
    // 3. DPK (Jurusan Teknik Elektro) -> DPK Elektro (Pembina)
    // 4. Pemilik Ruangan (Pusat - Politeknik Negeri Malang) -> Wadir III, Wadir II
    expect($labels[0])->toContain('Internal (HME)');
    expect($labels[1])->toContain('BEM (BEM Polinema)');
    expect($labels[2])->toContain('DPK (Jurusan Teknik Elektro)');
    expect($labels[3])->toContain('Pemilik Ruangan (Pusat - Politeknik Negeri Malang)');
});

it('resolves correct workflow steps for Staf Pusat booking Graha Polinema', function () {
    $user = User::where('email', 'staf@spacein.test')->firstOrFail();
    $room = Room::where('room_name', 'Graha Polinema')->firstOrFail();
    $workflow = Workflow::where('name', 'Peminjaman Graha Polinema')->firstOrFail();

    $booking = Booking::create([
        'user_id' => $user->id,
        'room_id' => $room->id,
        'workflow_id' => $workflow->id,
        'event_name' => 'Rapat Akademik Pusat',
        'booking_date' => now()->addDays(10)->format('Y-m-d'),
        'start_time' => '08:00',
        'end_time' => '12:00',
        'status' => 'Pending',
        'current_step' => 1,
        'event_scope' => 'Internal',
    ]);

    $steps = $this->bridgeService->resolveStepChain($booking, 'Internal');
    expect($steps)->not->toBeEmpty();

    $labels = collect($steps)->map(fn ($s) => $s['tier_label'])->toArray();

    // Staf Pusat is from Pusat (Level 1). Graha is owned by Pusat. Expected flow:
    // 1. Internal (Pusat - Politeknik Negeri Malang) -> Wadir III, Wadir II
    expect($labels[0])->toContain('Internal (Pusat - Politeknik Negeri Malang)');
    expect($labels[1])->toContain('Internal (Pusat - Politeknik Negeri Malang)');
});

it('resolves correct workflow steps for Staf TI booking TI Room', function () {
    $user = User::where('email', 'staf.ti@spacein.test')->firstOrFail();
    $room = Room::where('room_name', 'Ruang Kelas TI')->firstOrFail();
    $workflow = Workflow::where('name', 'Peminjaman JTI')->firstOrFail();

    $booking = Booking::create([
        'user_id' => $user->id,
        'room_id' => $room->id,
        'workflow_id' => $workflow->id,
        'event_name' => 'Seminar Jurusan TI',
        'booking_date' => now()->addDays(12)->format('Y-m-d'),
        'start_time' => '09:00',
        'end_time' => '11:00',
        'status' => 'Pending',
        'current_step' => 1,
        'event_scope' => 'Internal',
    ]);

    $steps = $this->bridgeService->resolveStepChain($booking, 'Internal');
    expect($steps)->not->toBeEmpty();

    $labels = collect($steps)->map(fn ($s) => $s['tier_label'])->toArray();

    // Staf TI is from Jurusan TI (Level 2). Room TI is owned by Jurusan TI. Expected flow:
    // 1. Internal (Jurusan Teknologi Informasi) -> Ketua Jurusan TI
    expect($labels[0])->toContain('Internal (Jurusan Teknologi Informasi)');
});
