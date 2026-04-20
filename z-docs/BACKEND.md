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
15. [Alur Kerja Sistem](#15-alur-kerja-sistem)
16. [Konfigurasi Email & Mailable](#16-konfigurasi-email--mailable)

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

```
BookingLog belongsTo Booking
BookingLog belongsTo User (as actor_id)
BookingLog belongsTo WorkflowStep (as step_id)
```

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

### `routes/api.php` — Rute API (Dilindungi Auth)

Semua endpoint di bawah dilindungi dengan `Route::middleware('auth')`. Endpoint ini dirancang untuk konsumsi frontend JavaScript atau mobile app.

#### Workflow API

| Metode | URI | Controller@Method | Deskripsi |
|---|---|---|---|
| GET | `/api/workflows/{id}/requirements` | `Api\WorkflowController@requirements` | Ambil daftar persyaratan dokumen untuk workflow tertentu. Returns JSON array dengan field `document_name` dan `is_mandatory` |

#### Room API

| Metode | URI | Controller@Method | Deskripsi |
|---|---|---|---|
| GET | `/api/rooms/available?date=&start=&end=` | `Api\RoomController@available` | Cek ketersediaan ruangan untuk tanggal dan jam tertentu. Query parameter: `date` (Y-m-d), `start` (H:i), `end` (H:i). Returns JSON array ruangan yang tersedia |

#### Booking API

| Metode | URI | Controller@Method | Deskripsi |
|---|---|---|---|
| GET | `/api/bookings/{id}/timeline` | `Api\BookingController@timeline` | Ambil timeline/riwayat peminjaman untuk tracking UI. Returns JSON array booking_logs dengan relasi actor dan step |

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

---

### `Api\WorkflowController` (`app/Http/Controllers/Api/WorkflowController.php`)

| Metode | HTTP | Rute | Deskripsi |
|---|---|---|---|
| `requirements` | GET | `/api/workflows/{id}/requirements` | Ambil persyaratan dokumen wajib untuk workflow. Mengembalikan JSON array dengan field `document_name` (string) dan `is_mandatory` (boolean). Digunakan untuk rendering form upload dokumen di frontend. |

**Logika:**
1. Load workflow dengan eager-load relasi `requirements`
2. Map requirements ke array minimal (hanya `document_name` dan `is_mandatory`)
3. Return as JSON response

---

### `Api\RoomController` (`app/Http/Controllers/Api/RoomController.php`)

| Metode | HTTP | Rute | Deskripsi |
|---|---|---|---|
| `available` | GET | `/api/rooms/available?date=&start=&end=` | Cek ketersediaan ruangan berdasarkan tanggal dan rentang waktu. Mengembalikan JSON array ruangan yang **tidak memiliki** booking aktif (status: Pending, Approved) pada jam yang bertabrakan. |

**Validasi Query Parameter:**
- `date` — required, format Y-m-d
- `start` — required, format H:i (24-jam)
- `end` — required, format H:i, harus after `start`

**Logika Query:**
```sql
SELECT rooms.*
FROM rooms
WHERE NOT EXISTS (
  SELECT 1 FROM bookings
  WHERE bookings.room_id = rooms.id
    AND bookings.booking_date = {date}
    AND bookings.status IN ('Pending', 'Approved')
    AND bookings.start_time < {end}
    AND bookings.end_time > {start}
)
```

---

### `Api\BookingController` (`app/Http/Controllers/Api/BookingController.php`)

| Metode | HTTP | Rute | Deskripsi |
|---|---|---|---|
| `timeline` | GET | `/api/bookings/{id}/timeline` | Ambil riwayat perubahan status booking (booking_logs) untuk timeline tracking UI. Eager-load relasi `actor` (User) dan `step` (WorkflowStep) untuk menampilkan nama approver dan posisi. |

**Response Format:**
```json
[
  {
    "id": 1,
    "booking_id": 5,
    "actor_id": 3,
    "step_id": 2,
    "action": "SUBMITTED",
    "notes": "Peminjaman diajukan ke alur persetujuan",
    "created_at": "2026-04-19T10:30:00Z",
    "actor": { "id": 3, "name": "Andi Mahasiswa TI", ... },
    "step": { "id": 2, "step_order": 1, "position_id": 5, ... }
  }
]
```
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

---

## 15. Alur Kerja Sistem

Dokumentasi detail alur kerja sistem SpaceIn dari berbagai perspektif pengguna dan tahapan bisnis.

---

### 15.1 Alur Peminjam (User)

#### Fase 1: Akses Tanpa Autentikasi (Guest)

```
1. Guest mengakses URL aplikasi (/)
   ↓
   Route: GET /
   Controller: Menampilkan view 'welcome' (halaman landing)
   Status: Belum autentikasi
```

#### Fase 2: Registrasi User Baru

```
2. Guest klik tombol "Daftar" di halaman landing
   ↓
   Route: GET /register
   Controller: RegisteredUserController@create
   Response: Tampilkan form registrasi (Blade: auth.register)
   
3. Isi form: name, email, password, password_confirmation
   ↓
   Route: POST /register
   Controller: RegisteredUserController@store
   Operasi DB:
     - Validasi: StoreUserRequest (internal validation)
       * name: required, string, max:150
       * email: required, email, unique:users
       * password: memenuhi aturan kata sandi default Laravel
     - Unit diset dari request default (atau pilihan user jika ada)
     - Peran otomatis: 'User'
     - Hash kata sandi menggunakan bcrypt
     - INSERT ke tabel 'users'
   Response: Auth guard 'web' mencatat sesi pengguna
   Redirect: POST /login → dashboard redirect (lihat Fase 3)
```

#### Fase 3: Login & Dashboard Redirect

```
4. User (atau registrasi baru) klik "Masuk"
   ↓
   Route: GET /login
   Controller: AuthenticatedSessionController@create
   Response: Tampilkan form login (Blade: auth.login)
   
5. Isi email & password, klik Masuk
   ↓
   Route: POST /login
   Controller: AuthenticatedSessionController@store
   Operasi DB:
     - Validasi LoginRequest: email, password
     - Laravel Guard 'web' verifikasi credentials
     - Jika berhasil: regenerate session token
   Operasi Kode:
     - if (Auth::attempt(...)) {
         $request->session()->regenerate();
         $user = Auth::user(); // load relasi 'role'
         match ($user->role->name) {
           'User' => redirect to 'cari-ruangan',
           'Approver' => redirect to 'meja-kerja',
           default => redirect to 'dashboard'
         }
       }
   Response: Redirect dengan status autentikasi sukses
```

#### Fase 4: Cari Ruangan & Periksa Ketersediaan

```
6. User tiba di halaman pencarian ruangan (cari-ruangan)
   ↓
   Route: GET /user/cari-ruangan
   Middleware: auth, checkRole:User
   Controller: Menampilkan view 'user.cari-ruangan'
   Frontend: AJAX/JavaScript fetch data ruangan & jadwal
   
7. Frontend AJAX: GET /rooms (atau endpoint yang ada)
   ↓
   Controller: RoomController@index
   Query DB:
     SELECT rooms.*, buildings.building_name, units.unit_name
     FROM rooms
     JOIN buildings ON rooms.building_id = buildings.id
     JOIN units ON rooms.unit_id = units.id
   Response JSON: Daftar ruangan dengan info bangunan & unit pemilik
   
8. User pilih ruangan, pilih tanggal & jam
   ↓
   Frontend AJAX: POST /check-availability
   (atau logika di aplikasi frontend untuk cek konflik lokal)
   Query DB:
     SELECT bookings.* FROM bookings
     WHERE room_id = {roomId}
     AND booking_date = {date}
     AND status NOT IN ('Rejected', 'Draft')
     AND (
       (start_time < {userEndTime} AND end_time > {userStartTime})
     )
   Response: Status ketersediaan (available / conflict)
   Frontend: Tampilkan "Ruangan tersedia" atau "Bentrok dengan jadwal lain"
```

#### Fase 5: Submit Booking (Draft → Pending)

```
9. User isi detail peminjaman:
   - event_name: "Seminar Workshop Python"
   - event_description: "....."
   - booking_date: 2026-04-15
   - start_time: 09:00
   - end_time: 12:00
   Klik "Ajukan Peminjaman"
   ↓
   Route: POST /bookings (atau endpoint sesuai struktur)
   Middleware: auth
   Controller: BookingController@store
   Validasi:
     - room_id: required, exists:rooms,id
     - workflow_id: required, exists:workflows,id (otomatis dari room)
     - event_name: required, string, max:200
     - event_description: nullable, text
     - booking_date: required, date, >= today
     - start_time: required, date_format:H:i:s
     - end_time: required, after:start_time
   
   Operasi DB Transaksi Atomic:
     START TRANSACTION
     
     • SELECT rooms WHERE id = {roomId} FOR UPDATE (lock)
     • Cek konflikt booking di rentang waktu yg sama
     • Jika ada booking aktif dgn status bukan 'Rejected' → abort
     
     • INSERT INTO bookings:
       {
         user_id: Auth::id(),
         room_id: {roomId},
         workflow_id: {workflowId}, // diambil dari unit pemilik room
         event_name: {eventName},
         event_description: {eventDescription},
         booking_date: {bookingDate},
         start_time: {startTime},
         end_time: {endTime},
         current_step: 1, // langkah pertama workflow
         status: 'Draft', // belum final submit
         revision_count: 0,
         created_at: NOW(),
         updated_at: NOW()
       }
     
     • INSERT INTO booking_logs:
       {
         booking_id: {newBookingId},
         actor_id: Auth::id(),
         action: 'CREATED',
         notes: 'Booking dibuat (Draft)',
         created_at: NOW()
       }
     
     COMMIT
   
   Response: Return booking object dengan ID
   Frontend: Redirect ke halaman upload dokumen atau detail booking
```

#### Fase 6: Upload Dokumen Syarat Workflow

```
10. User melihat halaman detail booking (status: Draft)
    Sistem menampilkan daftar dokumen yang wajib diunggah
    ↓
    GET /api/workflows/{workflowId}/requirements
    Middleware: auth
    Controller: WorkflowController@showRequirements
    Otorisasi: authorizeUnit() check
    Query DB:
      SELECT * FROM workflow_requirements
      WHERE workflow_id = {workflowId}
      AND is_mandatory = true
    Response JSON:
      [
        { id: 1, document_name: "Surat Permohonan", is_mandatory: true },
        { id: 2, document_name: "Identitas Peserta", is_mandatory: true },
        { id: 3, document_name: "Surat Sponsor", is_mandatory: false }
      ]
    Frontend: Tampilkan form upload per dokumentasi
    
11. User upload dokumen (misal: Surat Permohonan)
    ↓
    Route: POST /booking-attachments
    Controller: BookingAttachmentController@store
    Validasi:
      - booking_id: required, exists:bookings,id
      - requirement_id: required, exists:workflow_requirements,id
      - file: required, mimes:pdf,doc,docx, max:5120 (5MB)
    
    Operasi File & DB:
      • file_path = storage_path('bookings/{bookingId}/{filename}')
      • File::put(file_path, $request->file('file')->getContent())
      • INSERT INTO booking_attachments:
        {
          booking_id: {bookingId},
          requirement_id: {requirementId},
          uploader_id: Auth::id(),
          document_type: requirement.document_name,
          file_path: {file_path},
          created_at: NOW()
        }
      • INSERT INTO booking_logs:
        {
          booking_id: {bookingId},
          actor_id: Auth::id(),
          action: 'DOCUMENT_UPLOADED',
          notes: 'Upload: Surat Permohonan',
          created_at: NOW()
        }
    Response: Success dengan booking attachment ID
    
12. User ulangi upload untuk semua dokumen wajib (requirement.is_mandatory = true)
    ↓
    Frontend: Tampilkan checklist, highlight mana yang sudah/belum
    
13. Semua dokumen wajib sudah diunggah, user klik "Ajukan ke Approver"
    ↓
    Route: PATCH /bookings/{id}/submit
    Middleware: auth
    Controller: BookingController@submitForApproval
    Validasi:
      • Cek booking milik user saat ini
      • Cek status = 'Draft'
      • Validasi semua requirement wajib sudah diunggah:
        WorkflowService::validateRequirements($bookingId)
        if (!valid) → return error "Dokumen belum lengkap"
    
    Operasi DB:
      UPDATE bookings
      SET status = 'Pending', 
          updated_at = NOW()
      WHERE id = {bookingId}
      
      INSERT INTO booking_logs:
      {
        booking_id: {bookingId},
        actor_id: Auth::id(),
        action: 'SUBMITTED',
        notes: 'Peminjaman diajukan ke alur persetujuan',
        created_at: NOW()
      }
    
    Response: Booking object dengan status 'Pending'
    Frontend: Alert "Berhasil diajukan, tunggu persetujuan approver"
```

#### Fase 7: Monitor Riwayat & Status Persetujuan

```
14. User akses halaman "Jadwal Saya" (jadwal-saya)
    ↓
    Route: GET /user/jadwal-saya
    Middleware: auth, checkRole:User
    Controller: Menampilkan view 'user.jadwal-saya'
    Frontend AJAX: GET /api/bookings?user_id={userId}
    
    Query DB:
      SELECT bookings.*, rooms.room_name, workflows.name as workflow_name
      FROM bookings
      JOIN rooms ON bookings.room_id = rooms.id
      JOIN workflows ON bookings.workflow_id = workflows.id
      WHERE bookings.user_id = {userId}
      ORDER BY booking_date DESC, start_time DESC
    
    Response JSON: Daftar peminjaman user
    Frontend: Tampilkan tabel/card dengan status, tanggal, jam, approver saat ini
    
15. User klik detail booking → lihat timeline approval
    ↓
    Frontend: GET /api/bookings/{id}/approvals
    Query DB:
      SELECT approvals.*, users.name as approver_name, positions.name as position_name,
             workflow_steps.step_order
      FROM approvals
      JOIN users ON approvals.approver_id = users.id
      JOIN workflow_steps ON approvals.step_id = workflow_steps.id
      LEFT JOIN positions ON workflow_steps.position_id = positions.id
      WHERE approvals.booking_id = {bookingId}
      ORDER BY workflow_steps.step_order ASC
    
    Response: Timeline approval dengan status setiap step
    Frontend: Tampilkan timeline visual
```

---

### 15.2 Alur Approver (Pejabat Persetujuan)

#### Fase 1: Login & Dashboard Approver

```
1. Approver login (same as User phase 3)
   ↓
   Route: POST /login
   Redirect: ke /approver/meja-kerja (karena role = 'Approver')
```

#### Fase 2: Lihat Antrian Persetujuan

```
2. Approver akses "Meja Kerja" (meja-kerja)
   ↓
   Route: GET /approver/meja-kerja
   Middleware: auth, checkRole:Approver
   Controller: Menampilkan view 'approver.meja-kerja'
   Frontend AJAX: GET /api/approvals/pending
   
   Query DB (kompleks):
      SELECT bookings.*, users.name as peminjam, rooms.room_name,
             workflows.name as workflow_name,
             current_step, workflow_steps.position_id,
             positions.name as position_required
      FROM bookings
      JOIN bookings.workflow_id = workflows.id
      JOIN workflows.workflow_steps ON workflow.id = workflow_steps.workflow_id
      JOIN positions ON workflow_steps.position_id = positions.id
      WHERE bookings.status = 'Pending'
      AND workflow_steps.step_order = bookings.current_step
      AND positions.id = Auth::user()->position_id  // Approver ini di posisi mana?
      ORDER BY bookings.created_at ASC
   
   Response JSON: Daftar booking yg menunggu persetujuan approver ini
   Frontend: Tampilkan queue/antrian dengan sorting by created_at
```

#### Fase 3: Lihat Detail Booking untuk Approval

```
3. Approver klik booking → buka detail
   ↓
   Route: GET /approver/approvals/{booking_id}
   Middleware: auth, checkRole:Approver
   Controller: ApprovalController@show
   
   Operasi:
     • Load booking dengan eager load: user, room, workflow, workflow_steps
     • Load semua dokumen yang sudah diunggah
     • Load approval history (approval tracking)
     • Load current step requirement: apakah step ini requires_attachment?
   
   Response: Tampilkan:
     - Detail peminjam & acara
     - Tanggal, jam, ruangan
     - Dokumen yang diunggah (link preview/download)
     - Form submit: "Setuju" atau "Tolak"
     - Jika step.requires_attachment = true: form upload mandatory
     - History persetujuan sebelumnya
```

#### Fase 4: Persetujuan (Approve)

```
4. Approver review dokumen → klik "Setuju"
   ↓
   Route: POST /approvals/{booking_id}/approve
   Middleware: auth, checkRole:Approver
   Controller: ApprovalController@approve
   
   Validasi:
     • Booking milik step approver ini
     • Status booking = 'Pending'
     • Jika step.requires_attachment = true:
       - File lampiran harus diunggah (attachment di request)
       - Validasi file: mimes:pdf,doc,docx, max:5120
   
   Operasi DB Transaksi:
     START TRANSACTION
     
     1. INSERT INTO approvals:
        {
          booking_id: {bookingId},
          approver_id: Auth::id(),
          step_id: {currentStepId},
          approval_status: 'Approved',
          notes: {optional_notes},
          signature_image: {base64_atau_path_tanda_tangan},
          qr_code: {generated_qr_code},
          approved_at: NOW(),
          attempt: {current_revision_count}
        }
     
     2. Jika step requires_attachment = true & file diunggah:
        INSERT INTO booking_attachments:
        {
          booking_id: {bookingId},
          requirement_id: {step_requirement_id},
          uploader_id: Auth::id(),
          document_type: {approver_attachment_name},
          file_path: {file_path_approver_doc},
          created_at: NOW()
        }
     
     3. INSERT INTO booking_logs:
        {
          booking_id: {bookingId},
          actor_id: Auth::id(),
          step_id: {currentStepId},
          action: 'APPROVED',
          notes: 'Disetujui oleh ' . position.name,
          created_at: NOW()
        }
     
     4. Cari langkah berikutnya:
        next_step = WorkflowStep WHERE workflow_id = workflow.id AND step_order = current_step + 1
        
        if (next_step exists) {
          UPDATE bookings
          SET current_step = next_step.step_order,
              updated_at = NOW()
          WHERE id = {bookingId}
          // Status tetap 'Pending' untuk step berikutnya
        } else {
          // Tidak ada step berikutnya = semua approver setuju
          UPDATE bookings
          SET status = 'Approved',
              updated_at = NOW()
          WHERE id = {bookingId}
          
          // Generate izin akhir (PDF dengan QR)
          Trigger PDF generation dengan semua approval signatures
        }
     
     COMMIT
   
   Response: Redirect ke meja kerja dgn flash "Berhasil disetujui"
   Notifikasi: Email ke approver berikutnya (jika ada) atau peminjam (jika final)
```

#### Fase 5: Penolakan / Revisi

```
5. Approver review & klik "Tolak"
   ↓
   Route: POST /approvals/{booking_id}/reject
   Middleware: auth, checkRole:Approver
   Controller: ApprovalController@reject
   
   Validasi:
     • Booking milik step approver ini
     • notes (alasan penolakan) tidak boleh kosong
   
   Operasi DB Transaksi:
     START TRANSACTION
     
     1. INSERT INTO approvals:
        {
          booking_id: {bookingId},
          approver_id: Auth::id(),
          step_id: {currentStepId},
          approval_status: 'Rejected',
          notes: {rejection_reason},
          approved_at: NOW(),
          attempt: {current_revision_count}
        }
     
     2. UPDATE bookings:
        {
          status: 'Revised', // Bukan 'Rejected' langsung, tapi 'Revised' untuk dibuka kembali
          current_step: 1,   // Reset ke langkah pertama
          revision_count: revision_count + 1,
          updated_at: NOW()
        }
     
     3. INSERT INTO booking_logs:
        {
          booking_id: {bookingId},
          actor_id: Auth::id(),
          step_id: {currentStepId},
          action: 'REJECTED',
          notes: {rejection_reason},
          created_at: NOW()
        }
     
     COMMIT
   
   Response: Redirect ke meja kerja
   Notifikasi: Email ke peminjam "Peminjaman ditolak, silakan revisi"
```

---

### 15.3 Alur Admin Unit (Setup Workflow)

#### Fase 1: Login Admin Unit

```
1. Admin_Unit login
   ↓
   Redirect: /admin/dashboard
```

#### Fase 2: Setup Alur Kerja (Workflow)

```
2. Admin_Unit akses "Setup Alur Persetujuan" → buka form/view
   ↓
   Route: GET /admin/workflow/create (atau page setup-workflow)
   Middleware: auth, checkRole:SuperAdmin,Admin_Unit
   
3. Admin_Unit isi:
   - Nama Workflow: "Peminjaman Lab Komputer TI"
   - Deskripsi: "Alur persetujuan untuk peminjaman lab..."
   Klik "Buat Workflow"
   ↓
   Route: POST /workflows
   Middleware: auth
   Controller: WorkflowController@store
   
   Validasi:
     • Auth::user()->role->name must be 'Admin_Unit'
     • name: required, string, max:255
     • description: nullable
   
   Operasi DB:
     unit_id = Auth::user()->unit_id (otomatis)
     INSERT INTO workflows:
     {
       unit_id: {unit_id},
       name: {workflowName},
       description: {description},
       created_at: NOW()
     }
   
   Otorisasi: authorizeUnit() hanya Admin_Unit unitnya sendiri bisa buat
   Response: Redirect ke halaman edit workflow
```

#### Fase 3: Tambahkan Workflow Steps

```
4. Admin_Unit di halaman workflow detail → "Tambah Step"
   ↓
   Route: GET /admin/workflow/{id}/steps/create
   
5. Admin_Unit pilih:
   - Posisi: "Ketua Lab Komputer TI" (dari tabel positions)
   - Urutan: 1
   - Perlu Lampiran?: ☐ (unchecked)
   Klik "Simpan Step"
   ↓
   Route: POST /workflow-steps
   Middleware: auth
   Controller: WorkflowStepController@store
   
   Validasi:
     • workflow_id: required, exists:workflows,id
     • position_id: required, exists:positions,id
     • step_order: required, integer, >= 1
     • requires_attachment: nullable, boolean
   
   Otorisasi: Cek workflow belongs to Admin_Unit's unit
   
   Operasi DB:
     unit_id = Workflow.unit_id
     if (Auth::user()->role->name === 'Admin_Unit' && Workflow.unit_id !== Auth::user()->unit_id) {
       abort(403);
     }
     
     INSERT INTO workflow_steps:
     {
       workflow_id: {workflowId},
       position_id: {positionId},
       step_order: 1,
       requires_attachment: false,
       created_at: NOW()
     }
   
   Response: Success, reload workflow detail
   
6. Admin_Unit tambah step kedua:
   - Posisi: "Wadir Akademik"
   - Urutan: 2
   - Perlu Lampiran?: ☑ (checked) → Pejabat harus upload surat disposisi
   Klik "Simpan Step"
   ↓
   Operasi sama seperti step 1, tapi requires_attachment = true
```

#### Fase 4: Tambahkan Persyaratan Dokumen

```
7. Admin_Unit di halaman workflow → "Tambah Dokumen Wajib"
   ↓
   Route: GET /admin/workflow/{id}/requirements/create
   
8. Admin_Unit isi:
   - Nama Dokumen: "Surat Permohonan"
   - Wajib?: ☑ (checked)
   Klik "Simpan"
   ↓
   Route: POST /workflow-requirements
   Middleware: auth
   Controller: WorkflowRequirementController@store
   
   Validasi: document_name: required, max:150, is_mandatory: nullable boolean
   Otorisasi: Same as step creation
   
   Operasi DB:
     INSERT INTO workflow_requirements:
     {
       workflow_id: {workflowId},
       document_name: 'Surat Permohonan',
       is_mandatory: true,
       created_at: NOW()
     }
   
9. Ulangi untuk dokumen lain:
   - "Kartu Identitas Peserta"
   - "Surat Sponsor Kegiatan"
   
   Setelah selesai, workflow sudah siap digunakan.
```

#### Fase 5: Assign Workflow ke Ruangan

```
10. SuperAdmin atau Admin_Unit yang memiliki room:
    Masuk ke halaman Edit Ruangan
    ↓
    Route: PATCH /rooms/{id}
    Di form: Pilih Workflow: "Peminjaman Lab Komputer TI"
    
    Operasi DB:
      Tabel rooms belum ada kolom workflow_id, tapi bisa ditambah.
      Atau: Assign otomatis berdasarkan unit_id room ke workflow yang ada di unit itu.
      
      Option 1 (future): rooms.workflow_id = {workflowId}
      Option 2 (current): Query otomatis by unit
```

---

### 15.4 Flow Diagram Singkat

```
┌─────────────────────────────────────────────────────────────────┐
│                     USER (PEMINJAM) JOURNEY                     │
└─────────────────────────────────────────────────────────────────┘
                                
   Landing Page / Guest
         ↓
    Register / Login
         ↓
   Auth Guard 'web' ✓
         ↓
   Role Check: 'User' → /user/cari-ruangan
         ↓
   Search Rooms + Check Availability
         ↓
   Select Room, Date, Time
         ↓
   Create Booking (POST /bookings)
   → Status: 'Draft', current_step: 1
         ↓
   Upload Wajib Documents
   → booking_attachments INSERT
         ↓
   Submit for Approval (PATCH /bookings/{id}/submit)
   → Status: 'Pending'
         ↓
   Monitor Status di /user/jadwal-saya
         ↓
         ├─ APPROVED (semua langkah setuju) → PDF Izin + QR
         ├─ REVISED (ada yang tolak) → Kembali ke Draft, upload ulang
         └─ pending (menunggu approver)

┌─────────────────────────────────────────────────────────────────┐
│                   APPROVER (PEJABAT) JOURNEY                    │
└─────────────────────────────────────────────────────────────────┘

   Login → Role Check: 'Approver' → /approver/meja-kerja
         ↓
   Query: Booking di current_step yang butuh approver ini
         ↓
   Buka Detail Booking
         ↓
   Review Dokumen + Catatan
         ↓
         ├─ APPROVE → INSERT approval, next_step++
         │            Jika requires_attachment: upload doc
         │            Email ke next approver atau peminjam
         │
         └─ REJECT → INSERT approval, status='Revised', current_step=1
                      revision_count++
                      Email ke peminjam (revisi)

┌─────────────────────────────────────────────────────────────────┐
│                 ADMIN_UNIT (SETUP WORKFLOW) JOURNEY             │
└─────────────────────────────────────────────────────────────────┘

   Login → Role Check: 'Admin_Unit' → /admin/dashboard
         ↓
   Setup Alur Persetujuan (Workflow)
   → Create: name, description (unit_id otomatis)
         ↓
   Tambah Langkah (Steps)
   → position_id, step_order, requires_attachment
         ↓
   Tambah Dokumen Wajib (Requirements)
   → document_name, is_mandatory
         ↓
   Workflow ready untuk digunakan di room milik unit
```

---

### 15.7 Email Notifications (Future Implementation)

```
Trigger events:

1. Booking di-submit
   → Email ke approver 1
   Subject: "Ada permintaan peminjaman ruangan dari {peminjam}"
   Body: Link ke meja kerja, preview details

2. Step disetujui + ada step berikutnya
   → Email ke approver berikutnya
   Subject: "Ada permintaan peminjaman yang perlu persetujuan Anda"

3. Semua approve (booking final)
   → Email ke peminjam
   Subject: "Peminjaman Anda disetujui! PDF izin terlampir"
   Attachment: PDF izin dengan QR code

4. Di-reject (status = 'Revised')
   → Email ke peminjam
   Subject: "Peminjaman Anda perlu revisi"
   Body: Alasan ditolak, link ke booking untuk upload ulang

5. Timeout / Reminder (optional)
   → Email ke approver
   Subject: "Reminder: Ada {N} permintaan menunggu persetujuan Anda"
```

---

## 16. Konfigurasi Email & Mailable

### Konfigurasi Email di `.env`

Sistem menggunakan **Mailtrap** untuk email testing di development environment. Konfigurasi di `.env` (mulai April 2026):

```env
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=abe1c5ffa58a86              # Ganti dengan credential Mailtrap Anda
MAIL_PASSWORD=a58346e5db55be              # Ganti dengan credential Mailtrap Anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@spacein.com"
MAIL_FROM_NAME="${APP_NAME}"
```

**Setup Mailtrap:**
1. Buka https://mailtrap.io dan buat akun (gratis)
2. Pilih "Testing Inbox" / "My Inbox"
3. Klik "Integrations" → Pilih "Laravel"
4. Copy `MAIL_USERNAME` dan `MAIL_PASSWORD` dari instruksi yang diberikan
5. Paste ke file `.env` Anda
6. Setiap email yang dikirim akan tertangkap di Inbox Mailtrap untuk review

### Mailable: `BookingSubmittedMail`

**File:** `app/Mail/BookingSubmittedMail.php`

**Tujuan:** Mengirim notifikasi ke approver pertama ketika booking baru disubmit oleh user.

**Constructor:**
```php
public function __construct(Booking $booking)
{
    $this->booking = $booking;
}
```

Menerima parameter `Booking` model dengan relasi yang sudah di-load.

**Envelope (Subject):**
```
Subject: "Peminjaman Ruangan: [nama_acara]"
```

**Content (Template Markdown):**
File: `resources/views/emails/booking/submitted.blade.php`

Template menampilkan:
- Judul: "Peminjaman Ruangan Baru"
- Detail acara: nama, ruangan, tanggal, waktu, peminjam
- Tombol CTA: "Lihat Detail & Berikan Persetujuan" → link ke `/approver/meja-kerja`
- Footer branded dengan nama aplikasi

### Template Email

**File:** `resources/views/emails/booking/submitted.blade.php`

Menggunakan komponen Markdown Mailable Laravel:
- `<x-mail::message>` — Wrapper utama
- `<x-mail::button>` — Tombol CTA dengan URL
- Format: Markdown untuk email yang responsif di semua klien

### Testing Email

#### Cara 1: Tinker Interaktif

```bash
php artisan tinker
```

Dalam shell tinker:
```php
use App\Models\Booking;
use App\Mail\BookingSubmittedMail;
use Illuminate\Support\Facades\Mail;

$booking = Booking::with(['user', 'room'])->first();
Mail::to('test@example.com')->send(new BookingSubmittedMail($booking));
```

#### Cara 2: Script Standalone (`test_email.php`)

Script otomatis membuat booking dummy jika tidak ada, lalu mengirim email:

```php
php test_email.php
// Output:
// ✅ Email berhasil dikirim!
// 📌 Cek inbox Mailtrap Anda: https://mailtrap.io
```

#### Verifikasi di Mailtrap

1. Login ke https://mailtrap.io
2. Pilih "My Inbox"
3. Email akan muncul dalam hitungan detik
4. Klik untuk lihat HTML rendering, headers, dan plain text version

---

## 17. Database Factories

### `BookingFactory` (`database/factories/BookingFactory.php`)

Factory untuk menghasilkan dummy data `Booking` untuk testing.

**Definition:**
```php
public function definition(): array
{
    return [
        'user_id' => User::factory(),
        'room_id' => Room::factory(),
        'workflow_id' => Workflow::factory(),
        'event_name' => $this->faker->words(3, asText: true),
        'event_description' => $this->faker->sentence(),
        'booking_date' => $this->faker->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
        'start_time' => $this->faker->time('H:i:s'),
        'end_time' => $this->faker->time('H:i:s'),
        'current_step' => 1,
        'status' => 'Pending',
        'revision_count' => 0,
    ];
}
```

**Penggunaan:**
```php
// Satu booking
$booking = Booking::factory()->create();

// Batch multiple bookings
$bookings = Booking::factory()->count(5)->create();

// Dengan relasi custom
$booking = Booking::factory()->for(User::find(1))->create();
```

### `BookingFactory` juga membuat relasi secara otomatis:
- `user_id` → Factory User (membuat user baru jika belum ada)
- `room_id` → Factory Room (membuat room baru jika belum ada)
- `workflow_id` → Factory Workflow (membuat workflow baru jika belum ada)

---

*Dokumentasi ini merupakan reference lengkap untuk testing, validasi, dan pengembangan fitur di masa depan. Setiap request flow sudah mencakup Controller, Middleware, DB Operation, Validasi, dan Response yang diharapkan.*
