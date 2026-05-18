<?php

namespace Tests\Feature;

use App\Mail\ApprovalNeededMail;
use App\Models\Approval;
use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\BookingLog;
use App\Models\Building;
use App\Models\Position;
use App\Models\Role;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowRequirement;
use App\Models\WorkflowStep;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

/**
 * Test Suite untuk fitur Revisi Booking, Cancel Booking, Bulk PDF Download, dan QR Validation
 *
 * Fitur yang ditest:
 * 1. POST /user/bookings/{id}/revise - Revise booking dengan upload dokumen ulang
 * 2. PATCH /user/bookings/{id}/cancel - Cancel booking
 * 3. GET /admin_unit/bookings/bulk-pdf - Bulk download PDF
 * 4. GET /validate/{bookingId} - QR Code validation
 */
describe('Booking Revision Feature', function () {

    beforeEach(function () {
        // Setup roles
        Role::create(['name' => 'SuperAdmin']);
        Role::create(['name' => 'Admin_Unit']);
        Role::create(['name' => 'Approver']);
        Role::create(['name' => 'User']);

        // Setup unit & positions
        $this->unit = Unit::create(['unit_name' => 'Pusat', 'level' => 'Pusat']);
        $this->position1 = Position::create(['name' => 'Kaprodi', 'unit_id' => $this->unit->id]);
        $this->position2 = Position::create(['name' => 'Ketua Jurusan', 'unit_id' => $this->unit->id]);

        // Create building
        $this->building = Building::factory()->create();

        // Create test users
        $this->user = User::factory()
            ->create(['role_id' => Role::where('name', 'User')->first()->id, 'unit_id' => $this->unit->id]);

        $this->approver = User::factory()
            ->create([
                'role_id' => Role::where('name', 'Approver')->first()->id,
                'position_id' => $this->position1->id,
                'unit_id' => $this->unit->id,
            ]);

        // Create room & workflow
        $this->room = Room::factory()
            ->create(['unit_id' => $this->unit->id, 'building_id' => $this->building->id]);

        $this->workflow = Workflow::factory()
            ->create(['unit_id' => $this->unit->id]);

        // Create workflow steps
        WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'position_id' => $this->position1->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);

        WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'position_id' => $this->position2->id,
            'step_order' => 2,
            'requires_attachment' => false,
        ]);

        // Create workflow requirements
        $this->proposalReq = WorkflowRequirement::create([
            'workflow_id' => $this->workflow->id,
            'document_name' => 'Proposal Acara',
            'is_mandatory' => true,
        ]);

        $this->disposisiReq = WorkflowRequirement::create([
            'workflow_id' => $this->workflow->id,
            'document_name' => 'Surat Disposisi',
            'is_mandatory' => true,
        ]);
    });

    test('mahasiswa bisa upload ulang dokumen ketika status Revising', function () {
        Storage::fake('private');

        // Create booking dengan status Revising
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Revising',
                'current_step' => 1,
                'revision_count' => 1,
            ]);

        // Create initial attachments
        BookingAttachment::create([
            'booking_id' => $booking->id,
            'requirement_id' => $this->proposalReq->id,
            'uploader_id' => $this->user->id,
            'document_type' => 'Proposal Acara',
            'file_path' => 'attachments/1/proposal-v1.pdf',
        ]);

        // Login as user
        $this->actingAs($this->user);

        // Prepare new files
        $newProposal = UploadedFile::fake()->create('proposal-v2.pdf', 500);
        $newDisposisi = UploadedFile::fake()->create('disposisi-v2.pdf', 300);

        // Submit revise request
        $response = $this->postJson("/user/bookings/{$booking->id}/revise", [
            "requirement_{$this->proposalReq->id}" => $newProposal,
            "requirement_{$this->disposisiReq->id}" => $newDisposisi,
        ]);

        // Assert response
        $response->assertStatus(200);

        // Assert booking status changed to Pending
        expect(Booking::find($booking->id)->status)->toBe('Pending');
        expect(Booking::find($booking->id)->current_step)->toBe(1);

        // Assert old attachment deleted
        expect(BookingAttachment::where('booking_id', $booking->id)
            ->where('uploader_id', $this->user->id)->count())->toBe(2);

        // Assert new files exist (menggunakan path dari database karena Laravel menyimpan dengan nama random)
        $attachments = BookingAttachment::where('booking_id', $booking->id)
            ->where('uploader_id', $this->user->id)
            ->get();

        foreach ($attachments as $attachment) {
            Storage::disk('private')->assertExists($attachment->file_path);
        }
    });

    test('mahasiswa tidak bisa revise jika status bukan Revising', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Pending',
            ]);

        $this->actingAs($this->user);

        $response = $this->postJson("/user/bookings/{$booking->id}/revise", [
            "requirement_{$this->proposalReq->id}" => UploadedFile::fake()->create('proposal.pdf', 500),
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['error' => "Booking tidak dapat direvisi karena statusnya 'Pending'."]);
    });

    test('revise memerlukan semua dokumen mandatory', function () {
        Storage::fake('private');

        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Revising',
            ]);

        $this->actingAs($this->user);

        // Hanya upload 1 dari 2 dokumen mandatory
        $response = $this->postJson("/user/bookings/{$booking->id}/revise", [
            "requirement_{$this->proposalReq->id}" => UploadedFile::fake()->create('proposal.pdf', 500),
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['error' => "File wajib 'Surat Disposisi' tidak ditemukan."]);
    });

    test('email notifikasi dikirim ke approver setelah revise', function () {
        Mail::fake();
        Storage::fake('private');

        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Revising',
                'current_step' => 1,
            ]);

        $this->actingAs($this->user);

        $this->postJson("/user/bookings/{$booking->id}/revise", [
            "requirement_{$this->proposalReq->id}" => UploadedFile::fake()->create('proposal.pdf', 500),
            "requirement_{$this->disposisiReq->id}" => UploadedFile::fake()->create('disposisi.pdf', 300),
        ]);

        // Assert email sent ke approver
        Mail::assertSent(ApprovalNeededMail::class, function ($mail) {
            return $mail->hasTo($this->approver->email);
        });
    });
});

