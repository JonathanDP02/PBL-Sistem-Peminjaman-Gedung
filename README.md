# Space.in - Sistem Peminjaman Gedung dan Ruangan

Sistem informasi manajemen peminjaman gedung dan ruangan berbasis web yang dirancang untuk menangani siklus peminjaman fasilitas secara end-to-end, mulai dari pengajuan oleh pengguna, manajemen lampiran persyaratan, hingga sistem persetujuan (approval workflow) bertingkat oleh pihak berwenang.

## 👥 Tim Pengembang

**Institusi:** Politeknik Negeri Malang | **Program Studi:** Teknologi Informasi

| Nama Lengkap | NIM | Kelas |
|---|---|---|
| Febri | 244107020199 | TI-2F |
| Jonathan | 244107020197 | TI-2F |
| Nabhan Rizqi Julian Saputro | 2341720255 | TI-2F |
| Otavia | 244107020053 | TI-2F |

## ✨ Fitur Utama (Core Features)

- **Manajemen Autentikasi & Otorisasi:** Sistem login dengan pembagian hak akses (Role-Based Access Control) yang ketat.
- **Manajemen Fasilitas:** Pengelolaan data master Gedung (`Buildings`) dan Ruangan (`Rooms`).
- **Sistem Pengajuan (Booking):** Perekaman data permohonan peminjaman beserta lampiran dokumen persyaratan (`Booking Attachments`).
- **Workflow Persetujuan Bertingkat:** Alur validasi dinamis (`Workflows`, `Workflow Steps`) yang memastikan setiap peminjaman disetujui oleh posisi atau unit yang tepat sebelum dieksekusi (`Approvals`).

## 🛠️ Stack Teknologi

- **Framework:** Laravel 12.x
- **Bahasa:** PHP >= 8.2
- **Frontend:** HTML, TailwindCSS, Blade Templating
- **Database:** PostgreSQL

## 📦 Instalasi & Konfigurasi

Ikuti langkah berikut untuk menjalankan proyek ini di lingkungan lokal (Development):

```bash
# 1. Clone repositori
git clone https://github.com/JonathanDP02/PBL-Sistem-Peminjaman-Gedung
cd PBL-Sistem-Peminjaman-Gedung

# 2. Install dependensi PHP
composer install

# 3. Install dependensi Frontend
npm install
npm run build

# 4. Konfigurasi Environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi Database
# Edit file .env dan sesuaikan:
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD

# 6. Jalankan Migrasi dan Seeder
php artisan migrate --seed
# misal sudah ada , tapi blom dapet update migration dll, 
php artisan migrate:fresh --seed

# 7. Jalankan Local Server
php artisan serve
```

## 📚 Dokumentasi & Laporan
Log kemajuan dan laporan teknis dari implementasi modul sistem ini dapat diakses pada tautan berikut:

[📄 Laporan Implementasi Login, Auth, dan Middleware(Sementara)](report/REPORT.md)

## 📄 Lisensi
Proyek ini dikembangkan untuk kebutuhan akademik (Project-Based Learning). Kode inti kerangka kerja Laravel dilisensikan di bawah MIT license.

# Space.in — Sistem Peminjaman Gedung

## Akun Hasil Seeding

Semua akun menggunakan password: **`12345`**

### SuperAdmin
| Nama                     | Email                    | Role       | Unit  |
|--------------------------|--------------------------|------------|-------|
| Super Admin Politeknik   | superadmin@spacein.test  | SuperAdmin | Pusat |

