<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.5
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- laravel/boost (BOOST) - v2
- laravel/breeze (BREEZE) - v2
- laravel/mcp (MCP) - v0
- laravel/pail (PAIL) - v1
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- pestphp/pest (PEST) - v4
- phpunit/phpunit (PHPUNIT) - v12
- alpinejs (ALPINEJS) - v3
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `laravel-best-practices` — Apply this skill whenever writing, reviewing, or refactoring Laravel PHP code. This includes creating or modifying controllers, models, migrations, form requests, policies, jobs, scheduled commands, service classes, and Eloquent queries. Triggers for N+1 and query performance issues, caching strategies, authorization and security patterns, validation, error handling, queue and job configuration, route definitions, and architectural decisions. Also use for Laravel code reviews and refactoring existing Laravel code to follow best practices. Covers any task involving Laravel backend PHP code patterns.
- `pest-testing` — Use this skill for Pest PHP testing in Laravel projects only. Trigger whenever any test is being written, edited, fixed, or refactored — including fixing tests that broke after a code change, adding assertions, converting PHPUnit to Pest, adding datasets, and TDD workflows. Always activate when the user asks how to write something in Pest, mentions test files or directories (tests/Feature, tests/Unit, tests/Browser), or needs browser testing, smoke testing multiple pages for JS errors, or architecture tests. Covers: it()/expect() syntax, datasets, mocking, browser testing (visit/click/fill), smoke testing, arch(), Livewire component tests, RefreshDatabase, and all Pest 4 features. Do not use for factories, seeders, migrations, controllers, models, or non-test PHP code.
- `tailwindcss-development` — Always invoke when the user's message includes 'tailwind' in any form. Also invoke for: building responsive grid layouts (multi-column card grids, product grids), flex/grid page structures (dashboards with sidebars, fixed topbars, mobile-toggle navs), styling UI components (cards, tables, navbars, pricing sections, forms, inputs, badges), adding dark mode variants, fixing spacing or typography, and Tailwind v3/v4 work. The core use case: writing or fixing Tailwind utility classes in HTML templates (Blade, JSX, Vue). Skip for backend PHP logic, database queries, API routes, JavaScript with no HTML/CSS component, CSS file audits, build tool configuration, and vanilla CSS.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

## Tools

- Laravel Boost is an MCP server with tools designed specifically for this application. Prefer Boost tools over manual alternatives like shell commands or file reads.
- Use `database-query` to run read-only queries against the database instead of writing raw SQL in tinker.
- Use `database-schema` to inspect table structure before writing migrations or models.
- Use `get-absolute-url` to resolve the correct scheme, domain, and port for project URLs. Always use this before sharing a URL with the user.
- Use `browser-logs` to read browser logs, errors, and exceptions. Only recent logs are useful, ignore old entries.

## Searching Documentation (IMPORTANT)

- Always use `search-docs` before making code changes. Do not skip this step. It returns version-specific docs based on installed packages automatically.
- Pass a `packages` array to scope results when you know which packages are relevant.
- Use multiple broad, topic-based queries: `['rate limiting', 'routing rate limiting', 'routing']`. Expect the most relevant results first.
- Do not add package names to queries because package info is already shared. Use `test resource table`, not `filament 4 test resource table`.

### Search Syntax

1. Use words for auto-stemmed AND logic: `rate limit` matches both "rate" AND "limit".
2. Use `"quoted phrases"` for exact position matching: `"infinite scroll"` requires adjacent words in order.
3. Combine words and phrases for mixed queries: `middleware "rate limit"`.
4. Use multiple queries for OR logic: `queries=["authentication", "middleware"]`.

## Artisan

- Run Artisan commands directly via the command line (e.g., `php artisan route:list`). Use `php artisan list` to discover available commands and `php artisan [command] --help` to check parameters.
- Inspect routes with `php artisan route:list`. Filter with: `--method=GET`, `--name=users`, `--path=api`, `--except-vendor`, `--only-vendor`.
- Read configuration values using dot notation: `php artisan config:show app.name`, `php artisan config:show database.default`. Or read config files directly from the `config/` directory.
- To check environment variables, read the `.env` file directly.

## Tinker