describe('Booking Cancellation Feature', function () {

    beforeEach(function () {
        // Setup roles
        Role::create(['name' => 'SuperAdmin']);
        Role::create(['name' => 'Admin_Unit']);
        Role::create(['name' => 'Approver']);
        Role::create(['name' => 'User']);

        $this->unit = Unit::create(['unit_name' => 'Pusat', 'level' => 'Pusat']);
        $this->building = Building::factory()->create();
        $this->user = User::factory()
            ->create(['role_id' => Role::where('name', 'User')->first()->id, 'unit_id' => $this->unit->id]);

        $this->room = Room::factory()->create(['unit_id' => $this->unit->id, 'building_id' => $this->building->id]);
        $this->workflow = Workflow::factory()->create(['unit_id' => $this->unit->id]);
    });

    test('mahasiswa bisa cancel booking dengan status Pending', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Pending',
            ]);

        $this->actingAs($this->user);

        $response = $this->patchJson("/user/bookings/{$booking->id}/cancel");

        $response->assertStatus(200);
        expect(Booking::find($booking->id)->status)->toBe('Cancelled');
    });

    test('mahasiswa bisa cancel booking dengan status Revising', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Revising',
            ]);

        $this->actingAs($this->user);

        $response = $this->patchJson("/user/bookings/{$booking->id}/cancel");

        $response->assertStatus(200);
        expect(Booking::find($booking->id)->status)->toBe('Cancelled');
    });

    test('mahasiswa bisa cancel booking dengan status Draft', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Draft',
            ]);

        $this->actingAs($this->user);

        $response = $this->patchJson("/user/bookings/{$booking->id}/cancel");

        $response->assertStatus(200);
        expect(Booking::find($booking->id)->status)->toBe('Cancelled');
    });

    test('mahasiswa tidak bisa cancel booking dengan status Approved', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Approved',
            ]);

        $this->actingAs($this->user);

        $response = $this->patchJson("/user/bookings/{$booking->id}/cancel");

        $response->assertStatus(422);
        $response->assertJsonFragment(['error' => "Booking tidak dapat dibatalkan karena statusnya 'Approved'."]);
    });

    test('mahasiswa tidak bisa cancel booking milik pengguna lain', function () {
        $otherUser = User::factory()
            ->create(['role_id' => Role::where('name', 'User')->first()->id, 'unit_id' => $this->unit->id]);

        $booking = Booking::factory()
            ->create([
                'user_id' => $otherUser->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Pending',
            ]);

        $this->actingAs($this->user);

        $response = $this->patchJson("/user/bookings/{$booking->id}/cancel");

        $response->assertStatus(404);
    });

    test('cancel booking mencatat log action', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Pending',
            ]);

        $this->actingAs($this->user);

        $this->patchJson("/user/bookings/{$booking->id}/cancel");

        // Assert log entry created
        expect(BookingLog::where('booking_id', $booking->id)
            ->where('action', 'CANCELLED')->count())->toBe(1);
    });
});

