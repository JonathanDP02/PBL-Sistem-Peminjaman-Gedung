# Laporan Penyelarasan Fitur (PRD Alignment Report)

Dokumen ini memuat hasil audit fungsionalitas sistem `Space.in` berdasarkan dokumen *Product Requirements Document* (PRD v1.0).

## 🟢 Fitur Yang Sudah Diimplementasikan

Berikut adalah daftar fitur yang sudah berhasil diimplementasikan sesuai dengan PRD:

### 1. Sistem Autentikasi dan Role (UC-02)
- [x] Login terpusat dengan multi-role: SuperAdmin, Admin_Unit, Approver, dan User.
- [x] Filter otorisasi hierarkis menggunakan `unit_id` dan `parent_id`.

### 2. Manajemen Fasilitas dan Master Data (UC-07, UC-08, UC-09)
- [x] **SuperAdmin**: Mampu mengelola seluruh unit, gedung, dan ruangan secara global, serta menetapkan kepemilikan ruangan ke `unit_id` tertentu.
- [x] **Admin_Unit**: Mampu mengelola ruangan yang berada di dalam wilayah `unit_id`-nya sendiri.
- [x] **Dukungan Gambar**: Penambahan fitur upload foto/gambar (`image`) untuk entitas Gedung dan Ruangan agar dapat tampil di landing page.
- [x] **Tampilan SuperAdmin**: Transformasi tampilan manajemen fasilitas SuperAdmin dari berbasis *Card* menjadi bentuk *Tabel* yang lebih profesional, simpel, dan mudah dibaca (sesuai *feedback*).

### 3. Setup Alur Persetujuan (Workflow) (UC-05)
- [x] **Admin_Unit**: Mampu membuat workflow (SOP persetujuan) dan menetapkan urutan langkah jabatan yang spesifik untuk unitnya.
- [x] **Relasi Ruangan - Workflow**: Ruangan sekarang dipasangkan langsung dengan *Workflow* pada saat penambahan/pengubahan data ruangan.

### 4. Pengajuan Peminjaman & Anti-Overlap Dasar (UC-03)
- [x] **Peminjam**: Dapat melihat ruangan, mengisi detail peminjaman, dan mengunggah dokumen.
- [x] **Sistem Deteksi Workflow**: Jika ruangan yang dipilih tidak memiliki workflow aktif, peminjam akan mendapatkan pesan peringatan dan peminjaman tidak dapat dilanjutkan.
- [x] Pengecekan bentrok jadwal dasar (*Anti-Overlap Check*).

### 5. Review dan Persetujuan (UC-04)
- [x] **Approver**: Dashboard untuk melihat pengajuan masuk.
- [x] Logika *Hard-Lock*: Pembaruan status persetujuan yang berjalan bertahap berdasarkan urutan *step* pada workflow yang dipilih.

---

## 🔴 Fitur Yang Belum Diimplementasikan (Pending/Missing)

Berikut adalah daftar fitur atau kriteria penerimaan yang **belum ada atau memerlukan penyempurnaan** agar 100% sesuai dengan PRD:

> [!WARNING]
> Beberapa fitur di bawah ini merupakan bagian krusial dari Core Logic `Space.in` yang harus segera diselesaikan.

### 1. Fitur Validasi QR Code (F-P11)
- **Status:** Belum Ada Scanner
- **Keterangan:** PRD mengamanatkan adanya fitur "Scan Validasi" untuk QR Code pada Surat Izin Final oleh Pengelola Gedung di hari-H. Saat ini baru generate file, belum ada antarmuka pemindainya.

### 2. Cleanup Job untuk *Soft-Lock*
- **Status:** Belum Ada
- **Keterangan:** PRD (Poin 5. Soft-Lock) mensyaratkan adanya proses latar belakang (Cleanup Job) yang akan menghapus *draft stale* jika user tidak menyelesaikan form dalam kurun waktu "X menit".

### 3. Revisi Pengajuan (UC-03a)
- **Status:** Butuh Pengecekan / Belum Sempurna
- **Keterangan:** Alur di mana Approver "Menolak" lalu User merevisi pengajuan kembali (tanpa membuat *booking* baru) agar `revision_count` bertambah dan proses berulang dari awal.

### 4. Notifikasi Sistem (F-P09 & F-AU06)
- **Status:** Belum Diimplementasikan
- **Keterangan:** Sistem notifikasi (Email atau Notifikasi Lonceng/In-App) ketika terjadi perubahan status pada pengajuan, atau saat ada pengajuan baru masuk ke meja Approver.

### 5. Database Transaction & Pessimistic Locking
- **Status:** Butuh Pengecekan Lanjutan
- **Keterangan:** Aturan emas dari "Backend & Security Directives" menuntut penggunaan `DB::transaction()` dan `lockForUpdate()` saat validasi Anti-Overlap untuk mencegah *race condition* secara mutlak. Hal ini perlu dipastikan kembali di dalam `BookingController@store`.

---

## 📝 Rekomendasi Selanjutnya

1. Mengimplementasikan fitur **Scanner QR Code** di halaman depan (Guest/Admin).
2. Membangun mekanisme **Cleanup Job** (Scheduler Laravel) untuk membersihkan `Soft-Lock` yang sudah *stale/expired*.
3. Menyempurnakan alur **Revisi Dokumen** bagi Peminjam jika ditolak Approver.
