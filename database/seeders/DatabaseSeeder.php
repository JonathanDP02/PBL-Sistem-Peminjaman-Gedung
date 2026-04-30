<?php

namespace Database\Seeders;

use App\Models\Approval;
use App\Models\Booking;
use App\Models\BookingAttachment;
use App\Models\Building;
use App\Models\Position;
use App\Models\Role;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use App\Models\Workflow;
use App\Models\WorkflowRequirement;
use App\Models\WorkflowStep;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════════
        // STEP 1: SEED ROLES
        // Tabel roles punya kolom: name, description
        // ═══════════════════════════════════════════════════════
        $roleSuperAdmin = Role::create([
            'name' => 'SuperAdmin',
            'description' => 'Administrator pusat dengan akses penuh ke seluruh sistem',
        ]);
        $roleAdminUnit = Role::create([
            'name' => 'Admin_Unit',
            'description' => 'Administrator unit (jurusan/organisasi) yang mengelola data di wilayahnya',
        ]);
        $roleUser = Role::create([
            'name' => 'User',
            'description' => 'Mahasiswa atau civitas akademika yang dapat mengajukan peminjaman ruangan',
        ]);
        $roleApprover = Role::create([
            'name' => 'Approver',
            'description' => 'Pejabat berwenang yang mereview dan menyetujui/menolak pengajuan peminjaman',
        ]);

        // ═══════════════════════════════════════════════════════
        // STEP 2: SEED HIERARKI UNIT
        // Struktur: Pusat -> 3 Jurusan -> masing-masing 2 Organisasi
        // ═══════════════════════════════════════════════════════

        // Level 1: Pusat
        $pusat = Unit::create([
            'parent_id' => null,
            'level' => 'Pusat',
            'unit_name' => 'Pusat - Politeknik Negeri Malang',
            'description' => 'Unit induk tertinggi institusi',
        ]);

        // Level 2: Jurusan (parent = Pusat)
        $jurusanTI = Unit::create([
            'parent_id' => $pusat->id,
            'level' => 'Jurusan',
            'unit_name' => 'Jurusan Teknologi Informasi',
            'description' => 'Jurusan TI - mengelola program studi berbasis IT',
        ]);

        $jurusanSipil = Unit::create([
            'parent_id' => $pusat->id,
            'level' => 'Jurusan',
            'unit_name' => 'Jurusan Teknik Sipil',
            'description' => 'Jurusan Sipil - infrastruktur dan konstruksi',
        ]);

        $jurusanElektro = Unit::create([
            'parent_id' => $pusat->id,
            'level' => 'Jurusan',
            'unit_name' => 'Jurusan Teknik Elektro',
            'description' => 'Jurusan Elektro - sistem ketenagaan dan elektronika',
        ]);

        // Level 3: Organisasi (parent = masing-masing Jurusan)
        // Organisasi di bawah Jurusan TI
        $hmti = Unit::create([
            'parent_id' => $jurusanTI->id,
            'level' => 'Organisasi',
            'unit_name' => 'HMTI - Himpunan Mahasiswa TI',
            'description' => 'Organisasi kemahasiswaan Jurusan TI',
        ]);
        $bemti = Unit::create([
            'parent_id' => $jurusanTI->id,
            'level' => 'Organisasi',
            'unit_name' => 'BEM TI',
            'description' => 'Badan Eksekutif Mahasiswa Jurusan TI',
        ]);

        // Organisasi di bawah Jurusan Sipil
        $hmSipil = Unit::create([
            'parent_id' => $jurusanSipil->id,
            'level' => 'Organisasi',
            'unit_name' => 'HM Sipil',
            'description' => 'Himpunan Mahasiswa Teknik Sipil',
        ]);
        $bemSipil = Unit::create([
            'parent_id' => $jurusanSipil->id,
            'level' => 'Organisasi',
            'unit_name' => 'BEM Sipil',
            'description' => 'Badan Eksekutif Mahasiswa Sipil',
        ]);

        // Organisasi di bawah Jurusan Elektro
        $hmElektro = Unit::create([
            'parent_id' => $jurusanElektro->id,
            'level' => 'Organisasi',
            'unit_name' => 'HM Elektro',
            'description' => 'Himpunan Mahasiswa Teknik Elektro',
        ]);
        $bemElektro = Unit::create([
            'parent_id' => $jurusanElektro->id,
            'level' => 'Organisasi',
            'unit_name' => 'BEM Elektro',
            'description' => 'Badan Eksekutif Mahasiswa Elektro',
        ]);

        // ═══════════════════════════════════════════════════════
        // STEP 3: SEED POSITIONS (jabatan per unit)
        // Approver menggunakan position_id untuk identifikasi
        // ═══════════════════════════════════════════════════════

        // Jabatan di Pusat
        $posWadir = Position::create(['unit_id' => $pusat->id,       'name' => 'Wakil Direktur']);

        // Jabatan di Jurusan TI
        $posKajurTI = Position::create(['unit_id' => $jurusanTI->id,    'name' => 'Ketua Jurusan TI']);
        $posKaprodiTI = Position::create(['unit_id' => $jurusanTI->id,   'name' => 'Kaprodi TI']);

        // Jabatan di Jurusan Sipil
        $posKajurSipil = Position::create(['unit_id' => $jurusanSipil->id, 'name' => 'Ketua Jurusan Sipil']);

        // Jabatan di Jurusan Elektro
        $posKajurElektro = Position::create(['unit_id' => $jurusanElektro->id, 'name' => 'Ketua Jurusan Elektro']);

        // Jabatan organisasi (untuk kelola data lokal)
        $posKetHMTI = Position::create(['unit_id' => $hmti->id, 'name' => 'Ketua HMTI']);

        // ═══════════════════════════════════════════════════════
        // STEP 4: SEED BUILDINGS & ROOMS
        // ═══════════════════════════════════════════════════════

        $gedungAN = Building::create([
            'building_name' => 'Gedung Administrasi Niaga',

        ]);
        $gedungTI = Building::create([
            'building_name' => 'Gedung Teknologi Informasi',

        ]);
        $auditorium = Building::create([
            'building_name' => 'Auditorium Utama',

        ]);

        // Ruangan di Gedung A (milik Pusat)
        Room::create([
            'building_id' => $gedungAN->id,
            'unit_id' => $pusat->id,
            'room_name' => 'Ruang Rapat',
            'capacity' => 30,
            'description' => 'Ruang rapat resmi untuk pimpinan institusi',
        ]);
        Room::create([
            'building_id' => $gedungAN->id,
            'unit_id' => $pusat->id,
            'room_name' => 'Aula',
            'capacity' => 100,
            'description' => 'Aula besar untuk kegiatan kampus',
        ]);

        // Ruangan di Gedung TI (milik Jurusan TI)
        Room::create([
            'building_id' => $gedungTI->id,
            'unit_id' => $jurusanTI->id,
            'room_name' => 'Lab Komputer TI-101',
            'capacity' => 40,
            'description' => 'Lab komputer utama Jurusan TI',
        ]);
        Room::create([
            'building_id' => $gedungTI->id,
            'unit_id' => $jurusanTI->id,
            'room_name' => 'Ruang Kelas TI',
            'capacity' => 50,
            'description' => 'Ruang kelas reguler Jurusan TI',
        ]);

        // Auditorium (milik Pusat)
        Room::create([
            'building_id' => $auditorium->id,
            'unit_id' => $pusat->id,
            'room_name' => 'Auditorium Lantai 8',
            'capacity' => 200,
            'description' => 'Ruang auditorium utama kampus',
        ]);

        // ═══════════════════════════════════════════════════════
        // STEP 5: SEED USERS
        // Urutan: SuperAdmin -> Admin_Unit -> Approver -> User biasa
        // ═══════════════════════════════════════════════════════

        // 1 SuperAdmin (unit = Pusat, tanpa position)
        User::create([
            'unit_id' => $pusat->id,
            'position_id' => null,
            'role_id' => $roleSuperAdmin->id,
            'name' => 'Super Admin Politeknik',
            'email' => 'superadmin@spacein.test',
            'password' => Hash::make('12345'),
        ]);

        // 2 Admin Unit (beda jurusan: TI dan Sipil)
        User::create([
            'unit_id' => $jurusanTI->id,
            'position_id' => null,
            'role_id' => $roleAdminUnit->id,
            'name' => 'Admin Jurusan TI',
            'email' => 'admin.ti@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $jurusanSipil->id,
            'position_id' => null,
            'role_id' => $roleAdminUnit->id,
            'name' => 'Admin Jurusan Sipil',
            'email' => 'admin.sipil@spacein.test',
            'password' => Hash::make('12345'),
        ]);

        // 3 Approver (beda posisi: Kajur TI, Wadir, Kaprodi TI)
        User::create([
            'unit_id' => $jurusanTI->id,
            'position_id' => $posKajurTI->id,  // Ketua Jurusan TI
            'role_id' => $roleApprover->id,
            'name' => 'Dr. Budi Santoso',
            'email' => 'kajur.ti@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $pusat->id,
            'position_id' => $posWadir->id,    // Wakil Direktur
            'role_id' => $roleApprover->id,
            'name' => 'Dr. Siti Rahayu',
            'email' => 'wadir@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $jurusanTI->id,
            'position_id' => $posKaprodiTI->id, // Kaprodi TI
            'role_id' => $roleApprover->id,
            'name' => 'Ir. Agus Wijaya',
            'email' => 'kaprodi.ti@spacein.test',
            'password' => Hash::make('12345'),
        ]);

        // 3 User biasa (peminjam) — dari berbagai unit organisasi
        User::create([
            'unit_id' => $hmti->id,
            'position_id' => null,
            'role_id' => $roleUser->id,
            'name' => 'Andi Mahasiswa TI',
            'email' => 'user@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $bemSipil->id,
            'position_id' => null,
            'role_id' => $roleUser->id,
            'name' => 'Budi Mahasiswa Sipil',
            'email' => 'budi@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $hmElektro->id,
            'position_id' => null,
            'role_id' => $roleUser->id,
            'name' => 'Citra Mahasiswi Elektro',
            'email' => 'citra@spacein.test',
            'password' => Hash::make('12345'),
        ]);

        // ═══════════════════════════════════════════════════════
        // STEP 6: SEED WORKFLOWS & WORKFLOW STEPS
        // Contoh: Peminjaman JTI = Kaprodi -> Kajur -> Wadir
        // ═══════════════════════════════════════════════════════

        // Workflow untuk peminjaman ruangan Jurusan TI
        $wfJTI = Workflow::create([
            'unit_id' => $jurusanTI->id,
            'name' => 'Peminjaman JTI',
            'description' => 'Alur persetujuan peminjaman ruangan Jurusan Teknologi Informasi',
        ]);

        // Step 1: Kaprodi TI approve dulu
        WorkflowStep::create([
            'workflow_id' => $wfJTI->id,
            'position_id' => $posKaprodiTI->id,
            'step_order' => 1,
            'requires_attachment' => false, // Kaprodi tidak wajib upload dokumen
        ]);

        // Step 2: Kajur TI approve
        WorkflowStep::create([
            'workflow_id' => $wfJTI->id,
            'position_id' => $posKajurTI->id,
            'step_order' => 2,
            'requires_attachment' => false,
        ]);

        // Step 3: Wadir approve terakhir (wajib upload Surat Balasan/Syarat Wadir)
        WorkflowStep::create([
            'workflow_id' => $wfJTI->id,
            'position_id' => $posWadir->id,
            'step_order' => 3,
            'requires_attachment' => true, // Wadir wajib upload surat disposisi
        ]);

        // Workflow untuk peminjaman Auditorium (milik Pusat)
        $wfAuditorium = Workflow::create([
            'unit_id' => $pusat->id,
            'name' => 'Peminjaman Auditorium',
            'description' => 'Alur persetujuan peminjaman Auditorium Utama',
        ]);

        // Step 1: Kajur unit peminjam approve
        WorkflowStep::create([
            'workflow_id' => $wfAuditorium->id,
            'position_id' => $posKajurTI->id, // contoh: jika peminjam dari TI
            'step_order' => 1,
            'requires_attachment' => false,
        ]);

        // Step 2: Wadir approve (level Pusat)
        WorkflowStep::create([
            'workflow_id' => $wfAuditorium->id,
            'position_id' => $posWadir->id,
            'step_order' => 2,
            'requires_attachment' => true, // wajib upload surat izin Wadir
        ]);

        // ═══════════════════════════════════════════════════════
        // STEP 7: SEED TEST DATA MENGGUNAKAN FACTORIES
        // Generate bookings & approvals untuk dashboard test
        // ═══════════════════════════════════════════════════════

        // Get users & rooms untuk factory
        $peminjam = User::where('email', 'user@spacein.test')->first();
        $approverKajur = User::where('email', 'kajur.ti@spacein.test')->first();
        $ruangKelas = Room::where('room_name', 'Ruang Kelas TI')->first();

        // Create 5 pending approvals dengan factory
        for ($i = 0; $i < 5; $i++) {
            $booking = Booking::factory()->pending()->create([
                'user_id' => $peminjam->id,
                'room_id' => $ruangKelas->id,
                'workflow_id' => $wfJTI->id,
            ]);

            Approval::factory()
                ->pending()
                ->create([
                    'booking_id' => $booking->id,
                    'approver_id' => $approverKajur->id,
                ]);
        }

        // Create workflow dengan requirements menggunakan factory
        $workflowFactory = Workflow::factory()->create([
            'unit_id' => $jurusanTI->id,
            'name' => 'Peminjaman dari Factory',
        ]);

        // Add requirements ke workflow
        WorkflowRequirement::factory(2)->mandatory()->create([
            'workflow_id' => $workflowFactory->id,
        ]);
        WorkflowRequirement::factory(1)->optional()->create([
            'workflow_id' => $workflowFactory->id,
        ]);

        // Create booking untuk test attachments
        $booking = Booking::factory()->create([
            'user_id' => $peminjam->id,
            'room_id' => $ruangKelas->id,
            'workflow_id' => $wfJTI->id,
            'status' => 'Pending',
        ]);

        // Add 2 PDF attachments ke booking
        BookingAttachment::factory(2)->pdf()->create([
            'booking_id' => $booking->id,
        ]);

        // Create approval untuk booking tersebut
        Approval::factory()
            ->pending()
            ->create([
                'booking_id' => $booking->id,
                'approver_id' => $approverKajur->id,
            ]);

        // Create approved booking untuk dashboard stats
        $approvedBooking = Booking::factory()
            ->approved()
            ->create([
                'user_id' => $peminjam->id,
                'room_id' => $ruangKelas->id,
                'workflow_id' => $wfJTI->id,
                'status' => 'Approved',
            ]);
        Approval::factory()
            ->approved()
            ->create([
                'booking_id' => $approvedBooking->id,
                'approver_id' => $approverKajur->id,
                'created_at' => now(),
            ]);

        // Create rejected booking
        $rejectedBooking = Booking::factory()
            ->rejected()
            ->create([
                'user_id' => $peminjam->id,
                'room_id' => $ruangKelas->id,
                'workflow_id' => $wfJTI->id,
                'status' => 'Rejected',
            ]);
        Approval::factory()
            ->rejected()
            ->create([
                'booking_id' => $rejectedBooking->id,
                'approver_id' => $approverKajur->id,
                'created_at' => now(),
            ]);

        // Create revising booking
        $revisingBooking = Booking::factory()->create([
            'user_id' => $peminjam->id,
            'room_id' => $ruangKelas->id,
            'workflow_id' => $wfJTI->id,
            'status' => 'Revising',
            'revision_count' => 1,
        ]);
        Approval::factory()
            ->rejected()
            ->create([
                'booking_id' => $revisingBooking->id,
                'approver_id' => $approverKajur->id,
                'created_at' => now(),
                'notes' => 'Dokumen perlu dilengkapi kembali',
            ]);
    }
}