### Admin Unit
| Nama                  | Email                   | Role       | Unit                       |
|-----------------------|-------------------------|------------|----------------------------|
| Admin Pusat           | admin.pusat@spacein.test| Admin_Unit | Admin Pusat                |
| Admin Jurusan TI      | admin.ti@spacein.test   | Admin_Unit | Jurusan Teknologi Informasi|
| Admin Jurusan Sipil   | admin.sipil@spacein.test| Admin_Unit | Jurusan Teknik Sipil       |
| Admin Jurusan Elektro | admin.elektro@spacein.test| Admin_Unit | Jurusan Teknik Elektro     |
| Admin Jurusan Mesin   | admin.mesin@spacein.test| Admin_Unit | Jurusan Teknik Mesin       |
| Admin Jurusan Kimia   | admin.kimia@spacein.test| Admin_Unit | Jurusan Teknik Kimia       |
| Admin Jurusan Akuntansi| admin.akuntansi@spacein.test| Admin_Unit | Jurusan Akuntansi        |
| Admin Jurusan Administrasi Niaga| admin.an@spacein.test| Admin_Unit | Jurusan Administrasi Niaga|
| Admin BEM Polinema    | admin.bem@spacein.test  | Admin_Unit | BEM Polinema               |
| Admin Dewan Perwakilan Mahasiswa| admin.dpm@spacein.test| Admin_Unit | Dewan Perwakilan Mahasiswa|
| Admin Formadiksi      | admin.formadiksi@spacein.test| Admin_Unit | Formadiksi                 |
| Admin UKM Olahraga    | admin.olahraga@spacein.test| Admin_Unit | UKM Olahraga               |
| Admin HMTI            | admin.hmti@spacein.test | Admin_Unit | HMTI                       |
| Admin WRI             | admin.wri@spacein.test  | Admin_Unit | Workshop Riset Informatika |
| Admin HMS             | admin.hms@spacein.test  | Admin_Unit | HMS                        |
| Admin HME             | admin.hme@spacein.test  | Admin_Unit | HME                        |
| Admin HMM             | admin.hmm@spacein.test  | Admin_Unit | HMM                        |
| Admin HMK             | admin.hmk@spacein.test  | Admin_Unit | HMK                        |
| Admin HMA             | admin.hma@spacein.test  | Admin_Unit | HMA                        |
| Admin HMAN            | admin.hman@spacein.test | Admin_Unit | HMAN                       |

### Approver
| Nama              | Email                   | Role     | Posisi          | Unit           |
|-------------------|-------------------------|----------|-----------------|----------------|
| Dr. Siti Rahayu   | wadir@spacein.test      | Approver | Wakil Direktur III  | Pusat          |
| Dr. Ahmad Subagyo | wadir2@spacein.test     | Approver | Wakil Direktur II   | Pusat          |
| Dr. Budi Santoso  | kajur.ti@spacein.test   | Approver | Ketua Jurusan TI| Jurusan TI     |
| Ir. Agus Wijaya   | kaprodi.ti@spacein.test | Approver | Kaprodi TI      | Jurusan TI     |
| Humas HMTI        | humas.hmti@spacein.test | Approver | Humas HMTI      | HMTI           |
| Ketua Jurusan Sipil | kajur.sipil@spacein.test | Approver | Ketua Jurusan Sipil | Jurusan Teknik Sipil |
| Ketua Jurusan Elektro | kajur.elektro@spacein.test | Approver | Ketua Jurusan Elektro | Jurusan Teknik Elektro |
| Ketua Jurusan Mesin | kajur.mesin@spacein.test | Approver | Ketua Jurusan Mesin | Jurusan Teknik Mesin |
| Ketua Jurusan Kimia | kajur.kimia@spacein.test | Approver | Ketua Jurusan Kimia | Jurusan Teknik Kimia |
| Ketua Jurusan Akuntansi | kajur.akuntansi@spacein.test | Approver | Ketua Jurusan Akuntansi | Jurusan Akuntansi |
| Ketua Jurusan Administrasi Niaga | kajur.an@spacein.test | Approver | Ketua Jurusan Administrasi Niaga | Jurusan Administrasi Niaga |
| DPK TI            | dpk.ti@spacein.test     | Approver | DPK TI           | Jurusan TI     |
| Humas WRI         | humas.wri@spacein.test  | Approver | Humas WRI       | WRI            |
| Ketua WRI         | ketua.wri@spacein.test  | Approver | Ketua WRI       | WRI            |
| DPK BEM           | dpk.bem@spacein.test    | Approver | DPK BEM         | BEM Polinema   |
| DPK DPM           | dpk.dpm@spacein.test    | Approver | DPK DPM         | Dewan Perwakilan Mahasiswa |
| DPK Formadiksi    | dpk.formadiksi@spacein.test| Approver | DPK Formadiksi | Formadiksi |
| DPK UKM Olahraga  | dpk.olahraga@spacein.test| Approver | DPK UKM Olahraga | UKM Olahraga |
| Ketua BEM Polinema| ketua.bem@spacein.test  | Approver | Presiden BEM Polinema | BEM Polinema |
| Ketua Dewan Perwakilan Mahasiswa| ketua.dpm@spacein.test| Approver | Ketua Dewan Perwakilan Mahasiswa | Dewan Perwakilan Mahasiswa |
| Ketua Formadiksi  | ketua.formadiksi@spacein.test | Approver | Ketua Formadiksi | Formadiksi |
| Ketua UKM Olahraga| ketua.olahraga@spacein.test | Approver | Ketua UKM Olahraga | UKM Olahraga |
| Ketua HMTI        | ketua.hmti@spacein.test | Approver | Ketua HMTI      | HMTI           |
| Ketua HMS         | ketua.hms@spacein.test  | Approver | Ketua HMS       | HMS            |
| Ketua HME         | ketua.hme@spacein.test  | Approver | Ketua HME       | HME            |
| Ketua HMM         | ketua.hmm@spacein.test  | Approver | Ketua HMM       | HMM            |
| Ketua HMK         | ketua.hmk@spacein.test  | Approver | Ketua HMK       | HMK            |
| Ketua HMA         | ketua.hma@spacein.test  | Approver | Ketua HMA       | HMA            |
| Ketua HMAN        | ketua.hman@spacein.test | Approver | Ketua HMAN      | HMAN           |

