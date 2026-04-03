<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Unit;
use App\Models\Position;
use App\Models\Building;
use App\Models\Room;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. BUAT ROLE (Kolom Migration: name)
        $roles = ['SuperAdmin', 'Admin_Unit', 'Approver', 'User'];
        $roleIds = [];
        foreach ($roles as $roleName) {
            $roleIds[$roleName] = Role::create(['name' => $roleName])->id;
        }

        // 2. BUAT UNIT (Kolom Migration: unit_name, level)
        $pusat = Unit::create([
            'unit_name' => 'Politeknik Negeri Malang (Pusat)',
            'level' => 'Pusat'
        ]);

        $jurusans = ['Jurusan Teknologi Informasi', 'Jurusan Teknik Sipil', 'Jurusan Akuntansi'];
        $jurusanIds = [];
        foreach ($jurusans as $namaJurusan) {
            $jurusan = Unit::create([
                'unit_name' => $namaJurusan,
                'parent_id' => $pusat->id,
                'level' => 'Jurusan'
            ]);
            $jurusanIds[] = $jurusan->id;

            Unit::create(['unit_name' => 'BEM ' . $namaJurusan, 'parent_id' => $jurusan->id, 'level' => 'Organisasi']);
            Unit::create(['unit_name' => 'Himpunan ' . $namaJurusan, 'parent_id' => $jurusan->id, 'level' => 'Organisasi']);
        }

        // 3. BUAT POSITION (Kolom Migration: name, unit_id)
        // Factory ini butuh Unit, jadi kita jalankan setelah Unit ada
        Position::factory(5)->create();
        $positions = Position::all();

        // 4. SEED GEDUNG (Dibutuhkan sebelum Room & User)
        Building::factory(5)->create();

        // 5. SEED AKUN USER
        $password = Hash::make('password');

        User::create([
            'name' => 'Bapak SuperAdmin', 
            'email' => 'superadmin@space.in',
            'password' => $password, 
            'role_id' => $roleIds['SuperAdmin'],
            'unit_id' => $pusat->id, 
            'position_id' => $positions->first()->id,
        ]);

        User::create([
            'name' => 'Admin TI', 
            'email' => 'adminti@space.in',
            'password' => $password, 
            'role_id' => $roleIds['Admin_Unit'],
            'unit_id' => $jurusanIds[0], 
            'position_id' => $positions->random()->id,
        ]);

        // Buat beberapa user random dari Factory
        User::factory(5)->create();

        // 6. SEED RUANGAN (Terakhir karena butuh Building & Unit)
        Room::factory(10)->create();

        // 7. SEED WORKFLOW & STEPS SECARA LOGIS (Bukan Random)
        // Pastikan model Workflow dan WorkflowStep sudah di-import di atas (use App\Models\Workflow;)

        // Contoh Kasus 1: Alur Peminjaman Ruangan di Jurusan
        $workflowJurusan = \App\Models\Workflow::create([
            'unit_id' => $jurusanIds[0], // Milik Jurusan TI
            'name' => 'Alur Standar Peminjaman Ruang Jurusan',
            'description' => 'Persetujuan berjenjang dari Admin hingga Ketua Jurusan',
        ]);

        // Rantai Persetujuan (Workflow Steps) untuk Alur Jurusan
        // Step 1: Staff Administrasi (Cek jadwal)
        \App\Models\WorkflowStep::create([
            'workflow_id' => $workflowJurusan->id,
            'position_id' => \App\Models\Position::where('name', 'Staff Administrasi')->first()->id ?? 1,
            'step_order' => 1,
            'requires_attachment' => false, 
        ]);

        // Step 2: Ketua Jurusan (Persetujuan Final & TTD)
        // Di blueprint disebutkan approver bisa butuh dokumen balasan/dispensasi
        \App\Models\WorkflowStep::create([
            'workflow_id' => $workflowJurusan->id,
            'position_id' => \App\Models\Position::where('name', 'Ketua Jurusan')->first()->id ?? 2,
            'step_order' => 2,
            'requires_attachment' => true, // Harus upload surat balasan sesuai Blueprint
        ]);

        // Contoh Kasus 2: Alur Peminjaman Gedung Pusat (Misal: Auditorium)
        $workflowPusat = \App\Models\Workflow::create([
            'unit_id' => $pusat->id, // Milik Pusat Polinema
            'name' => 'Alur Peminjaman Fasilitas Pusat',
            'description' => 'Peminjaman gedung besar yang butuh ACC lebih banyak',
        ]);

        // Step 1: Admin Pusat
        \App\Models\WorkflowStep::create([
            'workflow_id' => $workflowPusat->id,
            'position_id' => \App\Models\Position::where('name', 'Staff Administrasi')->first()->id ?? 1,
            'step_order' => 1,
            'requires_attachment' => false,
        ]);
        
        // Step 2: Kepala Pusat/Direktorat
        \App\Models\WorkflowStep::create([
            'workflow_id' => $workflowPusat->id,
            'position_id' => \App\Models\Position::where('name', 'Kepala Program Studi')->first()->id ?? 3, // Disesuaikan posisi yang ada
            'step_order' => 2,
            'requires_attachment' => true,
        ]);
    }
}