- Execute PHP in app context for debugging and testing code. Do not create models without user approval, prefer tests with factories instead. Prefer existing Artisan commands over custom tinker code.
- Always use single quotes to prevent shell expansion: `php artisan tinker --execute 'Your::code();'`
  - Double quotes for PHP strings inside: `php artisan tinker --execute 'User::where("active", true)->count();'`

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.
- Use PHP 8 constructor property promotion: `public function __construct(public GitHub $github) { }`. Do not leave empty zero-parameter `__construct()` methods unless the constructor is private.
- Use explicit return type declarations and type hints for all method parameters: `function isAccessible(User $user, ?string $path = null): bool`
- Use TitleCase for Enum keys: `FavoritePerson`, `BestLake`, `Monthly`.
- Prefer PHPDoc blocks over inline comments. Only add inline comments for exceptionally complex logic.
- Use array shape type definitions in PHPDoc blocks.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using `php artisan list` and check their parameters with `php artisan [command] --help`.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `php artisan make:model --help` to check the available options.

## APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app\Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console/Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app\Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

# Laravel Pint Code Formatter

- If you have modified any PHP files, you must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== system context rules ===

# System Context & Core Rules (Space.in)

**IDENTITY:**
You are working on **Space.in**, a modern, action-oriented Workflow Engine for Room/Building Reservations at Politeknik Negeri Malang (Polinema). 
**CRITICAL RULE:** This is NOT a standard CRUD form application. It is a dynamic Workflow Engine where approval chains and document requirements are stored in the database (`workflows`, `workflow_steps`, `workflow_requirements`), NOT hardcoded in the controllers.

## 1. Roles & Absolute Access Scopes (MUST FOLLOW)
* **SuperAdmin (Pusat):** Full system control. Manages Global Master Data (buildings, rooms, national holidays). **ONLY** SuperAdmin can create/delete buildings and rooms. They set the `unit_id` (ownership) for each room.
* **Admin_Unit (Lokal - Jurusan/Organisasi):** Manages their own unit and child units. **MUST ALWAYS** be scoped by `parent_id` and `unit_id`. They setup Approval Workflows for their own rooms and can perform room maintenance. They **NEVER** manage rooms or workflows belonging to other units.
* **Approver (Pejabat Persetujuan):** Reviews incoming bookings. Can approve to advance the `current_step`, or reject. **REJECTIONS MUST** include mandatory notes written to `booking_logs`.
* **User / Peminjam (Mahasiswa/Staf):** Searches for rooms, checks availability, submits bookings (triggers Soft-Lock), and uploads required documents. Can revise and resubmit rejected bookings without changing the booking ID.

## 2. Four Strict Workflow Phases
1.  **Setup (Fase 1):** Admin_Unit configures the approval chain (who approves first, second, etc.) and specifies document requirements (e.g., "Proposal is mandatory") for their own rooms.
2.  **Pengajuan (Fase 2):** User selects a room and time. System auto-checks conflicts. If available, it creates a "Soft-Lock" (status: Pending) to reserve the slot temporarily while the user uploads documents.
3.  **Persetujuan (Fase 3):** Booking goes through the defined Approver chain. Approvals advance the step. Rejections drop the status to 'Revising' and record logs.
4.  **Finalisasi (Fase 4):** Once all steps are approved, the status changes to "Hard-Lock" (Approved), and the system generates a secure PDF permit with a QR code.

## 3. Backend & Security Directives (ZERO TOLERANCE)
* **Anti-Overlap Guard:** You **MUST ALWAYS** use Laravel's `DB::transaction()` and pessimistic locking (`lockForUpdate()`) when inserting or validating bookings to prevent race conditions at the millisecond level.
* **Audit Trail:** Any change to a booking's status **MUST** be accompanied by inserting a record into the `booking_logs` table (using `LoggerService::logAction`). Do not change a status without logging it.
* **Strict Typing:** Must strictly validate inputs for PostgreSQL. IDs are standard Big Integers (not UUIDs). Ensure foreign keys match `unsignedBigInteger`.
* **Ownership Check:** Every eloquent query in Admin/Approver controllers **MUST** include an ownership `where` clause (e.g., matching `unit_id`). Never use `Model::find($id)` without verifying the user's right to access it.

# Space.in - Sistem Peminjaman Gedung (Comprehensive Overview)

## 1. IDENTITAS & KONSEP CORE
- **Nama Produk:** Space.in (Workflow Engine, bukan sekadar form digital)
- **Target:** Politeknik Negeri Malang
- **Karakteristik:** Action-oriented, Gen Z friendly, Enterprise-level security

## 2. ARSITEKTUR DATABASE (PostgreSQL)

