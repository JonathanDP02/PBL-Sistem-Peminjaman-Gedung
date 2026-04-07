# Dokumentasi Backend — Sistem Peminjaman Gedung (SpaceIn)

## Daftar Isi

1. [Gambaran Proyek](#1-gambaran-proyek)
2. [Tech Stack](#2-tech-stack)
3. [Instalasi & Pengaturan](#3-instalasi--pengaturan)
4. [Konfigurasi Lingkungan](#4-konfigurasi-lingkungan)
5. [Skema Database](#5-skema-database)
6. [Model & Relasi](#6-model--relasi)
7. [Peran & Otorisasi](#7-peran--otorisasi)
8. [Middleware](#8-middleware)
9. [Rute](#9-rute)
10. [Controller](#10-controller)
11. [Form Requests (Validasi)](#11-form-requests-validasi)
12. [Layanan](#12-layanan)
13. [Helper](#13-helper)
14. [Database Seeder](#14-database-seeder)

---

## 1. Gambaran Proyek

**SpaceIn** adalah sistem manajemen peminjaman gedung/ruangan berbasis web yang dibangun untuk Politeknik Negeri Malang. Sistem ini memungkinkan unit akademik dan organisasi mahasiswa untuk mengirimkan, melacak, dan mengelola permintaan peminjaman ruangan melalui alur persetujuan multi-langkah yang dapat dikonfigurasi.

### Fitur Utama

- Penelusuran ruangan dan pengecekan ketersediaan
- Alur persetujuan multi-langkah (dapat dikonfigurasi per unit)
- Manajemen dokumen/lampiran per langkah peminjaman
- Kontrol akses berbasis peran (SuperAdmin, Admin_Unit, Approver, User)
- Jejak audit peminjaman
- Dukungan kode QR dan tanda tangan digital pada persetujuan

---

## 2. Tech Stack

| Lapisan | Teknologi |
|---|---|
| Bahasa | PHP 8.2+ |
| Framework | Laravel 12 |
| Database | PostgreSQL |
| Scaffolding Frontend | Laravel Breeze (Blade) |
| CSS | Tailwind CSS |
| Alat Build | Vite |
| Testing | PestPHP 4 |
| Antrian | Driver database |
| Cache | Driver database |
| Sesi | Driver database |

---

## 3. Instalasi & Pengaturan

### Prasyarat

- PHP 8.2+
- Composer
- Node.js & npm
- PostgreSQL

### Pengaturan Cepat (menggunakan Composer script)

```bash
composer run setup
```

Perintah ini menjalankan langkah-langkah berikut secara otomatis:
1. `composer install`
2. Menyalin `.env.example` → `.env` (jika belum ada)
3. `php artisan key:generate`
4. `php artisan migrate --force`
5. `npm install`
6. `npm run build`

### Menjalankan Lokal

```bash
composer run dev
```

Ini memulai tiga proses secara bersamaan:
- `php artisan serve` — Server pengembangan Laravel
- `php artisan queue:listen --tries=1` — Queue worker
- `npm run dev` — Pemantau aset Vite

### Menjalankan Tes

```bash
composer run test
# atau
php artisan test
```

---

## 4. Konfigurasi Lingkungan

Salin `.env.example` ke `.env` dan konfigurasi variabel-variabel kunci berikut:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=          # Dihasilkan via php artisan key:generate
APP_URL=http://localhost

# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pbl_sistem_peminjaman_gedung
DB_USERNAME=postgres
DB_PASSWORD=

# Session, Queue, Cache semuanya menggunakan driver database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

---

## 5. Skema Database

### Gambaran Relasi Entitas

```
roles ──< users >── units ──< positions
                       │
                       └──< rooms >── buildings
                       │
                       └──< workflows >──< workflow_steps >── positions
                                    └──< workflow_requirements
                       │
                       └──< bookings >──< approvals >── workflow_steps
                                    └──< booking_attachments >── workflow_requirements
                                    └──< booking_logs
```

### Tables

#### `roles`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| name | string | Pengenal peran (`SuperAdmin`, `Admin_Unit`, `User`, `Approver`) |
| description | string | Deskripsi yang dapat dibaca manusia |
| created_at / updated_at | timestamp | Cap waktu |

#### `units`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| parent_id | bigint FK (self) nullable | Unit induk (null = tingkat atas) |
| level | string | Tingkat hierarki: `Pusat`, `Jurusan`, `Organisasi` |
| unit_name | string | Nama unit |
| description | text nullable | Deskripsi |
| created_at / updated_at | timestamp | Cap waktu |

#### `positions`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| unit_id | bigint FK → units | Unit tempat posisi ini berada |
| name | string | Judul posisi (cth: Ketua Jurusan, Wakil Direktur) |
| created_at / updated_at | timestamp | Cap waktu |

#### `users`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| unit_id | bigint FK → units | Unit organisasi pengguna |
| position_id | bigint FK → positions nullable | Posisi pekerjaan (digunakan untuk Approver) |
| role_id | bigint FK → roles | Peran sistem pengguna |
| name | string(150) | Nama lengkap |
| email | string(150) unique | Alamat email |
| password | text | Kata sandi yang di-hash |
| created_at / updated_at | timestamp | Cap waktu |

#### `buildings`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| building_name | string | Nama gedung |
| location | string nullable | Deskripsi lokasi fisik |
| created_at / updated_at | timestamp | Cap waktu |

#### `rooms`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| building_id | bigint FK → buildings | Gedung tempat ruangan ini berada |
| unit_id | bigint FK → units | Unit yang memiliki/mengelola ruangan ini |
| room_name | string | Nama ruangan |
| capacity | integer | Kapasitas okupan maksimal |
| description | text nullable | Deskripsi ruangan |
| created_at / updated_at | timestamp | Cap waktu |

#### `workflows`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| unit_id | bigint FK → units | Unit tempat alur kerja ini berlaku |
| name | string(255) | Nama alur kerja |
| description | text nullable | Deskripsi |
| created_at / updated_at | timestamp | Cap waktu |

#### `workflow_steps`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| workflow_id | bigint FK → workflows | Alur kerja induk |
| position_id | bigint FK → positions | Posisi yang bertanggung jawab atas langkah ini |
| step_order | integer | Urutan dalam rantai persetujuan |
| requires_attachment | boolean | Apakah penyetuju harus mengunggah dokumen |
| created_at / updated_at | timestamp | Cap waktu |

#### `workflow_requirements`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| workflow_id | bigint FK → workflows | Alur kerja induk |
| document_name | string | Nama dokumen yang diperlukan |
| is_mandatory | boolean | Apakah dokumen ini wajib |
| created_at / updated_at | timestamp | Cap waktu |

#### `bookings`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| user_id | bigint FK → users (cascade) | Peminta |
| room_id | bigint FK → rooms (cascade) | Ruangan yang dipinjam |
| workflow_id | bigint FK → workflows | Alur kerja persetujuan yang harus diikuti |
| event_name | string(200) | Nama acara |
| event_description | text nullable | Deskripsi acara |
| booking_date | date | Tanggal peminjaman |
| start_time | time | Waktu mulai |
| end_time | time | Waktu berakhir |
| current_step | integer (default 1) | Langkah saat ini dalam alur kerja |
| status | string(50) (default `Pending`) | Status peminjaman |
| revision_count | integer (default 0) | Jumlah revisi yang diminta |
| created_at / updated_at | timestamp | Cap waktu |

> **Indeks:** `idx_bookings_conflict` pada (`room_id`, `booking_date`, `start_time`, `end_time`, `status`) untuk pengecekan konflik yang efisien.

**Nilai Status Peminjaman:**
- `Draft` — Dibuat tetapi belum diajukan
- `Pending` — Diajukan dan menunggu persetujuan
- `Approved` — Sepenuhnya disetujui
- `Rejected` — Ditolak oleh penyetuju
- `Revised` — Dikirimkan kembali untuk revisi

#### `booking_attachments`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| booking_id | bigint FK → bookings (cascade) | Peminjaman terkait |
| requirement_id | bigint FK → workflow_requirements | Persyaratan yang dipenuhi |
| uploader_id | bigint FK → users | Pengguna yang mengunggah |
| document_type | string | Tipe/label dokumen |
| file_path | string | Jalur penyimpanan file |
| created_at / updated_at | timestamp | Cap waktu |

#### `approvals`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| booking_id | bigint FK → bookings (cascade) | Peminjaman terkait |
| approver_id | bigint FK → users | Pengguna yang menyetujui/menolak |
| step_id | bigint FK → workflow_steps | Langkah alur kerja untuk persetujuan ini |
| approval_status | string(50) | Status tindakan persetujuan ini |
| notes | text nullable | Catatan/alasan dari penyetuju |
| signature_image | text nullable | Base64 atau jalur gambar tanda tangan |
| qr_code | text nullable | Data kode QR |
| approved_at | timestamp | Cap waktu tindakan persetujuan |
| attempt | integer (default 1) | Nomor percobaan (bertambah pada revisi) |

> **Indeks:** `idx_approvals_tracking` pada (`booking_id`, `step_id`).

#### `booking_logs`
| Kolom | Tipe | Deskripsi |
|---|---|---|
| id | bigint PK | Kunci utama |
| booking_id | bigint FK → bookings | Peminjaman terkait |
| actor_id | bigint FK → users | Pengguna yang melakukan tindakan |
| step_id | bigint FK → workflow_steps nullable | Langkah terkait (jika berlaku) |
| action | string | Tindakan yang dilakukan (huruf besar, cth: `SUBMITTED`, `APPROVED`) |
| notes | text nullable | Catatan tambahan |
| created_at / updated_at | timestamp | Cap waktu |

---

## 6. Model & Relasi

### `User` (`app/Models/User.php`)

```
User belongsTo Role
User belongsTo Unit
User belongsTo Position
User hasMany Booking
User hasMany Approval (as approver_id)
User hasMany BookingAttachment (as uploader_id)
User hasMany BookingLog (as actor_id)
```

**Dapat Diisi:** `role_id`, `unit_id`, `position_id`, `name`, `email`, `password`

**Tersembunyi:** `password`, `remember_token`

**Casting:** `email_verified_at` → datetime, `password` → hashed

---

### `Role` (`app/Models/Role.php`)

```
Role hasMany User
```

**Dapat Diisi:** `name`, `description`

---

### `Unit` (`app/Models/Unit.php`)

Hierarki yang merujuk pada diri sendiri (Pusat → Jurusan → Organisasi).

---

### `Position` (`app/Models/Position.php`)

```
Position belongsTo Unit
Position hasMany User
Position hasMany WorkflowStep
```

**Dapat Diisi:** `unit_id`, `name`

---

### `Building` (`app/Models/Building.php`)

```
Building hasMany Room
```

**Dapat Diisi:** `building_name`, `location`

---

### `Room` (`app/Models/Room.php`)

```
Room belongsTo Building
Room belongsTo Unit
Room hasMany Booking
```

**Dapat Diisi:** `building_id`, `unit_id`, `room_name`, `capacity`, `description`

---

### `Workflow` (`app/Models/Workflow.php`)

```
Workflow belongsTo Unit
Workflow hasMany WorkflowStep (diurutkan berdasarkan step_order ASC)
Workflow hasMany WorkflowRequirement
Workflow hasMany Booking
```

**Dapat Diisi:** `unit_id`, `name`, `description`

---

### `WorkflowStep` (`app/Models/WorkflowStep.php`)

```
WorkflowStep belongsTo Workflow
WorkflowStep belongsTo Position
WorkflowStep hasMany Approval (as step_id)
```

**Dapat Diisi:** `workflow_id`, `position_id`, `step_order`, `requires_attachment`

**Casting:** `requires_attachment` → boolean

---

### `WorkflowRequirement` (`app/Models/WorkflowRequirement.php`)

```
WorkflowRequirement belongsTo Workflow
```

**Dapat Diisi:** `workflow_id`, `document_name`, `is_mandatory`

**Casting:** `is_mandatory` → boolean

---

### `Booking` (`app/Models/Booking.php`)

```
Booking belongsTo User
Booking belongsTo Room
Booking belongsTo Workflow
Booking hasMany BookingAttachment
Booking hasMany Approval
Booking hasMany BookingLog
```

**Dapat Diisi:** `user_id`, `room_id`, `workflow_id`, `event_name`, `event_description`, `booking_date`, `start_time`, `end_time`, `current_step`, `status`, `revision_count`

**Casting:** `booking_date` → date, `start_time` → datetime:H:i:s, `end_time` → datetime:H:i:s

---

### `BookingAttachment` (`app/Models/BookingAttachment.php`)

```
BookingAttachment belongsTo Booking
BookingAttachment belongsTo WorkflowRequirement (as requirement_id)
BookingAttachment belongsTo User (as uploader_id)
```

**Dapat Diisi:** `booking_id`, `requirement_id`, `uploader_id`, `document_type`, `file_path`

---

### `Approval` (`app/Models/Approval.php`)

```
Approval belongsTo Booking
Approval belongsTo User (as approver_id)
Approval belongsTo WorkflowStep (as step_id)
```

**Dapat Diisi:** `booking_id`, `approver_id`, `step_id`, `approval_status`, `notes`, `signature_image`, `qr_code`, `approved_at`, `attempt`

**Casting:** `approved_at` → datetime

---

### `BookingLog` (`app/Models/BookingLog.php`)

**Dapat Diisi:** `booking_id`, `actor_id`, `step_id`, `action`, `notes`

---

## 7. Peran & Otorisasi

Sistem menggunakan empat peran, disimpan dalam tabel `roles` dan ditegakkan melalui middleware `CheckRole`.

| Peran | Deskripsi | Akses |
|---|---|---|
| `SuperAdmin` | Administrator pusat | Akses sistem penuh, semua rute admin |
| `Admin_Unit` | Administrator tingkat unit | Rute admin yang dibatasi pada unit mereka sendiri |
| `Approver` | Pejabat persetujuan (cth: Ketua Departemen) | Dashboard penyetuju, antrian persetujuan |
| `User` | Pengguna reguler (mahasiswa/staf) | Pencarian ruangan, pengajuan peminjaman, riwayat peminjaman pribadi |

### Resolusi Peran saat Login

Setelah autentikasi, rute `/dashboard` digunakan untuk menampilkan tampilan yang benar berdasarkan peran:

```
SuperAdmin / Admin_Unit → admin.dashboard
Approver               → approver.dashboard
User (default)         → user.dashboard
```

---

## 8. Middleware

### `CheckRole` (`app/Http/Middleware/CheckRole.php`)

Menegakkan akses berbasis peran pada grup rute. Kode ini:

1. Mengembalikan `401` jika pengguna tidak terautentikasi.
2. Memeriksa apakah nama peran pengguna ada dalam daftar peran yang diizinkan; mengembalikan `403` jika tidak.
3. Untuk peran `AdminUnit`: menetapkan atribut permintaan `scope_unit_id` sehingga controller dapat membatasi kueri ke unit admin. Juga memvalidasi bahwa parameter `unit_id` yang tertanam di URL cocok dengan unit admin.

**Penggunaan dalam rute:**
```php
Route::middleware(['auth', 'checkRole:SuperAdmin,AdminUnit'])->prefix('admin')->group(...)
Route::middleware(['auth', 'checkRole:Approver'])->prefix('approver')->group(...)
Route::middleware(['auth', 'checkRole:User'])->prefix('user')->group(...)
```

> Atribut `scope_unit_id` dapat diambil di controller melalui `$request->attributes->get('scope_unit_id')`.

---

## 9. Rute

Rute dibagi menjadi dua file: `routes/web.php` dan `routes/auth.php`.

### `routes/web.php` — Rute Aplikasi

| Metode | URI | Nama | Middleware | Deskripsi |
|---|---|---|---|---|
| GET | `/` | — | — | Halaman selamat datang/landing |
| GET | `/dashboard` | `dashboard` | `auth` | Dashboard yang disesuaikan dengan peran |
| GET | `/riwayat` | `riwayat` | `auth` | Riwayat peminjaman yang disesuaikan dengan peran |
| GET | `/profile` | `profile.edit` | `auth` | Halaman edit profil |
| PATCH | `/profile` | `profile.update` | `auth` | Perbarui profil |
| DELETE | `/profile` | `profile.destroy` | `auth` | Hapus akun |

#### Rute Admin (`/admin/*`) — `checkRole:SuperAdmin,AdminUnit`

| Metode | URI | Nama | Deskripsi |
|---|---|---|---|
| GET | `/admin/dashboard` | `admin.dashboard` | Dashboard admin |
| GET | `/admin/fasilitas` | `fasilitas` | Halaman manajemen fasilitas |
| GET | `/admin/unit` | `unit` | Halaman manajemen unit |
| GET | `/admin/kelola-user` | `kelola-user` | Halaman manajemen pengguna |
| POST | `/admin/user` | `tambah-user.store` | Buat pengguna baru |

#### Rute Penyetuju (`/approver/*`) — `checkRole:Approver`

| Metode | URI | Nama | Deskripsi |
|---|---|---|---|
| GET | `/approver/dashboard` | `approver.dashboard` | Dashboard penyetuju |
| GET | `/approver/meja-kerja` | `meja-kerja` | Meja kerja persetujuan |

#### Rute Pengguna (`/user/*`) — `checkRole:User`

| Metode | URI | Nama | Deskripsi |
|---|---|---|---|
| GET | `/user/cari-ruangan` | `cari-ruangan` | Halaman pencarian ruangan |
| GET | `/user/jadwal-saya` | `jadwal-saya` | Jadwal peminjaman pribadi |

### `routes/auth.php` — Rute Autentikasi (Laravel Breeze)

#### Rute Tamu

| Metode | URI | Nama | Deskripsi |
|---|---|---|---|
| GET | `/register` | `register` | Formulir pendaftaran |
| POST | `/register` | — | Kirim pendaftaran |
| GET | `/login` | `login` | Formulir login |
| POST | `/login` | — | Kirim login |
| GET | `/forgot-password` | `password.request` | Formulir lupa kata sandi |
| POST | `/forgot-password` | `password.email` | Kirim tautan reset |
| GET | `/reset-password/{token}` | `password.reset` | Formulir reset kata sandi |
| POST | `/reset-password` | `password.store` | Kirim kata sandi baru |

#### Rute Autentikasi Terautentikasi

| Metode | URI | Nama | Deskripsi |
|---|---|---|---|
| GET | `/verify-email` | `verification.notice` | Prompt verifikasi email |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` | Verifikasi email (ditandatangani, dibatasi) |
| POST | `/email/verification-notification` | `verification.send` | Kirim ulang email verifikasi |
| GET | `/confirm-password` | `password.confirm` | Halaman konfirmasi kata sandi |
| POST | `/confirm-password` | — | Kirim konfirmasi kata sandi |
| PUT | `/password` | `password.update` | Perbarui kata sandi |
| POST | `/logout` | `logout` | Logout |

---

## 10. Controller

### `Admin\UserController` (`app/Http/Controllers/Admin/UserController.php`)

| Metode | HTTP | Rute | Deskripsi |
|---|---|---|---|
| `store` | POST | `/admin/user` | Buat pengguna baru. Hash kata sandi kemudian panggil `User::create()`. Alihkan ke `kelola-user` jika berhasil. |

**Otorisasi:** Hanya peran `SuperAdmin` dan `Admin_Unit` yang dapat memanggil ini melalui `StoreUserRequest::authorize()`.

---

### `ProfileController` (`app/Http/Controllers/ProfileController.php`)

| Metode | HTTP | Rute | Deskripsi |
|---|---|---|---|
| `edit` | GET | `/profile` | Tampilkan formulir edit profil, dengan meneruskan pengguna yang terautentikasi. |
| `update` | PATCH | `/profile` | Validasi dan perbarui nama/email. Hapus `email_verified_at` jika email berubah. |
| `destroy` | DELETE | `/profile` | Validasi kata sandi saat ini, logout, hapus akun, dan batalkan sesi. |

---

### Auth Controller (`app/Http/Controllers/Auth/`)

Ini adalah controller standar Laravel Breeze:

| Controller | Tanggung Jawab |
|---|---|
| `AuthenticatedSessionController` | Login / Logout |
| `RegisteredUserController` | Registrasi pengguna |
| `PasswordResetLinkController` | Kirim email reset kata sandi |
| `NewPasswordController` | Reset kata sandi dengan token |
| `PasswordController` | Perbarui kata sandi pengguna terautentikasi |
| `ConfirmablePasswordController` | Gerbang konfirmasi kata sandi |
| `EmailVerificationPromptController` | Tampilkan pemberitahuan verifikasi email |
| `VerifyEmailController` | Tangani tautan verifikasi email |
| `EmailVerificationNotificationController` | Kirim ulang email verifikasi |

---

## 11. Form Requests (Validasi)

### `StoreUserRequest` (`app/Http/Requests/StoreUserRequest.php`)

Digunakan oleh `Admin\UserController@store`.

**Otorisasi:** Pengguna harus memiliki peran `SuperAdmin` atau `Admin_Unit`.

**Aturan Validasi:**

| Field | Aturan |
|---|---|
| `name` | required, string, max:150 |
| `email` | required, string, lowercase, email, max:150, unique dalam tabel `users` |
| `password` | required, memenuhi aturan kata sandi default Laravel |
| `unit_id` | required, harus ada dalam tabel `units` |
| `position_id` | required, harus ada dalam tabel `positions` |
| `role_id` | required, harus ada dalam tabel `roles` |

---

### `ProfileUpdateRequest` (`app/Http/Requests/ProfileUpdateRequest.php`)

Digunakan oleh `ProfileController@update`.

**Aturan Validasi:**

| Field | Aturan |
|---|---|
| `name` | required, string, max:255 |
| `email` | required, string, lowercase, email, max:255, unique (mengabaikan ID bersangkutan) |

---

## 12. Layanan

### `WorkflowService` (`app/Services/WorkflowService.php`)

Menangani logika bisnis yang terkait dengan alur kerja persetujuan.

#### `getNextApprover(int $bookingId): ?User`

Menemukan User yang seharusnya menyetujui peminjaman di langkah berikutnya.

**Logika:**
1. Muat peminjaman dengan alur kerja dan ruangannya (dengan unit).
2. Temukan `WorkflowStep` di mana `step_order = current_step + 1`.
3. Kembalikan `User` yang cocok dengan `position_id` langkah tersebut dalam unit pemilik ruangan.
4. Mengembalikan `null` jika tidak ada langkah berikutnya (alur kerja selesai).

---

#### `validateRequirements(int $bookingId): array`

Memeriksa apakah semua dokumen alur kerja wajib telah diunggah sebelum peminjaman dapat diajukan.

**Mengembalikan:** `['valid' => bool, 'missing' => Collection<WorkflowRequirement>]`

**Logika:**
1. Temukan semua record `WorkflowRequirement` wajib untuk alur kerja peminjaman.
2. Bandingkan dengan record `BookingAttachment` yang diunggah.
3. Kembalikan persyaratan yang hilang jika ada.

---

#### `getWorkflowSteps(int $bookingId): Collection`

Mengembalikan semua record `WorkflowStep` untuk alur kerja peminjaman, diurutkan berdasarkan `step_order`, dengan `position` dimuat lebih awal.

---

#### `nextApproverRequiresAttachment(int $bookingId): bool`

Mengembalikan `true` jika langkah alur kerja berikutnya memiliki `requires_attachment = true`, berarti penyetuju harus mengunggah dokumen sebelum menyetujui.

---

### `LoggerService` (`app/Services/LoggerService.php`)

Utilitas statis untuk mencatat entri jejak audit ke tabel `booking_logs`.

#### `LoggerService::logAction(int $bookingId, string $action, ?int $stepId = null, ?string $notes = null): void`

**Parameter:**

| Parameter | Tipe | Deskripsi |
|---|---|---|
| `$bookingId` | int | Peminjaman yang sedang ditindaklanjuti |
| `$action` | string | Label tindakan — selalu disimpan dalam HURUF BESAR (cth: `SUBMITTED`, `APPROVED`, `REJECTED`, `REVISED`) |
| `$stepId` | int\|null | ID langkah alur kerja jika relevan |
| `$notes` | string\|null | Catatan opsional (alasan penolakan, pesan revisi, dll.) |

Atribut `actor_id` secara otomatis diselesaikan dari `Auth::id()`.

**Contoh penggunaan:**
```php
LoggerService::logAction($booking->id, 'APPROVED', $currentStep->id, 'Disetujui tanpa catatan');
```

---

## 13. Helper

### `app/Helpers/RouteHelper.php`

Fungsi helper global (dimuat otomatis melalui Composer).

#### `dashboardRoute(): string`

Mengembalikan URL untuk dashboard dashboard pengguna saat ini yang sesuai dengan peran.

| Peran | Mengembalikan |
|---|---|
| SuperAdmin / Admin_Unit | `route('admin.dashboard')` |
| Lainnya | `route('user.dashboard')` |

---

#### `isDashboardRoute(): bool`

Mengembalikan `true` jika permintaan saat ini cocok dengan nama rute `admin.dashboard` atau `user.dashboard`. Digunakan untuk rendering UI bersyarat (cth: penyorotan bilah sisi aktif).

---

#### `getPageTitle(): string`

Mengembalikan judul halaman yang mudah dibaca berdasarkan nama rute saat ini.

| Nama Rute | Judul |
|---|---|
| `admin.dashboard` | Dashboard |
| `user.dashboard` | Dashboard |
| `cari-ruangan` | Cari Ruangan |
| `jadwal-saya` | Jadwal Saya |
| `riwayat` | Riwayat |
| `profile.edit` | Profil |
| `approve` | Persetujuan |
| *(default)* | Dashboard |

---

## 14. Database Seeder

`DatabaseSeeder` (`database/seeders/DatabaseSeeder.php`) mengisi database dengan data tes yang representatif.

### Urutan Seeding

1. **Peran** — Membuat 4 peran: `SuperAdmin`, `Admin_Unit`, `User`, `Approver`
2. **Unit** — Membuat hierarki 3 tingkat:
   - Pusat (Politeknik Negeri Malang)
   - 3 Jurusan: Teknologi Informasi, Teknik Sipil, Teknik Elektro
   - 6 Organisasi (2 per Jurusan): HMTI, BEM TI, HM Sipil, BEM Sipil, HM Elektro, BEM Elektro
3. **Posisi** — Membuat jabatan per unit: Wakil Direktur, Ketua Jurusan TI, Kaprodi TI, Ketua Jurusan Sipil, Ketua Jurusan Elektro, Ketua HMTI
4. **Gedung & Ruangan** — 3 gedung (Gedung AN, Gedung TI, Auditorium), 5 ruangan
5. **Pengguna** — 9 pengguna di semua peran (lihat tabel di bawah)
6. **Alur Kerja & Langkah** — 2 alur kerja: "Peminjaman JTI" (3 langkah) dan "Peminjaman Auditorium" (2 langkah)

### Pengguna Seed Default

| Nama | Email | Peran | Unit |
|---|---|---|---|
| Super Admin Politeknik | `superadmin@spacein.test` | SuperAdmin | Pusat |
| Admin Jurusan TI | `admin.ti@spacein.test` | Admin_Unit | Jurusan TI |
| Admin Jurusan Sipil | `admin.sipil@spacein.test` | Admin_Unit | Jurusan Sipil |
| Dr. Budi Santoso | `kajur.ti@spacein.test` | Approver | Jurusan TI (Ketua Jurusan) |
| Dr. Siti Rahayu | `wadir@spacein.test` | Approver | Pusat (Wakil Direktur) |
| Ir. Agus Wijaya | `kaprodi.ti@spacein.test` | Approver | Jurusan TI (Kaprodi) |
| Andi Mahasiswa TI | `user@spacein.test` | User | HMTI |
| Budi Mahasiswa Sipil | `budi@spacein.test` | User | BEM Sipil |
| Citra Mahasiswi Elektro | `citra@spacein.test` | User | HM Elektro |

> **Kata sandi default untuk semua pengguna seed:** `12345`

### Alur Kerja: Peminjaman JTI

Berlaku untuk ruangan yang dimiliki oleh Jurusan Teknologi Informasi.

| Langkah | Posisi | Memerlukan Lampiran |
|---|---|---|
| 1 | Kaprodi TI | Tidak |
| 2 | Ketua Jurusan TI | Tidak |
| 3 | Wakil Direktur | Ya (harus mengunggah surat disposisi) |

### Alur Kerja: Peminjaman Auditorium

Berlaku untuk ruangan Auditorium yang dimiliki oleh Pusat.

| Langkah | Posisi | Memerlukan Lampiran |
|---|---|---|
| 1 | Ketua Jurusan TI | Tidak |
| 2 | Wakil Direktur | Ya (harus mengunggah surat izin Wadir) |

---

*Dokumen ini mencakup arsitektur backend yang telah diimplementasikan. Untuk dokumentasi frontend (tampilan Blade), lihat `docs/FRONTEND.md`.*