### User Biasa (Peminjam)
| Nama                    | Email              | Role | Unit         |
|-------------------------|--------------------|------|--------------|
| Andi Mahasiswa TI       | user@spacein.test  | User | HMTI         |
| Doni Mahasiswa WRI      | user.wri@spacein.test | User | Workshop Riset Informatika |
| Budi Mahasiswa Sipil    | budi@spacein.test  | User | HMS          |
| Citra Mahasiswi Elektro | citra@spacein.test | User | HME          |
| Staf Jurusan TI         | staf.ti@spacein.test | User | Jurusan Teknologi Informasi |
| Staf Jurusan Sipil      | staf.sipil@spacein.test | User | Jurusan Teknik Sipil |
| Staf Jurusan Elektro    | staf.elektro@spacein.test | User | Jurusan Teknik Elektro |
| Staf Jurusan Mesin      | staf.mesin@spacein.test | User | Jurusan Teknik Mesin |
| Staf Jurusan Kimia      | staf.kimia@spacein.test | User | Jurusan Teknik Kimia |
| Staf Jurusan Akuntansi  | staf.akuntansi@spacein.test | User | Jurusan Akuntansi |
| Staf Jurusan Administrasi Niaga | staf.an@spacein.test | User | Jurusan Administrasi Niaga |
| Mahasiswa BEM Polinema  | mhs.bem@spacein.test | User | BEM Polinema |
| Mahasiswa Dewan Perwakilan Mahasiswa| mhs.dpm@spacein.test | User | Dewan Perwakilan Mahasiswa |
| Mahasiswa Formadiksi    | mhs.formadiksi@spacein.test | User | Formadiksi |
| Mahasiswa UKM Olahraga  | mhs.olahraga@spacein.test | User | UKM Olahraga |
| Mahasiswa HMM           | mhs.hmm@spacein.test | User | HMM          |
| Mahasiswa HMK           | mhs.hmk@spacein.test | User | HMK          |
| Mahasiswa HMA           | mhs.hma@spacein.test | User | HMA          |
| Mahasiswa HMAN          | mhs.hman@spacein.test | User | HMAN         |

## Hierarki Unit
Pusat (Politeknik Negeri Malang)
├── Admin Pusat
│   ├── BEM Polinema
│   │   ├── Formadiksi
│   │   └── UKM Olahraga
│   └── Dewan Perwakilan Mahasiswa
├── Jurusan Teknologi Informasi
│   └── HMTI
│       └── Workshop Riset Informatika (WRI)
├── Jurusan Teknik Sipil
│   └── HMS
├── Jurusan Teknik Elektro
│   └── HME
├── Jurusan Teknik Mesin
│   └── HMM
├── Jurusan Teknik Kimia
│   └── HMK
├── Jurusan Akuntansi
│   └── HMA
└── Jurusan Administrasi Niaga
    └── HMAN