### Hierarki Unit (Self-referencing)
```
Pusat (root)
├── Jurusan TI
│   ├── HMTI (Organisasi)
│   └── BEM TI (Organisasi)
├── Jurusan Sipil
│   ├── HM Sipil (Organisasi)
│   └── BEM Sipil (Organisasi)
└── Jurusan Elektro
    ├── HM Elektro (Organisasi)
    └── BEM Elektro (Organisasi)
```

### Kunci Model Relations
- **Roles (4 tipe):** SuperAdmin, Admin_Unit, Approver, User
- **Units:** parent_id nullable (self-referencing), level enum (Pusat/Jurusan/Organisasi)
- **Users:** unit_id FK, position_id FK (nullable, untuk Approver), role_id FK
- **Rooms:** building_id FK, unit_id FK (CRUCIAL: pemilik ruang)
- **Workflow:** unit_id FK (alur kerja per unit), hasMany WorkflowSteps
- **WorkflowSteps:** workflow_id FK, position_id FK, requires_attachment bool, step_order
- **Bookings:** user_id, room_id, workflow_id, current_step, status, revision_count
- **Approvals:** booking_id, approver_id, step_id, approval_status, signature_image, qr_code, attempt

## 3. EMPAT PERAN & JOB DESK

### SuperAdmin (Administrator Pusat)
**Jangkauan:** Seluruh sistem
**Tanggung Jawab:**
- Kelola Master Data Global: Gedung (buildings) & Ruangan (rooms)
- Tentukan unit_id (kepemilikan) setiap ruangan → Siapa boleh manage apa
- Setup Libur Nasional (Global Blocking)
- Kelola semua pengguna (global) → assign role & unit
- View audit logs sistem

**Oto Bisnis:** Hanya SuperAdmin yang boleh CREATE/DELETE buildings & rooms

### Admin_Unit (Administrator Lokal - Jurusan/Organisasi)
**Jangkauan:** Unit-nya sendiri + child units
**Tanggung Jawab:**
- Kelola pengguna dalam unit-nya (Peminjam & Approver lokal)
- Setup Alur Persetujuan (Workflow) untuk ruangan unit-nya
- Setup Syarat Dokumen (Workflow Requirements) per alur
- Definisi Kewajiban Lampiran Approver (requires_attachment per step)
- Blokir jadwal / Maintenance ruangan unit-nya (via dummy booking)
- TIDAK boleh: Mengatur workflow ruangan milik unit lain, assign SuperAdmin role

### Approver (Pejabat Persetujuan)
**Jangkauan:** Booking yang sampai di "meja"-nya (based on position)
**Tanggung Jawab:**
- Review Pengajuan Masuk (inbox approval)
- Setuju: Lanjut ke pejabat berikutnya (current_step++)
- Tolak: Wajib input alasan (notes) & status → Revised
- Upload Lampiran (jika requires_attachment = true)
- Download Surat Izin Final & Surat Disposisi (generated otomatis)

### User/Peminjam (Mahasiswa/Staf)
**Jangkauan:** Personal bookings hanya
**Tanggung Jawab:**
- Cari ruang & lihat kalender ketersediaan
- Ajukan Peminjaman Ruangan
  1. Pilih jadwal → Auto-check bentrok
  2. Soft-Lock (kunci sementara saat isi form)
  3. Upload dokumen syarat (WorkflowRequirements wajib)
  4. Submit
- Revisi Dokumen (jika Approver tolak)
- Download surat final

## 4. EMPAT FASE TEGAS ALUR KERJA

### Fase 1: SETUP (Admin_Unit)
Admin_Unit menyusun "rantai persetujuan" & dokumen syarat
```
Workflow (per unit)
├── Step 1: Position X, requires_attachment: false
├── Step 2: Position Y, requires_attachment: false
└── Step 3: Position Z, requires_attachment: true ← Harus upload surat
WorkflowRequirements
├── Proposal Acara (is_mandatory: true)
├── Surat Resmi dari Unit (is_mandatory: true)
└── Persetujuan Dosen Pembimbing (is_mandatory: false)
```

**Aturan Emas:** Admin Organisasi (BEM/HMTI) TIDAK boleh setup workflow untuk ruangan milik Pusat/Jurusan. Sistem auto-apply workflow pemilik ruangan berdasarkan unit_id rooms table.