describe('Bulk PDF Download Feature', function () {

    beforeEach(function () {
        Role::create(['name' => 'SuperAdmin']);
        Role::create(['name' => 'Admin_Unit']);
        Role::create(['name' => 'Approver']);
        Role::create(['name' => 'User']);

        $this->unit = Unit::create(['unit_name' => 'Jurusan TI', 'level' => 'Jurusan']);
        $this->building = Building::factory()->create();

        $this->adminUnit = User::factory()
            ->create([
                'role_id' => Role::where('name', 'Admin_Unit')->first()->id,
                'unit_id' => $this->unit->id,
            ]);

        $this->user = User::factory()
            ->create(['role_id' => Role::where('name', 'User')->first()->id, 'unit_id' => $this->unit->id]);
    });

    test('admin unit bisa download semua PDF approved milik unitnya', function () {
        Storage::fake('private');

        $room = Room::factory()->create(['unit_id' => $this->unit->id, 'building_id' => $this->building->id]);
        $workflow = Workflow::factory()->create(['unit_id' => $this->unit->id]);

        // Create 3 approved bookings
        $bookings = [];
        for ($i = 0; $i < 3; $i++) {
            $booking = Booking::factory()
                ->create([
                    'user_id' => $this->user->id,
                    'room_id' => $room->id,
                    'workflow_id' => $workflow->id,
                    'status' => 'Approved',
                    'pdf_path' => "permits/booking-{$i}.pdf",
                ]);
            $bookings[] = $booking;

            // Create dummy PDF file
            Storage::disk('private')->put("permits/booking-{$i}.pdf", "dummy pdf content {$i}");
        }

        $this->actingAs($this->adminUnit);

        $response = $this->getJson('/admin_unit/bookings/bulk-pdf');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
    });

    test('admin unit hanya bisa download PDF dari unit mereka sendiri', function () {
        Storage::fake('private');

        $otherBuilding = Building::factory()->create();
        $otherUnit = Unit::create(['unit_name' => 'Jurusan Sipil', 'level' => 'Jurusan']);
        $otherRoom = Room::factory()->create(['unit_id' => $otherUnit->id, 'building_id' => $otherBuilding->id]);
        $otherWorkflow = Workflow::factory()->create(['unit_id' => $otherUnit->id]);
        $otherUser = User::factory()
            ->create(['role_id' => Role::where('name', 'User')->first()->id, 'unit_id' => $otherUnit->id]);

        // Booking dari unit lain
        Booking::factory()
            ->create([
                'user_id' => $otherUser->id,
                'room_id' => $otherRoom->id,
                'workflow_id' => $otherWorkflow->id,
                'status' => 'Approved',
                'pdf_path' => 'permits/other-booking.pdf',
            ]);

        $this->actingAs($this->adminUnit);

        $response = $this->getJson('/admin_unit/bookings/bulk-pdf');

        $response->assertStatus(404);
        $response->assertJsonFragment(['error' => 'Tidak ada surat izin yang tersedia.']);
    });

    test('bulk download hanya include approved bookings', function () {
        Storage::fake('private');

        $room = Room::factory()->create(['unit_id' => $this->unit->id, 'building_id' => $this->building->id]);
        $workflow = Workflow::factory()->create(['unit_id' => $this->unit->id]);

        // Create 1 approved, 1 pending
        Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $room->id,
                'workflow_id' => $workflow->id,
                'status' => 'Approved',
                'pdf_path' => 'permits/approved.pdf',
            ]);

        Storage::disk('private')->put('permits/approved.pdf', 'approved pdf');

        Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $room->id,
                'workflow_id' => $workflow->id,
                'status' => 'Pending',
                'pdf_path' => 'permits/pending.pdf',
            ]);

        $this->actingAs($this->adminUnit);

        $response = $this->getJson('/admin_unit/bookings/bulk-pdf');

        $response->assertStatus(200);
    });
});

