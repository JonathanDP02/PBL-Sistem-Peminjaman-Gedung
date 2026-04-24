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
| Admin Jurusan TI      | admin.ti@spacein.test   | Admin_Unit | Jurusan Teknologi Informasi|
| Admin Jurusan Sipil   | admin.sipil@spacein.test| Admin_Unit | Jurusan Teknik Sipil       |

### Approver
| Nama              | Email                   | Role     | Posisi          | Unit           |
|-------------------|-------------------------|----------|-----------------|----------------|
| Dr. Budi Santoso  | kajur.ti@spacein.test   | Approver | Ketua Jurusan TI| Jurusan TI     |
| Dr. Siti Rahayu   | wadir@spacein.test      | Approver | Wakil Direktur  | Pusat          |
| Ir. Agus Wijaya   | kaprodi.ti@spacein.test | Approver | Kaprodi TI      | Jurusan TI     |

### User Biasa (Peminjam)
| Nama                    | Email              | Role | Unit         |
|-------------------------|--------------------|------|--------------|
| Andi Mahasiswa TI       | user@spacein.test  | User | HMTI         |
| Budi Mahasiswa Sipil    | budi@spacein.test  | User | BEM Sipil    |
| Citra Mahasiswi Elektro | citra@spacein.test | User | HM Elektro   |

## Hierarki Unit
Pusat (Politeknik Negeri Malang)
├── Jurusan Teknologi Informasi
│   ├── HMTI
│   └── BEM TI
├── Jurusan Teknik Sipil
│   ├── HM Sipil
│   └── BEM Sipil
└── Jurusan Teknik Elektro
├── HM Elektro
└── BEM Elektro

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