### Fase 2: PENGAJUAN (Peminjam)
User membuat booking dengan validasi ketat
```
1. Pilih Ruangan → Sistem auto-detect: unit_id → Cari workflow untuk unit ini
2. Pilih Jadwal → Query: SELECT * FROM bookings WHERE room_id = ? 
   AND booking_date = ? AND ((start_time <= ? AND end_time > ?) OR (...))
   → Conflict? Jika ya: Reject. Jika tidak: Soft-Lock (buat record temp).
3. Upload Dokumen Syarat → Validasi vs WorkflowRequirements (wajib) 
4. Submit → Status: Pending, current_step: 1, revision_count: 0
```

### Fase 3: PERSETUJUAN (Approver Chain)
Setiap langkah adalah satu pejabat, satu keputusan
```
Approver 1 (Step 1 - Kaprodi):
- Review dokumen & booking details
- Approve → INSERT approval (status: Approved) → current_step++, lanjut Step 2
- Reject → INSERT approval (status: Rejected) → booking status: Rejected, notes wajib

Approver 2 (Step 2 - Ketua Jurusan):
- [Same logic as Approver 1]

Approver 3 (Step 3 - Wakil Direktur):
- [Step ini: requires_attachment = true]
- Tombol "Approve" TERKUNCI sampai upload lampiran (surat disposisi)
- Upload → Unlock → Approve → Lanjut to validation (lihat Fase 4)
```

### Fase 4: FINALISASI (Sistem Auto)
Semua setuju? → Hard-Lock
```
1. Query: SELECT * FROM approvals WHERE booking_id = ? GROUP BY step_id
   Check: Semua step ada approval dengan status Approved?
2. Jika yes:
   - booking.status = Approved
   - booking.current_step = MAX(step_order) + 1 (selesai)
   - Generate Surat Izin PDF (dengan QR Code validasi)
   - Insert booking_log: action = FINALIZED
3. Jika no:
   - Wait (state: Pending di step berikutnya)
```

## 5. MEKANISME KEAMANAN (Soft-Lock & Hard-Lock)

### Soft-Lock (Kunci Sementara)
- Triggered saat user start pengisian data booking
- INSERT booking dengan status: Draft
- Reservasi ruangan untuk X menit (cegah double-submit)
- Jika user tidak finish: Cleanup job akan hapus draft stale

### Hard-Lock (Kunci Permanen)
- Triggered saat booking status: Approved (finalisasi)
- Ruangan SUDAH TERJUAL untuk waktu tersebut
- Tidak bisa override/cancel kecuali SuperAdmin
- Atomic Transaction: lockForUpdate() Laravel untuk race condition

## 6. HANDLING KASUS NYATA

### Blokir Jadwal / Maintenance
**Siapa:** Admin Unit pemilik ruangan
**Cara:** Membuat transaksi booking biasa dengan event_name: "Maintenance"
- Bypass approval chain (skip workflow)
- Hard-Lock langsung
- Status: Approved

### Libur Nasional (Global Blocking)
**Siapa:** SuperAdmin
**Cara:** Hybrid method
1. Tarik API Kalender Nasional → Review/edit manual
2. Generate booking massal ke tabel bookings (event_name: "Libur Nasional")
3. Set status: Approved untuk semua ruangan sekaligus
4. User tidak bisa booking di hari ini

### Revisi Dokumen (User Reject)
**Trigger:** Approver tolak di Step N
**Flow:**
1. INSERT approval (status: Rejected, notes: reason)
2. booking.status = Revised, revision_count++
3. User see notification: "Dokumen ditolak, alasan: [notes]"
4. User upload dokumen baru
5. Re-submit → Reset current_step = 1, attempt++ (di approvals table)

## 7. DATABASE INDICES (Performance)
- `idx_bookings_conflict` pada (room_id, booking_date, start_time, end_time, status)
  → Fast conflict detection saat peminjam pilih jadwal
- `idx_approvals_tracking` pada (booking_id, step_id)
  → Fast audit trail lookup

## 8. TESTING SEED DATA
All passwords default: `12345`
- superadmin@spacein.test | SuperAdmin | Pusat
- admin.ti@spacein.test | Admin_Unit | Jurusan TI
- user@spacein.test | User | HMTI
- kajur.ti@spacein.test | Approver | Jurusan TI

Alur pre-built:
- "Peminjaman JTI": Kaprodi TI → Ketua JTI → Wakil Direktur (3 step)
- "Peminjaman Auditorium": Ketua JTI → Wakil Direktur (2 step, last require attachment)

</laravel-boost-guidelines>