describe('QR Code Validation Feature', function () {

    beforeEach(function () {
        Role::create(['name' => 'SuperAdmin']);
        Role::create(['name' => 'Admin_Unit']);
        Role::create(['name' => 'Approver']);
        Role::create(['name' => 'User']);

        $this->unit = Unit::create(['unit_name' => 'Pusat', 'level' => 'Pusat']);
        $this->building = Building::factory()->create();
        $this->position = Position::create(['name' => 'Wadir', 'unit_id' => $this->unit->id]);

        $this->user = User::factory()
            ->create(['role_id' => Role::where('name', 'User')->first()->id, 'unit_id' => $this->unit->id]);

        $this->approver = User::factory()
            ->create([
                'role_id' => Role::where('name', 'Approver')->first()->id,
                'position_id' => $this->position->id,
                'unit_id' => $this->unit->id,
            ]);

        $this->room = Room::factory()->create(['unit_id' => $this->unit->id, 'building_id' => $this->building->id]);
        $this->workflow = Workflow::factory()->create(['unit_id' => $this->unit->id]);

        WorkflowStep::create([
            'workflow_id' => $this->workflow->id,
            'position_id' => $this->position->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);
    });

    test('halaman validasi bisa diakses publik dengan booking ID', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Approved',
            ]);

        // Tidak perlu login, akses publik
        $response = $this->get("/validate/{$booking->id}");

        $response->assertStatus(200);
        $response->assertViewIs('booking.validate');
        $response->assertViewHas('booking');
    });

    test('halaman validasi menampilkan informasi booking lengkap', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'event_name' => 'Seminar TI 2026',
                'status' => 'Approved',
            ]);

        $response = $this->get("/validate/{$booking->id}");

        $response->assertStatus(200);
        expect($response->viewData('booking')->id)->toBe($booking->id);
        expect($response->viewData('booking')->event_name)->toBe('Seminar TI 2026');
    });

    test('halaman validasi bisa diakses walau booking masih Pending', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Pending',
            ]);

        $response = $this->get("/validate/{$booking->id}");

        $response->assertStatus(200);
    });

    test('halaman validasi return 404 untuk booking tidak ada', function () {
        $response = $this->get('/validate/99999');

        $response->assertStatus(404);
    });

    test('halaman validasi menampilkan approval timeline', function () {
        $booking = Booking::factory()
            ->create([
                'user_id' => $this->user->id,
                'room_id' => $this->room->id,
                'workflow_id' => $this->workflow->id,
                'status' => 'Approved',
            ]);

        // Get the workflow step that was created in beforeEach
        $workflowStep = WorkflowStep::where('workflow_id', $this->workflow->id)
            ->where('step_order', 1)
            ->first();

        // Simulate approval
        Approval::create([
            'booking_id' => $booking->id,
            'approver_id' => $this->approver->id,
            'step_id' => $workflowStep->id,
            'approval_status' => 'Approved',
            'signature_image' => null,
        ]);

        $response = $this->get("/validate/{$booking->id}");

        $response->assertStatus(200);
        expect(count($response->viewData('booking')->approvals))->toBe(1);
    });
});
