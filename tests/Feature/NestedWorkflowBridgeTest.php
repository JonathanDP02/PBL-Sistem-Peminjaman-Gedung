<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Position;
use App\Models\Room;
use App\Models\User;
use App\Models\Workflow;
use App\Services\WorkflowBridgeService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NestedWorkflowBridgeTest extends TestCase
{
    use RefreshDatabase;

    protected WorkflowBridgeService $bridgeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
        $this->bridgeService = new WorkflowBridgeService;
    }

    /**
     * Test WRI (sub-organization under HMTI) booking JTI room.
     * Expects:
     * 1. Humas WRI (Internal WRI)
     * 2. Ketua WRI (Internal WRI)
     * 3. Ketua HMTI (Induk HMTI - dynamically injected)
     * 4. Presiden BEM Polinema (BEM Polinema gatekeeper - dynamically injected)
     * 5. Pembina WRI (Pembina WRI - dynamically injected)
     * 6. Ketua Jurusan TI (Pemilik Ruangan JTI)
     */
    public function test_wri_booking_resolves_nested_parent_and_bem_gatekeeper()
    {
        $wriBorrower = User::where('email', 'user.wri@spacein.test')->firstOrFail();
        $jtiRoom = Room::where('room_name', 'Ruang Kelas TI')->firstOrFail();
        $workflow = Workflow::where('name', 'Peminjaman JTI')->firstOrFail();

        $booking = Booking::create([
            'user_id' => $wriBorrower->id,
            'room_id' => $jtiRoom->id,
            'workflow_id' => $workflow->id,
            'event_name' => 'WRI Cyber Drill',
            'booking_date' => now()->addDays(10)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'status' => 'Pending',
            'current_step' => 1,
            'event_scope' => 'Internal',
        ]);

        $steps = $this->bridgeService->resolveStepChain($booking, 'Internal');

        // Assert steps chain length and order
        $this->assertNotEmpty($steps);

        $labels = collect($steps)->map(fn ($s) => $s['tier_label'])->toArray();
        $positions = collect($steps)->map(fn ($s) => Position::find($s['position_id'])->name)->toArray();

        // Expect WRI internal humas & ketua
        $this->assertStringContainsString('Internal (Workshop Riset Informatika)', $labels[0]);
        $this->assertEquals('Humas WRI', $positions[0]);

        $this->assertStringContainsString('Internal (Workshop Riset Informatika)', $labels[1]);
        $this->assertEquals('Ketua WRI', $positions[1]);

        // Expect dynamic HMTI parent injection
        $this->assertStringContainsString('Induk (HMTI)', $labels[2]);
        $this->assertEquals('Ketua HMTI', $positions[2]);

        // Expect BEM Polinema gatekeeper
        $this->assertStringContainsString('BEM (BEM Polinema)', $labels[3]);
        $this->assertEquals('Presiden BEM Polinema', $positions[3]);

        // Expect Pembina WRI bounce-back step
        $this->assertStringContainsString('Pembina (Workshop Riset Informatika)', $labels[4]);
        $this->assertEquals('Pembina WRI', $positions[4]);

        // Expect Room Owner JTI
        $this->assertStringContainsString('Pemilik Ruangan (Jurusan Teknologi Informasi)', $labels[5]);
        $this->assertEquals('Ketua Jurusan TI', $positions[5]);
    }

    /**
     * Test HMTI (organization directly under Jurusan TI) booking JTI room.
     * Expects:
     * 1. Humas HMTI (Internal HMTI)
     * 2. Ketua HMTI (Internal HMTI)
     * 3. Presiden BEM Polinema (BEM Polinema gatekeeper)
     * 4. Pembina HMTI (Pembina HMTI)
     * 5. Ketua Jurusan TI (Pemilik Ruangan JTI)
     *
     * Note: NO HMTI parent injection because HMTI's parent is Jurusan (level "Jurusan", not "Organisasi").
     */
    public function test_hmti_booking_does_not_inject_non_organisasi_parent()
    {
        $hmtiBorrower = User::where('email', 'user@spacein.test')->firstOrFail();
        $jtiRoom = Room::where('room_name', 'Ruang Kelas TI')->firstOrFail();
        $workflow = Workflow::where('name', 'Peminjaman JTI')->firstOrFail();

        $booking = Booking::create([
            'user_id' => $hmtiBorrower->id,
            'room_id' => $jtiRoom->id,
            'workflow_id' => $workflow->id,
            'event_name' => 'HMTI Gathering',
            'booking_date' => now()->addDays(10)->format('Y-m-d'),
            'start_time' => '09:00',
            'end_time' => '12:00',
            'status' => 'Pending',
            'current_step' => 1,
            'event_scope' => 'Internal',
        ]);

        $steps = $this->bridgeService->resolveStepChain($booking, 'Internal');

        $labels = collect($steps)->map(fn ($s) => $s['tier_label'])->toArray();
        $positions = collect($steps)->map(fn ($s) => Position::find($s['position_id'])->name)->toArray();

        // 1. Humas HMTI
        $this->assertStringContainsString('Internal (HMTI)', $labels[0]);
        $this->assertEquals('Humas HMTI', $positions[0]);

        // 2. Ketua HMTI
        $this->assertStringContainsString('Internal (HMTI)', $labels[1]);
        $this->assertEquals('Ketua HMTI', $positions[1]);

        // 3. Presiden BEM (BEM Polinema)
        $this->assertStringContainsString('BEM (BEM Polinema)', $labels[2]);
        $this->assertEquals('Presiden BEM Polinema', $positions[2]);

        // 4. Pembina HMTI
        $this->assertStringContainsString('Pembina (HMTI)', $labels[3]);
        $this->assertEquals('Pembina HMTI', $positions[3]);

        // 5. Ketua Jurusan TI (Pemilik Ruangan JTI)
        $this->assertStringContainsString('Pemilik Ruangan (Jurusan Teknologi Informasi)', $labels[4]);
        $this->assertEquals('Ketua Jurusan TI', $positions[4]);

        // Assert no Induk (Jurusan TI) tier is injected in the list
        foreach ($labels as $label) {
            $this->assertStringNotContainsString('Induk', $label);
        }
    }
}