---

# TRELLO JOBDESK: [Space.in - Sistem Peminjaman Gedung dan Ruangan ](https://trello.com/invite/b/64f7fc850d38f844873ae501/ATTI48479e318bdaa85df80babcab1571ff98A523CA3/penugasan) 

## 📊 Jobdesk Rencana Awal Pengembangan

**Nama:** Nabhan Rizqi JS 
**Focus Area:** PM & System Architect BE + Security

**Nama:** Muhammad Febri
**Focus Area:** Backend Data CRUD, File, PDF, Seeder

**Nama:** Otavia
**Focus Area:** Backend Core Model, Logika, Transaksi

**Nama:** Jonathan
**Focus Area:** Frontend Lead Views, AJAX

# 📋 Dokumentasi Progress Pengerjaan - Julian (rzjuliannofficial)
## Nabhan Rizqi Julian Saputro - NIM: 2341720255

**Project:** Space.in - Sistem Peminjaman Gedung dan Ruangan  
**Institusi:** Politeknik Negeri Malang | **Program Studi:** TI-2F  
**Periode:** Maret 2026 - April 2026

---

## 📊 Ringkasan Progress

**Total Commits:** 80+ commits  
**Status:** Aktif & Ongoing  
**Focus Area:** PM, Frontend UI/UX, Authentication, User Management, API Routes, & Documentation

## 🎯 Feature Utama yang Diimplementasikan Julian

1. ✅ **Authentication System** - Login/logout, session management, remember me (breeze)
2. ✅ **RBAC (Role-Based Access Control)** - 4 role types dengan authorization
3. ✅ **User Management** - CRUD operations, role & position assignment
4. ✅ **Facilities Management** - Building & room management
5. ✅ **Booking System** - Create booking, attachment upload, status tracking
6. ✅ **Workflow System** - Multi-step approval, workflow builder
7. ✅ **Approval Management** - Approval history, filtering, timeline
8. ✅ **Email Notifications** - BookingSubmittedMail, queue-based sending
9. ✅ **API Endpoints** - RESTful booking, room, workflow APIs
10. ✅ **Frontend UI/UX** - Responsive design, dark mode, modals
11. ✅ **Logging & Tracking** - BookingLog model, LoggerService untuk audit trail
12. ✅ **Documentation** - Backend docs, API documentation, case study, git guide

---


## 📈 Project Timeline Summary

| Periode | Fokus | Status |
|---------|-------|--------|
| Maret 1-2 | Setup & Foundation | ✅ |
| Maret 3-15 | Database & Models | ✅ |
| Maret 16-29 | Authentication & RBAC | ✅ |
| Maret 30-31 | Audit & Auth Flow | ✅ |
| April 1-7 | Frontend & Docs | ✅ |
| April 8-14 | Facilities & UI | ✅ |
| April 15-20 | API & Controllers | ✅ |
| April 21-23 | Advanced Features | ✅ |

---

# 📋 Dokumentasi Progress Pengerjaan - Jonathan (JonathanDP02)
## Jonathan - NIM: 244107020197

**Project:** Space.in - Sistem Peminjaman Gedung dan Ruangan  
**Institusi:** Politeknik Negeri Malang | **Program Studi:** TI-2F  
**Periode:** Maret 2026 - April 2026

---

## 📊 Ringkasan Progress

**Total Commits:** 6 commits  
**Status:** Aktif & Ongoing  
**Focus Area:** Frontend UI (User & Superadmin), Booking Detail View, Workflow Builder view, Room Blocking view

## 🎯 Feature Utama yang Diimplementasikan Jonathan

