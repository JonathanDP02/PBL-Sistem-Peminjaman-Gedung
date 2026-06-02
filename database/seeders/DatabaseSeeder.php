<?php

namespace Database\Seeders;

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
            'name' => 'Administrator Utama',
            'description' => 'Administrator pusat dengan akses penuh ke seluruh sistem',
        ]);
        $roleAdminUnit = Role::create([
            'name' => 'Administrator Unit',
            'description' => 'Administrator unit (jurusan/organisasi) yang mengelola data di wilayahnya',
        ]);
        $roleUser = Role::create([
            'name' => 'Peminjam',
            'description' => 'Mahasiswa atau civitas akademika yang dapat mengajukan peminjaman ruangan',
        ]);
        $roleApprover = Role::create([
            'name' => 'Penyetuju',
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
        $jurusanTI = Unit::create(['parent_id' => $pusat->id, 'level' => 'Jurusan', 'unit_name' => 'Jurusan Teknologi Informasi', 'description' => 'Jurusan TI']);
        $jurusanSipil = Unit::create(['parent_id' => $pusat->id, 'level' => 'Jurusan', 'unit_name' => 'Jurusan Teknik Sipil', 'description' => 'Jurusan Sipil']);
        $jurusanElektro = Unit::create(['parent_id' => $pusat->id, 'level' => 'Jurusan', 'unit_name' => 'Jurusan Teknik Elektro', 'description' => 'Jurusan Elektro']);
        $jurusanMesin = Unit::create(['parent_id' => $pusat->id, 'level' => 'Jurusan', 'unit_name' => 'Jurusan Teknik Mesin', 'description' => 'Jurusan Mesin']);
        $jurusanKimia = Unit::create(['parent_id' => $pusat->id, 'level' => 'Jurusan', 'unit_name' => 'Jurusan Teknik Kimia', 'description' => 'Jurusan Kimia']);
        $jurusanAkuntansi = Unit::create(['parent_id' => $pusat->id, 'level' => 'Jurusan', 'unit_name' => 'Jurusan Akuntansi', 'description' => 'Jurusan Akuntansi']);
        $jurusanAN = Unit::create(['parent_id' => $pusat->id, 'level' => 'Jurusan', 'unit_name' => 'Jurusan Administrasi Niaga', 'description' => 'Jurusan Administrasi Niaga']);
        // Level 3: Organisasi (parent = masing-masing Jurusan atau Pusat)
        $bemPusat = Unit::create(['parent_id' => $pusat->id, 'level' => 'Organisasi', 'unit_name' => 'BEM Polinema', 'description' => 'Badan Eksekutif Mahasiswa Pusat']);
        $dpmPusat = Unit::create(['parent_id' => $pusat->id, 'level' => 'Organisasi', 'unit_name' => 'Dewan Perwakilan Mahasiswa', 'description' => 'Dewan Perwakilan Mahasiswa']);
        $formadiksi = Unit::create(['parent_id' => $pusat->id, 'level' => 'Organisasi', 'unit_name' => 'Formadiksi', 'description' => 'Forum Mahasiswa Bidikmisi']);

        $hmti = Unit::create(['parent_id' => $jurusanTI->id, 'level' => 'Organisasi', 'unit_name' => 'HMTI', 'description' => 'Himpunan Mahasiswa TI']);
        $wri = Unit::create(['parent_id' => $hmti->id, 'level' => 'Organisasi', 'unit_name' => 'Workshop Riset Informatika', 'description' => 'Workshop Riset Informatika']);
        $hms = Unit::create(['parent_id' => $jurusanSipil->id, 'level' => 'Organisasi', 'unit_name' => 'HMS', 'description' => 'Himpunan Mahasiswa Sipil']);
        $hme = Unit::create(['parent_id' => $jurusanElektro->id, 'level' => 'Organisasi', 'unit_name' => 'HME', 'description' => 'Himpunan Mahasiswa Elektro']);
        $hmm = Unit::create(['parent_id' => $jurusanMesin->id, 'level' => 'Organisasi', 'unit_name' => 'HMM', 'description' => 'Himpunan Mahasiswa Mesin']);
        $hmk = Unit::create(['parent_id' => $jurusanKimia->id, 'level' => 'Organisasi', 'unit_name' => 'HMK', 'description' => 'Himpunan Mahasiswa Kimia']);
        $hma = Unit::create(['parent_id' => $jurusanAkuntansi->id, 'level' => 'Organisasi', 'unit_name' => 'HMA', 'description' => 'Himpunan Mahasiswa Akuntansi']);
        $hman = Unit::create(['parent_id' => $jurusanAN->id, 'level' => 'Organisasi', 'unit_name' => 'HMAN', 'description' => 'Himpunan Mahasiswa Administrasi Niaga']);

        // ═══════════════════════════════════════════════════════
        // STEP 3: SEED POSITIONS (jabatan per unit)
        // Approver menggunakan position_id untuk identifikasi
        // ═══════════════════════════════════════════════════════

        // Jabatan di Pusat
        $posWadir2 = Position::create(['unit_id' => $pusat->id,       'name' => 'Wakil Direktur II']);
        $posWadir3 = Position::create(['unit_id' => $pusat->id,       'name' => 'Wakil Direktur III']);

        // Jabatan di Jurusan TI
        $posKajurTI = Position::create(['unit_id' => $jurusanTI->id,    'name' => 'Ketua Jurusan TI']);
        $posKaprodiTI = Position::create(['unit_id' => $jurusanTI->id,   'name' => 'Kaprodi TI']);

        // Jabatan di Jurusan Lainnya
        $posKajurSipil = Position::create(['unit_id' => $jurusanSipil->id, 'name' => 'Ketua Jurusan Sipil']);
        $posKajurElektro = Position::create(['unit_id' => $jurusanElektro->id, 'name' => 'Ketua Jurusan Elektro']);
        $posKajurMesin = Position::create(['unit_id' => $jurusanMesin->id, 'name' => 'Ketua Jurusan Mesin']);
        $posKajurKimia = Position::create(['unit_id' => $jurusanKimia->id, 'name' => 'Ketua Jurusan Kimia']);
        $posKajurAkuntansi = Position::create(['unit_id' => $jurusanAkuntansi->id, 'name' => 'Ketua Jurusan Akuntansi']);
        $posKajurAN = Position::create(['unit_id' => $jurusanAN->id, 'name' => 'Ketua Jurusan Administrasi Niaga']);

        // Jabatan organisasi (untuk kelola data lokal)
        $posPresBEM = Position::create(['unit_id' => $bemPusat->id, 'name' => 'Presiden BEM Polinema']);
        $posKetuaDPM = Position::create(['unit_id' => $dpmPusat->id, 'name' => 'Ketua Dewan Perwakilan Mahasiswa']);
        $posKetuaFormadiksi = Position::create(['unit_id' => $formadiksi->id, 'name' => 'Ketua Formadiksi']);
        $posHumasHMTI = Position::create(['unit_id' => $hmti->id, 'name' => 'Humas HMTI']);
        $posKetHMTI = Position::create(['unit_id' => $hmti->id, 'name' => 'Ketua HMTI']);
        $posPembinaHMTI = Position::create(['unit_id' => $hmti->id, 'name' => 'Pembina HMTI']);
        $posHumasWRI = Position::create(['unit_id' => $wri->id, 'name' => 'Humas WRI']);
        $posKetuaWRI = Position::create(['unit_id' => $wri->id, 'name' => 'Ketua WRI']);
        $posPembinaWRI = Position::create(['unit_id' => $wri->id, 'name' => 'Pembina WRI']);
        $posKetHMS = Position::create(['unit_id' => $hms->id, 'name' => 'Ketua HMS']);
        $posKetHME = Position::create(['unit_id' => $hme->id, 'name' => 'Ketua HME']);
        $posKetHMM = Position::create(['unit_id' => $hmm->id, 'name' => 'Ketua HMM']);
        $posKetHMK = Position::create(['unit_id' => $hmk->id, 'name' => 'Ketua HMK']);
        $posKetHMA = Position::create(['unit_id' => $hma->id, 'name' => 'Ketua HMA']);
        $posKetHMAN = Position::create(['unit_id' => $hman->id, 'name' => 'Ketua HMAN']);

        // ═══════════════════════════════════════════════════════
        // STEP 4: SEED BUILDINGS & ROOMS
        // ═══════════════════════════════════════════════════════

        // Daftar Gedung
        $gedungTI = Building::create(['building_name' => 'Gedung Teknologi Informasi']);
        $gedungSipil = Building::create(['building_name' => 'Gedung Teknik Sipil']);
        $gedungElektro = Building::create(['building_name' => 'Gedung Teknik Elektro']);
        $gedungMesin = Building::create(['building_name' => 'Gedung Teknik Mesin']);
        $gedungKimia = Building::create(['building_name' => 'Gedung Teknik Kimia']);
        $gedungAkuntansi = Building::create(['building_name' => 'Gedung Akuntansi']);
        $gedungAN = Building::create(['building_name' => 'Gedung Administrasi Niaga']);
        $graha = Building::create(['building_name' => 'Graha Polinema']);

        // Ruangan di Gedung TI
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

        // Ruangan 1 untuk Gedung lainnya
        Room::create([
            'building_id' => $gedungSipil->id,
            'unit_id' => $jurusanSipil->id,
            'room_name' => 'Ruang Kelas Sipil-101',
            'capacity' => 40,
            'description' => 'Ruang kelas reguler Sipil',
        ]);
        Room::create([
            'building_id' => $gedungElektro->id,
            'unit_id' => $jurusanElektro->id,
            'room_name' => 'Ruang Kelas Elektro-101',
            'capacity' => 40,
            'description' => 'Ruang kelas reguler Elektro',
        ]);
        Room::create([
            'building_id' => $gedungMesin->id,
            'unit_id' => $jurusanMesin->id,
            'room_name' => 'Ruang Kelas Mesin-101',
            'capacity' => 40,
            'description' => 'Ruang kelas reguler Mesin',
        ]);
        Room::create([
            'building_id' => $gedungKimia->id,
            'unit_id' => $jurusanKimia->id,
            'room_name' => 'Ruang Kelas Kimia-101',
            'capacity' => 40,
            'description' => 'Ruang kelas reguler Kimia',
        ]);
        Room::create([
            'building_id' => $gedungAkuntansi->id,
            'unit_id' => $jurusanAkuntansi->id,
            'room_name' => 'Ruang Kelas Akuntansi-101',
            'capacity' => 40,
            'description' => 'Ruang kelas reguler Akuntansi',
        ]);
        Room::create([
            'building_id' => $gedungAN->id,
            'unit_id' => $jurusanAN->id,
            'room_name' => 'Ruang Kelas AN-101',
            'capacity' => 40,
            'description' => 'Ruang kelas reguler Administrasi Niaga',
        ]);

        // Graha Polinema (milik Pusat)
        Room::create([
            'building_id' => $graha->id,
            'unit_id' => $pusat->id,
            'room_name' => 'Graha Polinema',
            'capacity' => 1000,
            'description' => 'Gedung Graha Polinema untuk kegiatan besar',
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

        // 2 Admin Unit (masing-masing jurusan)
        $jurusans = [
            ['unit' => $jurusanTI, 'name' => 'TI', 'email' => 'admin.ti'],
            ['unit' => $jurusanSipil, 'name' => 'Sipil', 'email' => 'admin.sipil'],
            ['unit' => $jurusanElektro, 'name' => 'Elektro', 'email' => 'admin.elektro'],
            ['unit' => $jurusanMesin, 'name' => 'Mesin', 'email' => 'admin.mesin'],
            ['unit' => $jurusanKimia, 'name' => 'Kimia', 'email' => 'admin.kimia'],
            ['unit' => $jurusanAkuntansi, 'name' => 'Akuntansi', 'email' => 'admin.akuntansi'],
            ['unit' => $jurusanAN, 'name' => 'Administrasi Niaga', 'email' => 'admin.an'],
        ];

        foreach ($jurusans as $j) {
            User::create([
                'unit_id' => $j['unit']->id,
                'position_id' => null,
                'role_id' => $roleAdminUnit->id,
                'name' => 'Admin Jurusan '.$j['name'],
                'email' => $j['email'].'@spacein.test',
                'password' => Hash::make('12345'),
            ]);
        }

        // Admin Unit untuk masing-masing Organisasi
        $orgs = [
            ['unit' => $bemPusat, 'name' => 'BEM Polinema', 'email' => 'admin.bem'],
            ['unit' => $dpmPusat, 'name' => 'Dewan Perwakilan Mahasiswa', 'email' => 'admin.dpm'],
            ['unit' => $formadiksi, 'name' => 'Formadiksi', 'email' => 'admin.formadiksi'],
            ['unit' => $hmti, 'name' => 'HMTI', 'email' => 'admin.hmti'],
            ['unit' => $wri, 'name' => 'WRI', 'email' => 'admin.wri'],
            ['unit' => $hms, 'name' => 'HMS', 'email' => 'admin.hms'],
            ['unit' => $hme, 'name' => 'HME', 'email' => 'admin.hme'],
            ['unit' => $hmm, 'name' => 'HMM', 'email' => 'admin.hmm'],
            ['unit' => $hmk, 'name' => 'HMK', 'email' => 'admin.hmk'],
            ['unit' => $hma, 'name' => 'HMA', 'email' => 'admin.hma'],
            ['unit' => $hman, 'name' => 'HMAN', 'email' => 'admin.hman'],
        ];

        foreach ($orgs as $o) {
            User::create([
                'unit_id' => $o['unit']->id,
                'position_id' => null,
                'role_id' => $roleAdminUnit->id,
                'name' => 'Admin '.$o['name'],
                'email' => $o['email'].'@spacein.test',
                'password' => Hash::make('12345'),
            ]);
        }

        // Admin Unit untuk Admin Pusat
        User::create([
            'unit_id' => $pusat->id,
            'position_id' => null,
            'role_id' => $roleAdminUnit->id,
            'name' => 'Admin Pusat',
            'email' => 'admin.pusat@spacein.test',
            'password' => Hash::make('12345'),
        ]);

        // 3 Approver Utama (Digunakan di testing)
        User::create([
            'unit_id' => $jurusanTI->id,
            'position_id' => $posKajurTI->id,
            'role_id' => $roleApprover->id,
            'name' => 'Dr. Budi Santoso',
            'email' => 'kajur.ti@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $pusat->id,
            'position_id' => $posWadir3->id,
            'role_id' => $roleApprover->id,
            'name' => 'Dr. Siti Rahayu',
            'email' => 'wadir@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $pusat->id,
            'position_id' => $posWadir2->id,
            'role_id' => $roleApprover->id,
            'name' => 'Dr. Ahmad Subagyo',
            'email' => 'wadir2@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $hmti->id,
            'position_id' => $posHumasHMTI->id,
            'role_id' => $roleApprover->id,
            'name' => 'Humas HMTI',
            'email' => 'humas.hmti@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $hmti->id,
            'position_id' => $posPembinaHMTI->id,
            'role_id' => $roleApprover->id,
            'name' => 'Pembina HMTI',
            'email' => 'pembina.hmti@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $wri->id,
            'position_id' => $posHumasWRI->id,
            'role_id' => $roleApprover->id,
            'name' => 'Humas WRI',
            'email' => 'humas.wri@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $wri->id,
            'position_id' => $posPembinaWRI->id,
            'role_id' => $roleApprover->id,
            'name' => 'Pembina WRI',
            'email' => 'pembina.wri@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $jurusanTI->id,
            'position_id' => $posKaprodiTI->id,
            'role_id' => $roleApprover->id,
            'name' => 'Ir. Agus Wijaya',
            'email' => 'kaprodi.ti@spacein.test',
            'password' => Hash::make('12345'),
        ]);

        // Approver Tambahan untuk masing-masing Jurusan
        $kajurs = [
            ['unit' => $jurusanSipil, 'pos' => $posKajurSipil, 'name' => 'Sipil', 'email' => 'sipil'],
            ['unit' => $jurusanElektro, 'pos' => $posKajurElektro, 'name' => 'Elektro', 'email' => 'elektro'],
            ['unit' => $jurusanMesin, 'pos' => $posKajurMesin, 'name' => 'Mesin', 'email' => 'mesin'],
            ['unit' => $jurusanKimia, 'pos' => $posKajurKimia, 'name' => 'Kimia', 'email' => 'kimia'],
            ['unit' => $jurusanAkuntansi, 'pos' => $posKajurAkuntansi, 'name' => 'Akuntansi', 'email' => 'akuntansi'],
            ['unit' => $jurusanAN, 'pos' => $posKajurAN, 'name' => 'Administrasi Niaga', 'email' => 'an'],
        ];
        foreach ($kajurs as $k) {
            User::create([
                'unit_id' => $k['unit']->id,
                'position_id' => $k['pos']->id,
                'role_id' => $roleApprover->id,
                'name' => 'Ketua Jurusan '.$k['name'],
                'email' => 'kajur.'.$k['email'].'@spacein.test',
                'password' => Hash::make('12345'),
            ]);
        }

        // Approver Tambahan untuk masing-masing Organisasi
        $ketuas = [
            ['unit' => $bemPusat, 'pos' => $posPresBEM, 'name' => 'BEM Polinema', 'email' => 'bem'],
            ['unit' => $dpmPusat, 'pos' => $posKetuaDPM, 'name' => 'Dewan Perwakilan Mahasiswa', 'email' => 'dpm'],
            ['unit' => $formadiksi, 'pos' => $posKetuaFormadiksi, 'name' => 'Formadiksi', 'email' => 'formadiksi'],
            ['unit' => $hmti, 'pos' => $posKetHMTI, 'name' => 'HMTI', 'email' => 'hmti'],
            ['unit' => $wri, 'pos' => $posKetuaWRI, 'name' => 'WRI', 'email' => 'wri'],
            ['unit' => $hms, 'pos' => $posKetHMS, 'name' => 'HMS', 'email' => 'hms'],
            ['unit' => $hme, 'pos' => $posKetHME, 'name' => 'HME', 'email' => 'hme'],
            ['unit' => $hmm, 'pos' => $posKetHMM, 'name' => 'HMM', 'email' => 'hmm'],
            ['unit' => $hmk, 'pos' => $posKetHMK, 'name' => 'HMK', 'email' => 'hmk'],
            ['unit' => $hma, 'pos' => $posKetHMA, 'name' => 'HMA', 'email' => 'hma'],
            ['unit' => $hman, 'pos' => $posKetHMAN, 'name' => 'HMAN', 'email' => 'hman'],
        ];
        foreach ($ketuas as $k) {
            User::create([
                'unit_id' => $k['unit']->id,
                'position_id' => $k['pos']->id,
                'role_id' => $roleApprover->id,
                'name' => 'Ketua '.$k['name'],
                'email' => 'ketua.'.$k['email'].'@spacein.test',
                'password' => Hash::make('12345'),
            ]);
        }

        // User biasa (peminjam) — Main Test Accounts
        User::create([
            'unit_id' => $hmti->id,
            'position_id' => null,
            'role_id' => $roleUser->id,
            'name' => 'Andi Mahasiswa TI',
            'email' => 'user@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $hms->id,
            'position_id' => null,
            'role_id' => $roleUser->id,
            'name' => 'Budi Mahasiswa Sipil',
            'email' => 'budi@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $hme->id,
            'position_id' => null,
            'role_id' => $roleUser->id,
            'name' => 'Citra Mahasiswi Elektro',
            'email' => 'citra@spacein.test',
            'password' => Hash::make('12345'),
        ]);
        User::create([
            'unit_id' => $wri->id,
            'position_id' => null,
            'role_id' => $roleUser->id,
            'name' => 'Doni Mahasiswa WRI',
            'email' => 'user.wri@spacein.test',
            'password' => Hash::make('12345'),
        ]);

        // User biasa tambahan untuk Organisasi dan Jurusan
        foreach ($jurusans as $j) {
            User::create([
                'unit_id' => $j['unit']->id,
                'position_id' => null,
                'role_id' => $roleUser->id,
                'name' => 'Staf Jurusan '.$j['name'],
                'email' => 'staf.'.$j['email'].'@spacein.test',
                'password' => Hash::make('12345'),
            ]);
        }
        foreach ($ketuas as $k) {
            if (! in_array($k['name'], ['HMTI', 'HMS', 'HME'])) {
                User::create([
                    'unit_id' => $k['unit']->id,
                    'position_id' => null,
                    'role_id' => $roleUser->id,
                    'name' => 'Mahasiswa '.$k['name'],
                    'email' => 'mhs.'.$k['email'].'@spacein.test',
                    'password' => Hash::make('12345'),
                ]);
            }
        }

        // ═══════════════════════════════════════════════════════
        // STEP 6: SEED WORKFLOWS & WORKFLOW STEPS
        // Contoh: Peminjaman JTI = Kajur -> Wadir
        // ═══════════════════════════════════════════════════════

        // Workflow untuk peminjaman ruangan Jurusan TI
        $wfJTI = Workflow::create([
            'unit_id' => $jurusanTI->id,
            'name' => 'Peminjaman JTI',
            'description' => 'Alur persetujuan peminjaman ruangan Jurusan Teknologi Informasi',
        ]);

        // Urutan: Kajur (Jurusan) (Tanpa Wadir karena Pusat di-append dinamis)
        WorkflowStep::create([
            'workflow_id' => $wfJTI->id,
            'position_id' => $posKajurTI->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);

        // Tambahkan syarat dokumen untuk Peminjaman JTI (Kewajiban Peminjam)
        WorkflowRequirement::create([
            'workflow_id' => $wfJTI->id,
            'document_name' => 'Proposal Kegiatan',
            'is_mandatory' => true,
        ]);
        WorkflowRequirement::create([
            'workflow_id' => $wfJTI->id,
            'document_name' => 'Surat Disposisi Wadir',
            'is_mandatory' => true,
        ]);

        // Workflow untuk peminjaman Graha Polinema (milik Pusat)
        $wfAuditorium = Workflow::create([
            'unit_id' => $pusat->id,
            'name' => 'Peminjaman Graha Polinema',
            'description' => 'Alur persetujuan peminjaman Graha Polinema',
        ]);

        // Urutan jika Pusat adalah Pemilik Ruang: Wakil Direktur II -> Wakil Direktur III
        WorkflowStep::create([
            'workflow_id' => $wfAuditorium->id,
            'position_id' => $posWadir2->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);

        WorkflowStep::create([
            'workflow_id' => $wfAuditorium->id,
            'position_id' => $posWadir3->id,
            'step_order' => 2,
            'requires_attachment' => false,
        ]);

        // Tambahkan syarat dokumen untuk Peminjaman Auditorium
        WorkflowRequirement::create([
            'workflow_id' => $wfAuditorium->id,
            'document_name' => 'Surat Izin Orang Tua/Wali',
            'is_mandatory' => true,
        ]);
        WorkflowRequirement::create([
            'workflow_id' => $wfAuditorium->id,
            'document_name' => 'Surat Disposini Wadir',
            'is_mandatory' => true,
        ]);

        // Workflow umum untuk BEM Polinema (Tier 2a)
        $wfBEM = Workflow::create([
            'unit_id' => $bemPusat->id,
            'name' => 'Alur BEM Polinema',
            'description' => 'Alur umum persetujuan tingkat ormawa pusat',
        ]);
        WorkflowStep::create([
            'workflow_id' => $wfBEM->id,
            'position_id' => $posPresBEM->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);
        WorkflowRequirement::create([
            'workflow_id' => $wfBEM->id,
            'document_name' => 'Proposal Kegiatan (BEM)',
            'is_mandatory' => true,
        ]);

        // Workflow umum untuk HMTI (Tier 1)
        $wfHMTI = Workflow::create([
            'unit_id' => $hmti->id,
            'name' => 'Alur HMTI',
            'description' => 'Alur umum persetujuan tingkat himpunan TI',
        ]);
        WorkflowStep::create([
            'workflow_id' => $wfHMTI->id,
            'position_id' => $posHumasHMTI->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);
        WorkflowStep::create([
            'workflow_id' => $wfHMTI->id,
            'position_id' => $posKetHMTI->id,
            'step_order' => 2,
            'requires_attachment' => false,
        ]);
        WorkflowRequirement::create([
            'workflow_id' => $wfHMTI->id,
            'document_name' => 'Proposal Kegiatan (HMTI)',
            'is_mandatory' => true,
        ]);

        // Workflow umum untuk Formadiksi (Tier 1)
        $wfFormadiksi = Workflow::create([
            'unit_id' => $formadiksi->id,
            'name' => 'Alur Formadiksi',
            'description' => 'Alur umum persetujuan tingkat Formadiksi',
        ]);
        WorkflowStep::create([
            'workflow_id' => $wfFormadiksi->id,
            'position_id' => $posKetuaFormadiksi->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);
        WorkflowRequirement::create([
            'workflow_id' => $wfFormadiksi->id,
            'document_name' => 'Proposal Kegiatan (Formadiksi)',
            'is_mandatory' => true,
        ]);

        // Workflow umum untuk WRI (Tier 1)
        $wfWRI = Workflow::create([
            'unit_id' => $wri->id,
            'name' => 'Alur WRI',
            'description' => 'Alur umum persetujuan tingkat Workshop Riset Informatika',
        ]);
        WorkflowStep::create([
            'workflow_id' => $wfWRI->id,
            'position_id' => $posHumasWRI->id,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);
        WorkflowStep::create([
            'workflow_id' => $wfWRI->id,
            'position_id' => $posKetuaWRI->id,
            'step_order' => 2,
            'requires_attachment' => false,
        ]);
        WorkflowRequirement::create([
            'workflow_id' => $wfWRI->id,
            'document_name' => 'Proposal Kegiatan (WRI)',
            'is_mandatory' => true,
        ]);
    }
}
