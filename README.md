# Space.in - Sistem Peminjaman Gedung dan Ruangan

Sistem informasi manajemen peminjaman gedung dan ruangan berbasis web yang dirancang untuk menangani siklus peminjaman fasilitas secara end-to-end, mulai dari pengajuan oleh pengguna, manajemen lampiran persyaratan, hingga sistem persetujuan (approval workflow) bertingkat oleh pihak berwenang.

## 👥 Tim Pengembang

**Institusi:** Politeknik Negeri Malang | **Program Studi:** Teknologi Informasi

| Nama Lengkap | NIM | Kelas |
|---|---|---|
| Febri | [NIM Anggota 1] | TI-2F |
| Jonathan | [NIM Anggota 2] | TI-2F |
| Nabhan Rizqi Julian Saputro | 2341720255 | TI-2F |
| Otavia | [NIM Anggota 4] | TI-2F |

## ✨ Fitur Utama (Core Features)

- **Manajemen Autentikasi & Otorisasi:** Sistem login dengan pembagian hak akses (Role-Based Access Control) yang ketat.
- **Manajemen Fasilitas:** Pengelolaan data master Gedung (`Buildings`) dan Ruangan (`Rooms`).
- **Sistem Pengajuan (Booking):** Perekaman data permohonan peminjaman beserta lampiran dokumen persyaratan (`Booking Attachments`).
- **Workflow Persetujuan Bertingkat:** Alur validasi dinamis (`Workflows`, `Workflow Steps`) yang memastikan setiap peminjaman disetujui oleh posisi atau unit yang tepat sebelum dieksekusi (`Approvals`).

## 🛠️ Stack Teknologi

- **Framework:** Laravel 12.x
- **Bahasa:** PHP >= 8.2
- **Frontend:** HTML, TailwindCSS, Blade Templating
- **Database:** MySQL / PostgreSQL (Pilih salah satu sesuai aslinya)

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
# - DB_DATABASE
# - DB_USERNAME
# - DB_PASSWORD

# 6. Jalankan Migrasi dan Seeder
php artisan migrate --seed

# 7. Jalankan Local Server
php artisan serve
```

## 📚 Dokumentasi & Laporan
Log kemajuan dan laporan teknis dari implementasi modul sistem ini dapat diakses pada tautan berikut:
[📄 Laporan Implementasi Login, Auth, dan Middleware(Sementara)](report/REPORT.md)

## 📄 Lisensi
Proyek ini dikembangkan untuk kebutuhan akademik (Project-Based Learning). Kode inti kerangka kerja Laravel dilisensikan di bawah MIT license.