1. ✅ **Frontend Views untuk User Role** - Dashboard dan interface untuk user peminjam
2. ✅ **Frontend Views untuk Superadmin Role** - Dashboard dan interface untuk superadmin
3. ✅ **Pemblokiran Ruangan (Room Blocking)** - Fitur blokir ruangan dengan fungsionalitas dinamis
4. ✅ **Workflow Builder Pages** - Interface untuk membuat dan mengatur workflow approval
5. ✅ **Booking Detail View** - Halaman detail peminjaman dengan tracking status lengkap
6. ✅ **Status Tracking** - Implementasi tracking progress peminjaman real-time
7. ✅ **Dynamic Action Buttons** - Tombol aksi dinamis berdasarkan status booking
8. ✅ **UI Integration** - Integrasi tampilan dengan flow peminjaman system

---

## 📈 Project Timeline Summary

| Periode | Fokus | Status |
|---------|-------|--------|
| Mei 2 | Install laravel | ✅ |
| April 5 | frontend-user&superadmin | ✅ |
| April 13 | Room Blocking & Workflow Builder | ✅ |
| April 23 | Booking Detail & Status Tracking | ✅ |

---

**Total Commits Jonathan:** 6 commits  
**Focus Area:** Frontend UI/UX Development  
**Status:** Completed Major Features

---

---

# 📋 Dokumentasi Progress Pengerjaan - Febri (m1Febriansyah)
## Febri - NIM: 244107020199

**Project:** Space.in - Sistem Peminjaman Gedung dan Ruangan  
**Institusi:** Politeknik Negeri Malang | **Program Studi:** TI-2F  
**Periode:** Maret 2026 - April 2026

---

## 📊 Ringkasan Progress

**Total Commits:** 2+ commits  
**Status:** Aktif & Ongoing  
**Focus Area:** Database Factories, Seeders Setup, User Management, Initial Data

## 🎯 Feature Utama yang Diimplementasikan Febri

1. ✅ **Database Factories** - Factory classes untuk data generation testing
2. ✅ **Database Seeders** - Seeder untuk initial data population
3. ✅ **User Management Page** - Complete CRUD operations untuk user management
4. ✅ **Test Data Generation** - Setup untuk automated testing dengan factories
5. ✅ **Initial Database State** - Seeding data awal untuk development & testing
6. ✅ **Documentation Updates** - Update README dengan configuration details

---

## 📈 Project Timeline Summary

| Periode | Fokus | Status |
|---------|-------|--------|
| April 6 | Factory & Seeder Creation | ✅ |
| April 7-9 | Testing & Refinement | ✅ |
| April 10 | User Management Implementation | ✅ |

---

**Total Commits Febri:** 2+ commits  
**Focus Area:** Database Setup, User Management  

---

# 📋 Dokumentasi Progress Pengerjaan - Otavia (Otavia Ulandari)
## Otavia - NIM: 244107020053

**Project:** Space.in - Sistem Peminjaman Gedung dan Ruangan  
**Institusi:** Politeknik Negeri Malang | **Program Studi:** TI-2F  
**Periode:** Maret 2026 - April 2026

---

## 📊 Ringkasan Progress

**Total Commits:** 4+ commits  
**Status:** Aktif & Ongoing  
**Focus Area:** Backend Controllers, Conflict Resolution, Core Feature Support, Integration

## 🎯 Feature Utama yang Diimplementasikan Otavia

1. ✅ **Controller Development** - Setup & implementation backend logic controllers
2. ✅ **API Endpoint Support** - Controller methods untuk API endpoints
3. ✅ **Conflict Resolution** - Handling merge conflicts & integration issues
4. ✅ **Project Maintenance** - Weekly project updates & stabilization
5. ✅ **Features Integration** - Integrate features dari multiple branches
6. ✅ **Code Consolidation** - Consolidate code dari berbagai development branches

---

## 📈 Project Timeline Summary

| Periode | Fokus | Status |
|---------|-------|--------|
| April 6 | Controller Development | ✅ |
| April 7 | Conflict Resolution & Integration | ✅ |
| April 15-23 | Ongoing Maintenance | ✅ |

---

**Total Commits Otavia:** 3+ commits  
**Focus Area:** Backend Development, Integration
**Status:** Active in Core Development

---

**Last Updated:** April 24, 2026  
**Documentation Version:** 1.0

---

> 📌 Dokumentasi ini merangkum progress pengerjaan semua team members (Julian, Jonathan, Febri, Otavia) dalam project Space.in dari Maret hingga April 2026 berdasarkan analisis COMMIT GITHUB.

