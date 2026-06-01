# Product Requirements Document (PRD)

## Space.in
### Sistem Informasi Peminjaman Ruangan dan Fasilitas Berbasis Web
#### Politeknik Negeri Malang (Polinema)

---

**Versi Dokumen:** 1.0  
**Status:** Draft / Review  
**Tanggal:** Mei 2026  
**Platform:** Web-based (Laravel Framework)  
**Metodologi:** Extreme Programming (XP)

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Latar Belakang dan Permasalahan](#2-latar-belakang-dan-permasalahan)
3. [Alur Bisnis](#3-alur-bisnis)
4. [Karakteristik Pengguna](#4-karakteristik-pengguna-dan-hak-akses)
5. [Kebutuhan Fungsional](#5-kebutuhan-fungsional)
6. [Spesifikasi Fitur Detail](#6-spesifikasi-fitur-detail-per-role)
7. [Kebutuhan Non-Fungsional](#7-kebutuhan-non-fungsional)
8. [Batasan, Asumsi, Ketergantungan](#8-batasan-asumsi-dan-ketergantungan)
9. [Kebutuhan Antarmuka](#9-kebutuhan-antarmuka)
10. [Aturan Bisnis dan Logika Sistem](#10-aturan-bisnis-dan-logika-sistem-kritis)
11. [Metodologi Pengembangan](#11-metodologi-pengembangan)
12. [Matriks Keterlacakan Kebutuhan](#12-matriks-keterlacakan-kebutuhan)
13. [Riwayat Dokumen](#13-riwayat-revisi-dokumen)

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen Product Requirements Document (PRD) ini disusun sebagai panduan komprehensif dalam pengembangan sistem Space.in. Dokumen ini bertujuan untuk:

- Menjadi acuan utama bagi tim pengembang dalam mengimplementasikan seluruh fitur sistem peminjaman fasilitas berbasis web di Polinema.
- Mendefinisikan secara rinci seluruh kebutuhan fungsional dan non-fungsional sistem, mulai dari alur digitalisasi birokrasi, manajemen ketersediaan ruangan secara real-time, hingga aspek keamanan dan performa.
- Menjadi media komunikasi dan validasi antara tim pengembang dengan dosen pembimbing dan stakeholder terkait.
- Memastikan solusi teknis yang dihasilkan benar-benar mampu mengatasi kendala bentrok jadwal dan inefisiensi administrasi peminjaman fasilitas.

### 1.2 Ruang Lingkup

Space.in adalah Sistem Informasi Peminjaman Ruangan dan Fasilitas berbasis web yang dikembangkan khusus untuk lingkungan Politeknik Negeri Malang (Polinema). Sistem ini mendigitalisasi seluruh proses birokrasi peminjaman fasilitas kampus yang sebelumnya dilakukan secara manual.

**Sistem Space.in menangani:**

- Layanan informasi ketersediaan ruangan dan fasilitas secara real-time.
- Pengelolaan data profil fasilitas (kapasitas, jenis peralatan, lokasi) secara terpusat dan digital.
- Manajemen jadwal dan transaksi peminjaman untuk mencegah bentrok jadwal.
- Alur persetujuan (workflow) berbasis hirarki birokrasi kampus (Pusat, Jurusan, Organisasi).
- Validasi digital menggunakan QR Code pada surat izin peminjaman.

### 1.3 Definisi, Singkatan, dan Akronim

| Istilah / Akronim | Definisi |
|---|---|
| **Space.in** | Nama sistem informasi peminjaman ruangan dan fasilitas berbasis web yang dikembangkan untuk Polinema. |
| **PRD** | Product Requirements Document - dokumen spesifikasi kebutuhan produk perangkat lunak. |
| **SKPL** | Spesifikasi Kebutuhan Perangkat Lunak. |
| **Polinema** | Politeknik Negeri Malang - institusi tempat sistem ini diimplementasikan. |
| **Guest** | Pengguna umum yang belum login; dapat melihat informasi ruangan dan kalender ketersediaan. |
| **Peminjam / User** | Pengguna terdaftar yang dapat mengajukan peminjaman ruangan. |
| **Approver** | Pejabat berwenang yang memiliki wewenang menyetujui atau menolak pengajuan peminjaman. |
| **Admin Unit** | Administrator yang mengelola data dan konfigurasi pada lingkup unit tertentu. |
| **Super Admin** | Pengguna dengan hak akses tertinggi yang mengelola seluruh sistem secara global. |
| **Workflow** | Alur persetujuan (rantai Approver) yang dikonfigurasi untuk setiap ruangan. |
| **Soft-Lock** | Status sementara pada jadwal ruangan selama proses pengajuan berlangsung untuk mencegah double-booking. |
| **Hard-Lock** | Status final pada jadwal ruangan setelah seluruh rantai persetujuan dipenuhi. |
| **Anti-Overlap** | Logika validasi sistem untuk mencegah terjadinya bentrok jadwal peminjaman. |
| **QR Code** | Kode matriks dua dimensi yang digunakan sebagai alat validasi digital pada surat izin peminjaman. |
| **XP** | Extreme Programming - metodologi pengembangan perangkat lunak yang digunakan dalam proyek ini. |
| **UC** | Use Case - skenario interaksi antara pengguna dan sistem. |
| **parent_id** | Referensi pengenal unit induk dalam struktur hierarki organisasi. |
| **unit_id** | Pengenal unik unit pemilik suatu ruangan/fasilitas. |

### 1.4 Referensi

- Ardianzah, D. H., Nuryasin, I., & Wiyono, B. S. (2022). Pengembangan Sistem Pengelolaan Peminjaman Auditorium Universitas Muhammadiyah Malang Berbasis Web Menggunakan Metode Personal Extreme Programming. REPOSITOR, Vol. 4, No. 2, Mei 2022, Pp. 137-146.
- Kurniawan, D. A. (2019). Aplikasi Peminjaman Ruangan Dan Gedung Pada Universitas Mercu Buana Kampus D Jatisampurna Berbasis Web. Jurnal Ilmu Teknik dan Komputer, Vol. 3, No. 2.
- Sandika, T., & Kurniawan, H. (2014). Information System Design of River Water Quality in Lampung Province through Personal Extreme Programming Method. Jurnal Ilmiah ESAI, Vol. 8, No. 2.

---

## 2. Latar Belakang dan Permasalahan

### 2.1 Latar Belakang

Politeknik Negeri Malang (Polinema) sebagai instansi pendidikan tinggi menyediakan berbagai fasilitas, mulai dari gedung, ruang kelas, auditorium, hingga peralatan pendukung lainnya. Fasilitas-fasilitas ini berperan sangat vital dalam menunjang kegiatan sivitas akademika, baik untuk keperluan akademik seperti perkuliahan dan seminar, maupun kegiatan non-akademik seperti acara organisasi mahasiswa.

Namun, dalam praktiknya, sistem administrasi dan birokrasi peminjaman fasilitas di lingkungan kampus kerap berjalan lambat dan kurang efisien. Mahasiswa atau pihak penyelenggara acara yang ingin meminjam ruangan harus melewati alur birokrasi secara manual — mendatangi berbagai pihak satu per satu untuk memproses perizinan, serta mengecek ketersediaan ruangan secara langsung. Kondisi ini seringkali menimbulkan ketidakpastian informasi mengenai jadwal kosong, meningkatkan risiko bentrok jadwal antar pengguna, dan menyita waktu produktif.

### 2.2 Identifikasi Masalah

Berdasarkan hasil wawancara dan analisis dokumen yang dilakukan tim pengembang, berikut adalah permasalahan yang teridentifikasi:

| No. | Kategori Masalah | Deskripsi |
|---|---|---|
| 1 | Inefisiensi Birokrasi | Proses mengharuskan peminjam mendatangi banyak pihak satu per satu secara fisik; tidak ada sistem satu pintu yang terintegrasi. |
| 2 | Blind Spot Jadwal | Tidak ada kalender ketersediaan real-time; peminjam harus mengecek manual ke pengelola gedung sehingga menimbulkan ketidakpastian informasi. |
| 3 | Risiko Double-Booking | Pencatatan manual pada buku besar memiliki risiko human error tinggi yang menyebabkan bentrok jadwal antar pengguna. |
| 4 | Minimnya Transparansi | Peminjam sering kehilangan jejak status dokumen (apakah masih di Sekprodi, Kajur, atau Wadir) karena tidak ada sistem pelacakan. |
| 5 | Verifikasi Lemah | Keabsahan surat hanya dinilai dari tanda tangan fisik yang mudah dipalsukan, tanpa alat validasi digital. |
| 6 | Dokumen Fisik Rentan | Berkas kertas rentan rusak, hilang, dan sulit diarsip secara sistematis untuk kebutuhan pelacakan historis. |

### 2.3 Rumusan Masalah

1. Merancang dan membangun sistem informasi peminjaman fasilitas berbasis web yang dapat menyederhanakan alur birokrasi di Politeknik Negeri Malang menggunakan framework Laravel.
2. Menyediakan sistem informasi yang dapat menampilkan ketersediaan jadwal gedung dan ruangan secara transparan dan real-time untuk mencegah terjadinya bentrokan jadwal.
3. Mendigitalisasi proses perizinan manual yang ada saat ini agar menjadi lebih efektif, efisien, dan mudah dipantau oleh pengguna maupun pihak pengelola fasilitas.

### 2.4 Tujuan Produk

- Memangkas jalur birokrasi yang panjang menjadi satu pintu layanan yang terintegrasi dan digital.
- Menyajikan status dan tanggal ketersediaan ruangan secara transparan dan real-time kepada seluruh sivitas akademika.
- Mengimplementasikan sistem validasi digital (QR Code) sebagai pengganti tanda tangan fisik yang rentan dipalsukan.
- Mempercepat proses persetujuan peminjaman melalui notifikasi dan dashboard digital untuk setiap Approver.
- Menciptakan arsip digital yang sistematis dan mudah ditelusuri untuk seluruh histori transaksi peminjaman.

---

## 3. Alur Bisnis

### 3.1 Gambaran Umum Alur Bisnis

Alur bisnis Space.in dirancang untuk menggantikan prosedur manual yang tidak efisien dengan sebuah sistem digital terintegrasi. Berikut adalah tahapan alur bisnis utama:

| Tahap | Aktivitas | Detail |
|---|---|---|
| 1 | Pencarian Ruangan | Peminjam mencari ruangan yang dibutuhkan dan memeriksa ketersediaan jadwal secara real-time melalui kalender ketersediaan. |
| 2 | Pengajuan Peminjaman | Peminjam mengisi detail acara (nama acara, estimasi peserta, durasi), memilih Workflow sesuai otoritas ruangan, dan mengunggah dokumen persyaratan. |
| 3 | Validasi Anti-Overlap | Sistem secara otomatis memvalidasi bentrok jadwal (logika Anti-Overlap) dan menerapkan Soft-Lock agar jadwal tidak dapat diambil pengguna lain selama proses berlangsung. |
| 4 | Antrean Persetujuan | Pengajuan masuk ke antrean Admin Unit dan Approver secara berurutan sesuai hirarki unit yang telah dikonfigurasi (Pusat, Jurusan, atau Organisasi). |
| 5 | Review Approver | Setiap Approver meninjau dokumen melalui dashboard; dapat menyetujui, meminta dokumen tambahan, atau menolak dengan catatan revisi. |
| 6 | Revisi (jika ditolak) | Peminjam memperbaiki dokumen dan mengajukan revisi kembali tanpa mengulang proses dari awal; sistem mencatat log percobaan revisi beserta timestamp. |
| 7 | Hard-Lock & Penerbitan Surat | Setelah seluruh rantai persetujuan selesai, status booking berubah menjadi Hard-Lock dan sistem menerbitkan Surat Izin Peminjaman PDF beserta QR Code unik. |
| 8 | Validasi di Hari H | Pengelola gedung memindai QR Code menggunakan scanner pada platform untuk memverifikasi keabsahan izin secara instan. |

---

## 4. Karakteristik Pengguna dan Hak Akses

### 4.1 Daftar Pengguna Sistem

Space.in melayani lima jenis pengguna dengan karakteristik dan hak akses yang berbeda:

#### **Guest**
- **Deskripsi:** Pengguna umum yang belum login ke sistem.
- **Hak Akses:**
  - Akses landing page
  - Melihat informasi ruangan & fasilitas
  - Melihat kalender ketersediaan
  - Akses halaman login

#### **Peminjam (User)**
- **Deskripsi:** Mahasiswa atau organisasi yang terdaftar dan dapat mengajukan peminjaman.
- **Hak Akses:**
  - Login ke sistem
  - Kelola profil akun
  - Cari & pilih ruangan
  - Ajukan peminjaman ruangan
  - Upload dokumen persyaratan
  - Pantau status pengajuan
  - Revisi pengajuan (jika ditolak)
  - Unduh bukti peminjaman (PDF + QR Code)

#### **Approver**
- **Deskripsi:** Pejabat berwenang (Sekprodi, Kajur, Wadir, dsb.) yang menyetujui/menolak pengajuan.
- **Hak Akses:**
  - Login ke sistem
  - Lihat daftar pengajuan yang menunggu
  - Lihat detail pengajuan lengkap
  - Setujui/tolak pengajuan dengan catatan alasan

#### **Admin Unit (Lokal)**
- **Deskripsi:** Administrator yang mengelola sistem pada lingkup unit tertentu (Jurusan/Organisasi).
- **Hak Akses:**
  - Login ke sistem
  - Kelola akun user internal (CRUD)
  - Atur role pengguna
  - Konfigurasi workflow persetujuan
  - Kelola data ruangan unit
  - Pantau seluruh pengajuan dalam unit

#### **Super Admin (Pusat)**
- **Deskripsi:** Pengguna dengan hak akses tertinggi — mengelola seluruh sistem secara global.
- **Hak Akses:**
  - Login ke sistem
  - Kelola seluruh user (termasuk Admin Unit & Approver Pusat)
  - Kelola struktur unit dan hierarki organisasi
  - Kelola data master fasilitas global
  - Kontrol seluruh aktivitas sistem

### 4.2 Hierarki Unit Organisasi

Sistem Space.in mengimplementasikan struktur hierarki unit tiga tingkat:

- **Pusat (Level 1):** Unit tertinggi di Polinema. Ruangan milik Pusat disetujui oleh Approver Pusat yang ditentukan oleh Super Admin.
- **Jurusan (Level 2):** Unit di bawah Pusat. Memiliki parent_id yang merujuk ke unit Pusat.
- **Organisasi (Level 3):** Unit di bawah Jurusan. Memiliki parent_id yang merujuk ke unit Jurusan.

Setiap Admin Unit hanya dapat mengelola data dalam lingkup unitnya dan unit di bawahnya sesuai dengan struktur parent_id dalam hierarki.

---

## 5. Kebutuhan Fungsional

### 5.1 Ringkasan Use Case

| ID | Fitur | Deskripsi | Role |
|---|---|---|---|
| UC-01 | Melihat Jadwal & Ruangan | Guest melihat informasi detail ruangan dan ketersediaan waktu melalui kalender real-time. | Guest |
| UC-02 | Login | Autentikasi pengguna untuk masuk ke sistem sesuai role masing-masing. | Semua User |
| UC-03 | Ajukan Peminjaman Ruang | Peminjam mencari ruangan, isi detail acara, pilih workflow, dan upload dokumen. | Peminjam |
| UC-03a | Revisi Pengajuan | Peminjam merevisi dan mengajukan kembali pengajuan yang ditolak Approver. | Peminjam |
| UC-04 | Review Pengajuan Masuk | Approver memeriksa, menyetujui, atau menolak pengajuan disertai alasan. | Approver |
| UC-05 | Setup Workflow | Admin Unit menyusun rantai Approver dan dokumen persyaratan per ruangan. | Admin Unit |
| UC-06 | Kelola Akun User Internal | Admin Unit menambah, edit, dan hapus akun user dalam lingkup unitnya. | Admin Unit |
| UC-07 (Admin) | Kelola Ruangan Unit | Admin Unit mengelola data gedung dan ruangan dalam wilayah unitnya. | Admin Unit |
| UC-07 (Super) | Kelola Data User | Super Admin mengelola akun Admin Unit dan Approver Pusat. | Super Admin |
| UC-08 | Kelola Data Unit | Super Admin mengelola struktur hierarki unit dan organisasi secara global. | Super Admin |
| UC-09 | Kelola Data Fasilitas | Super Admin mengelola data master gedung dan ruangan secara global. | Super Admin |

### 5.2 Detail Use Case

#### **UC-01 — Melihat Jadwal dan Ruangan**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-01 |
| **Nama** | Melihat Jadwal dan Ruangan |
| **Aktor Primer** | Guest |
| **Tipe** | Primary |
| **Deskripsi** | Guest melihat informasi detail ruangan dan ketersediaan waktu untuk merencanakan kegiatan. |
| **Trigger** | User mengakses URL website Space.in. |
| **Pre-kondisi** | User berada pada halaman welcome yang berisi kalender dan daftar ruangan. |
| **Alur Normal** | 1. Masuk ke website Space.in; sistem menampilkan landing page dengan kalender dan jadwal ruangan tersedia.<br>2. Guest klik navigasi Ruangan.<br>3. Sistem menampilkan daftar gedung yang tersedia.<br>4. Guest klik salah satu gedung.<br>5. Sistem menampilkan detail ruangan di gedung tersebut. |
| **Alur Alternatif** | Jika sistem gagal memuat data jadwal akibat masalah koneksi, muncul notifikasi 'Gagal memuat kalender' dan opsi muat ulang. |
| **Post-kondisi (Berhasil)** | Guest mendapatkan informasi akurat mengenai status dan rincian ruangan. |
| **Post-kondisi (Gagal)** | Menampilkan notifikasi 'Gagal memuat kalender'. |

#### **UC-02 — Login**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-02 |
| **Nama** | Login |
| **Aktor Primer** | User (semua role) |
| **Tipe** | Primary |
| **Deskripsi** | Guest login untuk masuk ke aplikasi Space.in. |
| **Trigger** | Guest membuka halaman login dan memiliki akun terdaftar. |
| **Pre-kondisi** | Guest telah berada di halaman login dan memiliki akun. |
| **Alur Normal** | 1. Masuk ke halaman login.<br>2. Mengisi formulir login (username/email dan password).<br>3. Klik tombol login.<br>4. Sistem memvalidasi kredensial user.<br>5. Login berhasil.<br>6. Sistem menampilkan Dashboard sesuai role user. |
| **Alur Alternatif** | Jika validasi gagal atau login tidak sesuai database, sistem menampilkan notifikasi error 'Username atau password salah'. |
| **Post-kondisi (Berhasil)** | User berhasil masuk ke sistem dan diarahkan ke dashboard sesuai role. |
| **Post-kondisi (Gagal)** | User menerima notifikasi 'Username atau password salah'. |

#### **UC-03 — Ajukan Peminjaman Ruang**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-03 |
| **Nama** | Ajukan Peminjaman Ruang |
| **Aktor Primer** | User Biasa (Peminjam) |
| **Aktor Sekunder** | Approver |
| **Tipe** | Primary |
| **Relasi Include** | Upload Dokumen |
| **Relasi Extend** | Revisi Dokumen Ditolak (UC-03a) |
| **Deskripsi** | Peminjam mencari ruangan, memeriksa jadwal melalui kalender real-time, mengisi detail acara, memilih alur birokrasi (workflow), dan mengunggah berkas persyaratan untuk mendapatkan izin penggunaan fasilitas. |
| **Trigger** | User memilih menu 'Ajukan Peminjaman'. |
| **Pre-kondisi** | Peminjam telah berhasil login ke platform Space.in. |
| **Alur Normal** | 1. Peminjam membuka menu pencarian ruangan.<br>2. Memilih ruangan, tanggal, dan durasi waktu pada kalender ketersediaan.<br>3. Mengisi formulir detail acara: nama acara, deskripsi, estimasi peserta.<br>4. Memilih alur persetujuan (Workflow) yang sesuai dengan otoritas ruangan.<br>5. Mengunggah dokumen persyaratan yang diwajibkan.<br>6. Menekan tombol 'Ajukan'.<br>7. Sistem memvalidasi data pengajuan dan memastikan integritas data transaksi (Anti-Overlap check).<br>8. Sistem menerapkan Soft-Lock pada jadwal ruangan.<br>9. Menampilkan notifikasi bahwa pengajuan telah berhasil.<br>10. Kembali ke menu pencarian ruangan. |
| **Alur Alternatif** | • Jika dokumen tidak lengkap, Approver menolak; Peminjam mengajukan revisi (UC-03a).<br>• Jika jadwal bentrok (Anti-Overlap), Peminjam dapat mengajukan ulang untuk ruangan/waktu lain. |
| **Post-kondisi (Berhasil)** | Pengajuan tersimpan dan masuk ke antrean Admin Unit serta Pejabat Berwenang sesuai hirarki yang telah dikonfigurasi (Pusat, Jurusan, atau Organisasi). |
| **Post-kondisi (Gagal)** | Sistem menampilkan pesan error sesuai jenis kegagalan. |

#### **UC-03a — Peminjam Mengajukan Revisi**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-03a |
| **Nama** | Peminjam Mengajukan Revisi |
| **Aktor Primer** | User Biasa (Peminjam) |
| **Aktor Sekunder** | Approver |
| **Tipe** | Alternatif (Extend dari UC-03) |
| **Deskripsi** | Sistem mengirimkan alert bahwa Approver menolak pengajuan karena dokumen belum lengkap/salah. Peminjam merevisi dan mengajukan kembali. |
| **Trigger** | Peminjam mendapat notifikasi penolakan dan menekan 'Revisi / Ajukan Kembali'. |
| **Pre-kondisi** | Peminjam telah login dan pengajuannya ditolak oleh Approver. |
| **Alur Normal** | 1. Peminjam membuka notifikasi penolakan dan membaca catatan alasan dari Approver.<br>2. Sistem menampilkan formulir pengajuan sebelumnya beserta catatan revisi.<br>3. Peminjam melengkapi atau mengganti dokumen sesuai catatan revisi.<br>4. Peminjam menekan tombol 'Ajukan Kembali'.<br>5. Sistem memvalidasi kelengkapan dokumen yang diperbarui.<br>6. Pengajuan kembali masuk ke antrean Approver.<br>7. Menampilkan notifikasi bahwa pengajuan revisi berhasil.<br>8. Sistem kembali menampilkan menu pencarian ruangan. |
| **Sub Alur** | Sistem mencatat log bahwa terdapat percobaan pengajuan kembali/revisi beserta timestamp-nya. |
| **Alur Alternatif** | Jika setelah mengajukan kembali tetap ditolak, Peminjam dapat merevisi kembali sesuai catatan Approver. Tidak ada batas maksimum revisi. |
| **Post-kondisi (Berhasil)** | Pengajuan revisi tersimpan dan kembali masuk ke antrean Approver. Bila disetujui dan seluruh rantai selesai, status berubah Hard-Lock dan sistem menerbitkan Surat Izin PDF + QR Code. |

#### **UC-04 — Review Pengajuan Masuk (Approver)**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-04 |
| **Nama** | Review Pengajuan Masuk |
| **Aktor Primer** | Approver (Pejabat Berwenang) |
| **Aktor Sekunder** | User Biasa (Peminjam) |
| **Tipe** | Primary |
| **Deskripsi** | Approver memeriksa detail pengajuan yang masuk sesuai urutan workflow, lalu memutuskan menyetujui atau menolak beserta alasannya. |
| **Trigger** | Approver menerima notifikasi bahwa ada pengajuan peminjaman yang perlu ditinjau. |
| **Pre-kondisi** | Approver telah login dan terdapat pengajuan yang menunggu keputusan di antrean meja kerjanya. |
| **Alur Normal** | 1. Approver membuka menu 'Review Pengajuan Masuk' pada dashboard.<br>2. Sistem menampilkan daftar pengajuan yang menunggu persetujuan.<br>3. Approver memilih satu pengajuan untuk diperiksa secara detail.<br>4. Sistem menampilkan: data peminjam, nama acara, ruangan, jadwal, durasi, dokumen lampiran, dan riwayat persetujuan sebelumnya.<br>5. Approver memeriksa seluruh detail dan dokumen.<br>6. Approver memilih tindakan: 'Setuju' atau 'Tolak'.<br>7. Sistem menyimpan keputusan.<br>8. Sistem menampilkan kembali halaman Review Pengajuan. |
| **Sub Alur Setuju** | Jika Approver adalah yang terakhir dalam rantai, sistem mengubah status menjadi Hard-Lock. |
| **Sub Alur Tolak** | Approver wajib mengisi catatan alasan penolakan. Sistem memvalidasi isian catatan sebelum keputusan diproses. |
| **Alur Alternatif** | • Jika Approver setuju tetapi bukan yang terakhir, sistem meneruskan pengajuan ke Approver berikutnya.<br>• Jika Approver menolak tanpa mengisi catatan, sistem menampilkan peringatan: 'Alasan penolakan wajib diisi sebelum keputusan dapat dikirim.' |
| **Post-kondisi (Berhasil)** | Pengajuan diteruskan ke Approver berikutnya, ATAU status berubah Hard-Lock dan sistem menerbitkan Surat Izin PDF + QR Code Validasi jika seluruh rantai selesai. |
| **Post-kondisi (Gagal)** | Sistem menampilkan peringatan 'Alasan penolakan wajib diisi'. |

#### **UC-05 — Setup Alur Persetujuan / Workflow (Admin Unit)**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-05 |
| **Nama** | Setup Alur Persetujuan / Workflow |
| **Aktor Primer** | Admin Unit (Lokal) |
| **Tipe** | Primary |
| **Relasi Include** | Login |
| **Deskripsi** | Admin Unit menyusun rantai urutan Approver yang harus menyetujui pengajuan beserta persyaratan dokumen di setiap langkahnya. Disimpan sebagai data di database (bukan hardcode), bersifat dinamis. |
| **Trigger** | Admin Unit memilih menu 'Setup Alur Persetujuan / Workflow'. |
| **Pre-kondisi** | Admin Unit telah login, data master jabatan lokal tersedia, dan ruangan yang dikonfigurasi berada di bawah kepemilikan unit Admin bersangkutan. |
| **Alur Normal** | 1. Admin Unit membuka menu 'Setup Alur Persetujuan / Workflow'.<br>2. Sistem menampilkan daftar workflow yang ada beserta opsi membuat konfigurasi baru.<br>3. Admin Unit memilih ruangan atau jenis pengajuan yang akan dikonfigurasi.<br>4. Admin Unit menambahkan langkah-langkah workflow secara berurutan dengan memilih jabatan Approver dari daftar master jabatan lokal.<br>5. Untuk setiap langkah, Admin menentukan dokumen apa saja yang harus diserahkan ke Approver.<br>6. Admin Unit menyimpan konfigurasi workflow.<br>7. Sistem memvalidasi kelengkapan dan konsistensi (minimal satu langkah Approver).<br>8. Sistem menyimpan konfigurasi ke database dan mengaktifkannya untuk pengajuan selanjutnya. |
| **Alur Alternatif** | Jika konfigurasi disimpan tanpa satu pun langkah Approver, sistem menampilkan peringatan dan menolak penyimpanan. |
| **Post-kondisi (Berhasil)** | Konfigurasi alur persetujuan tersimpan di database dan aktif digunakan sebagai acuan proses persetujuan pengajuan peminjaman pada ruangan terkait. |

#### **UC-06 — Kelola Akun User Internal (Admin Unit)**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-06 |
| **Nama** | Kelola Akun User Internal |
| **Aktor Primer** | Admin Unit (Lokal) |
| **Tipe** | Primary |
| **Relasi Include** | Login |
| **Deskripsi** | Admin Unit mengelola akun pengguna internal di bawah cakupan wilayahnya: membuat akun baru, menetapkan peran (Admin Unit, Peminjam, Approver), dan menonaktifkan akun. |
| **Trigger** | Admin Unit memilih menu 'Kelola Akun User Internal'. |
| **Pre-kondisi** | Admin Unit telah login dengan hak akses Admin Unit yang valid. |
| **Alur Normal** | 1. Admin Unit membuka menu 'Kelola User'.<br>2. Sistem menampilkan daftar user dalam lingkup unit Admin bersangkutan.<br>3. Admin Unit memilih aksi: Tambah User Baru / Edit User / Hapus User.<br>4. Sistem menyimpan perubahan dan memperbarui referensi user di seluruh modul terkait. |
| **Sub Alur Tambah** | Admin mengisi nama user, email, password, unit (Organisasi), dan role. Kemudian tekan simpan. |
| **Sub Alur Edit** | Admin mengubah identitas user yang dipilih dan menyimpan perubahan. |
| **Sub Alur Hapus** | Admin memilih user tidak aktif dan mengonfirmasi penghapusan. |
| **Alur Alternatif** | • Jika menghapus jabatan yang terkait workflow aktif, sistem menampilkan peringatan dan membatalkan penghapusan.<br>• Jika nama/email sudah ada, sistem meminta Admin menggunakan nama/email berbeda. |
| **Post-kondisi (Berhasil)** | Data master user lokal berhasil diperbarui dan siap digunakan dalam konfigurasi workflow. |

#### **UC-07 (Admin Unit) — Kelola Ruangan Unit**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-07 (Admin) |
| **Nama** | Kelola Ruangan Unit |
| **Aktor Primer** | Admin Unit |
| **Tipe** | Primary |
| **Relasi Include** | Login |
| **Deskripsi** | Admin Unit mengelola seluruh data gedung dan ruangan untuk wilayah unitnya, termasuk menambah ruangan baru dan mengedit informasi. |
| **Trigger** | Admin Unit memilih menu 'Manajemen Ruangan'. |
| **Pre-kondisi** | Admin Unit telah login dengan hak akses Admin Unit. |
| **Alur Normal** | 1. Admin Unit membuka menu 'Manajemen Ruangan'.<br>2. Sistem menampilkan seluruh data ruangan yang terdaftar.<br>3. Admin Unit memilih aksi: Ruangan Baru / Edit Data / Nonaktifkan Ruangan.<br>4. Sistem menjalankan proses pengelolaan data yang dipilih.<br>5. Sistem menampilkan hasil perubahan. |
| **Sub Alur Tambah** | Admin mengisi formulir data (nama, lokasi, kapasitas, fasilitas, gambar). Sistem memvalidasi kelengkapan. |
| **Sub Alur Edit** | Admin memperbarui informasi yang diperlukan dan menyimpan. |
| **Sub Alur Nonaktifkan** | Admin mengonfirmasi tindakan; sistem mengubah status ruangan menjadi tidak aktif. |
| **Alur Alternatif** | Jika data formulir tidak lengkap, sistem menampilkan peringatan agar formulir dilengkapi. |
| **Post-kondisi (Berhasil)** | Data ruangan berhasil diperbarui sehingga Admin Unit dapat mulai mengonfigurasi workflow dan jadwal ruangan tersebut. |

#### **UC-07 (Super Admin) — Kelola Data User**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-07 (Super) |
| **Nama** | Kelola Data User |
| **Aktor Primer** | Super Admin (Pusat) |
| **Tipe** | Primary |
| **Relasi Include** | Login |
| **Deskripsi** | Super Admin mengelola akun Admin Unit dari masing-masing unit/jurusan/organisasi serta menetapkan Approver tingkat Pusat. |
| **Trigger** | Super Admin memilih menu 'Kelola Data Admin Unit dan Approver Pusat'. |
| **Pre-kondisi** | Super Admin telah login dan data unit/organisasi tersedia di sistem. |
| **Alur Normal** | 1. Super Admin membuka menu 'Kelola Data Admin Unit dan Approver Pusat'.<br>2. Sistem menampilkan daftar akun Admin Unit dan Approver Pusat.<br>3. Super Admin memilih aksi: Tambah Akun Baru / Edit Data Akun / Nonaktifkan Akun. |
| **Sub Alur Tambah Admin** | Super Admin mengisi data akun (nama, email, unit yang dikelola) dan menetapkan peran Admin Unit. |
| **Sub Alur Tambah Approver** | Super Admin mengisi data akun dan menetapkan peran Approver dengan cakupan ruangan Pusat. |
| **Sub Alur Edit** | Super Admin mengubah informasi atau cakupan unit/peran dan menyimpan. |
| **Sub Alur Nonaktifkan** | Super Admin mengonfirmasi; sistem menonaktifkan akun. |
| **Validasi Sistem** | Memastikan setiap Admin Unit hanya memiliki cakupan kelola pada unit yang ditetapkan sesuai parent_id. Sistem memvalidasi dan memastikan tidak ada duplikasi email. Kredensial dikirim ke email pengguna. |
| **Alur Alternatif** | • Email duplikat: sistem meminta email berbeda.<br>• Menonaktifkan Admin Unit dengan workflow aktif: sistem menampilkan peringatan sebelum konfirmasi. |
| **Post-kondisi (Berhasil)** | Akun berhasil dibuat/diperbarui/dinonaktifkan. Admin Unit baru dapat langsung login dan mengelola lingkup unitnya. |

#### **UC-08 — Kelola Data Unit (Super Admin)**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-08 |
| **Nama** | Kelola Data Unit |
| **Aktor Primer** | Super Admin (Pusat) |
| **Tipe** | Primary |
| **Relasi Include** | Login |
| **Deskripsi** | Super Admin mengelola struktur hierarki unit dan organisasi secara global: penambahan unit baru, level hierarki, relasi parent-child, pembaruan, dan penonaktifan. |
| **Trigger** | Super Admin memilih menu 'Unit' pada panel administrasi pusat. |
| **Pre-kondisi** | Super Admin telah login dengan hak akses Super Admin. |
| **Alur Normal** | 1. Super Admin membuka menu 'Unit'.<br>2. Sistem menampilkan struktur pohon (tree) seluruh unit dan organisasi.<br>3. Super Admin memilih aksi: Tambah Unit Baru / Edit Data Unit / Nonaktifkan Unit. |
| **Sub Alur Tambah** | Super Admin mengisi nama unit, memilih level hierarki (Pusat/Jurusan/Organisasi), dan menentukan unit induk (parent_id) jika berlaku. Sistem memvalidasi konsistensi hierarki. Sistem menyimpan ke database. |
| **Sub Alur Edit** | Super Admin memperbarui nama atau level unit. |
| **Sub Alur Nonaktifkan** | Super Admin mengonfirmasi; sistem mengubah status unit menjadi tidak aktif. |
| **Alur Alternatif** | Struktur hierarki yang tersimpan digunakan sistem untuk memfilter hak akses Admin Unit. Setiap Admin Unit hanya mengelola data dalam lingkup unitnya dan unit di bawahnya. |
| **Post-kondisi (Berhasil)** | Struktur hierarki unit berhasil diperbarui di database dan langsung berdampak pada logika filter akses seluruh Admin Unit. |

#### **UC-09 — Kelola Data Fasilitas (Super Admin)**

| Atribut | Nilai |
|---|---|
| **ID Use Case** | UC-09 |
| **Nama** | Kelola Data Fasilitas |
| **Aktor Primer** | Super Admin (Pusat) |
| **Tipe** | Primary |
| **Relasi Include** | Login |
| **Deskripsi** | Super Admin mengelola seluruh data master gedung dan ruangan secara global, termasuk menambah, mengedit, menetapkan kepemilikan ruangan ke unit tertentu (unit_id), dan menonaktifkan fasilitas. |
| **Trigger** | Super Admin memilih menu 'Kelola Data Fasilitas'. |
| **Pre-kondisi** | Super Admin telah login dengan hak akses Super Admin. |
| **Alur Normal** | 1. Super Admin membuka menu 'Kelola Data Fasilitas'.<br>2. Sistem menampilkan seluruh data gedung dan ruangan secara global.<br>3. Super Admin memilih aksi: Tambah Gedung/Ruangan Baru / Edit Data / Nonaktifkan.<br>4. Sistem menjalankan proses pengelolaan data.<br>5. Sistem menampilkan hasil perubahan. |
| **Sub Alur Tambah** | Super Admin mengisi formulir (nama, lokasi, kapasitas, fasilitas, gambar). Menetapkan kepemilikan dengan memilih unit_id. Sistem memvalidasi kelengkapan dan menyimpan. |
| **Sub Alur Edit** | Super Admin memperbarui informasi yang diperlukan. |
| **Sub Alur Nonaktifkan** | Super Admin mengonfirmasi; sistem mengubah status fasilitas menjadi tidak aktif. |
| **Alur Alternatif** | Jika data tidak lengkap, sistem menampilkan peringatan. |
| **Post-kondisi (Berhasil)** | Data master gedung/ruangan diperbarui secara global dan kepemilikan ditetapkan ke unit sesuai, sehingga Admin Unit dapat mulai mengonfigurasi workflow. |

---

## 6. Spesifikasi Fitur Detail Per Role

### 6.1 Fitur Guest

- **F-G01 Akses Landing Page:** Sistem menyediakan halaman utama yang dapat diakses tanpa autentikasi, menampilkan informasi sistem dan navigasi ke fitur publik.
- **F-G02 Melihat Informasi Ruangan:** Sistem menampilkan daftar ruangan dengan detail: nama ruangan, kapasitas, fasilitas tersedia, lokasi/gedung, dan foto ruangan.
- **F-G03 Kalender Ketersediaan Real-time:** Sistem menyediakan fitur kalender yang menampilkan jadwal penggunaan ruangan secara real-time, memungkinkan pengecekan ketersediaan tanpa login.
- **F-G04 Navigasi Gedung:** Sistem menampilkan daftar gedung yang dapat diklik untuk melihat detail ruangan di gedung tersebut.
- **F-G05 Akses Halaman Login:** Sistem menyediakan tautan ke halaman autentikasi dari landing page.

### 6.2 Fitur Peminjam (User)

- **F-P01 Autentikasi:** Login dan logout dari sistem menggunakan kredensial yang terdaftar.
- **F-P02 Kelola Profil:** Mengelola data profil akun (nama, email, foto profil, dan informasi kontak).
- **F-P03 Pencarian & Filter Ruangan:** Mencari ruangan berdasarkan kapasitas, fasilitas, gedung, dan ketersediaan jadwal.
- **F-P04 Kalender Ketersediaan Interaktif:** Melihat dan berinteraksi dengan kalender jadwal ruangan untuk memilih tanggal dan durasi peminjaman.
- **F-P05 Formulir Pengajuan Peminjaman:** Mengisi formulir dengan nama acara, deskripsi kegiatan, estimasi jumlah peserta, tanggal, waktu mulai, dan waktu selesai.
- **F-P06 Pemilihan Workflow:** Memilih alur persetujuan yang sesuai dengan otoritas ruangan yang dipinjam.
- **F-P07 Upload Dokumen:** Mengunggah dokumen persyaratan (proposal, surat permohonan, dll.) dalam format yang ditentukan.
- **F-P08 Tracking Status Pengajuan:** Memantau status pengajuan secara real-time (Menunggu, Dalam Proses, Disetujui, Ditolak) beserta posisi dalam rantai workflow.
- **F-P09 Notifikasi:** Menerima notifikasi sistem saat ada perubahan status pengajuan (disetujui, ditolak, atau memerlukan revisi).
- **F-P10 Revisi Pengajuan:** Melengkapi atau mengganti dokumen berdasarkan catatan Approver dan mengajukan kembali tanpa mengulang dari awal.
- **F-P11 Unduh Surat Izin:** Mengunduh Surat Izin Peminjaman dalam format PDF yang dilengkapi QR Code Validasi setelah pengajuan disetujui penuh.

### 6.3 Fitur Approver

- **F-A01 Autentikasi:** Login dan logout dari sistem.
- **F-A02 Dashboard Antrian:** Melihat daftar pengajuan yang menunggu persetujuan dari Approver bersangkutan, diurutkan berdasarkan tanggal masuk.
- **F-A03 Detail Pengajuan:** Melihat informasi lengkap pengajuan: data peminjam, nama acara, ruangan, jadwal, durasi, dokumen lampiran, dan riwayat persetujuan sebelumnya dalam rantai workflow.
- **F-A04 Unduh Dokumen Lampiran:** Mengunduh dokumen persyaratan yang diunggah peminjam untuk diperiksa.
- **F-A05 Keputusan Setuju:** Memberikan persetujuan atas pengajuan; sistem meneruskan ke Approver berikutnya atau men-trigger Hard-Lock jika Approver terakhir.
- **F-A06 Keputusan Tolak dengan Catatan:** Menolak pengajuan disertai catatan alasan yang wajib diisi; sistem mengirim notifikasi ke Peminjam.
- **F-A07 Riwayat Keputusan:** Melihat histori keputusan yang pernah dibuat.

### 6.4 Fitur Admin Unit

- **F-AU01 Autentikasi:** Login dan logout dari sistem.
- **F-AU02 Manajemen User Internal:** CRUD (Create, Read, Update, Delete) akun pengguna dalam lingkup unit, termasuk penetapan role (Peminjam/Approver/Admin Unit bawahan).
- **F-AU03 Konfigurasi Workflow:** Membuat dan mengelola alur persetujuan dinamis untuk setiap ruangan: menentukan urutan Approver dan dokumen persyaratan per langkah.
- **F-AU04 Manajemen Ruangan Unit:** CRUD data ruangan dalam wilayah unit (nama, lokasi, kapasitas, fasilitas, gambar, status aktif/nonaktif).
- **F-AU05 Monitoring Pengajuan:** Memantau seluruh pengajuan peminjaman dalam lingkup unitnya beserta status dan histori persetujuannya.
- **F-AU06 Notifikasi Unit:** Menerima notifikasi terkait aktivitas peminjaman dalam unit yang dikelola.

### 6.5 Fitur Super Admin

- **F-SA01 Autentikasi:** Login dan logout dari sistem.
- **F-SA02 Kelola User Global:** CRUD akun Admin Unit dan Approver Pusat; memastikan setiap Admin Unit hanya memiliki cakupan pada unit yang ditetapkan sesuai parent_id.
- **F-SA03 Kelola Struktur Unit:** CRUD unit dan organisasi; mengatur level hierarki (Pusat/Jurusan/Organisasi) dan relasi parent-child antar unit.
- **F-SA04 Kelola Data Fasilitas Global:** CRUD data master gedung dan ruangan secara global; menetapkan kepemilikan ruangan ke unit tertentu (unit_id).
- **F-SA05 Monitoring Global:** Memantau seluruh aktivitas sistem di semua unit.
- **F-SA06 Kontrol Penuh Sistem:** Hak akses penuh untuk mengontrol seluruh konfigurasi dan data sistem.

---

## 7. Kebutuhan Non-Fungsional

| No. | Kategori | Kebutuhan Pengguna | Spesifikasi Teknis |
|---|---|---|---|
| 1 | Aksesibilitas & Ketersediaan | Layanan dapat diakses 24/7 dari berbagai perangkat (komputer & smartphone). | Sistem berjalan pada browser web (web-based) tanpa instalasi tambahan. Responsif di berbagai ukuran layar. |
| 2 | Performa & Efisiensi | Proses pencarian dan pengajuan izin yang cepat, terutama pada periode sibuk kalender akademik. | Halaman utama dan kalender harus termuat dalam waktu yang wajar. Backend Laravel harus mengoptimalkan query database untuk data jadwal. |
| 3 | Keamanan Privasi | Dokumen pengajuan dan identitas pengguna dijaga kerahasiaannya dan hanya dapat diakses pihak berwenang. | Autentikasi berbasis role (RBAC). Dokumen hanya dapat diakses oleh Peminjam terkait dan Approver dalam rantai workflow. Enkripsi data sensitif. |
| 4 | Kemudahan Penggunaan (Usability) | Alur kerja intuitif sehingga seluruh sivitas akademika dapat memproses perizinan tanpa pelatihan khusus. | Antarmuka yang bersih, konsisten, dan menggunakan bahasa Indonesia. Pesan error yang informatif dan jelas. |
| 5 | Validasi Data (Integritas) | Sistem harus mencegah bentrok jadwal dan menjamin keabsahan surat izin. | Logika Anti-Overlap pada setiap pengajuan. Soft-Lock selama proses berlangsung. Hard-Lock setelah disetujui penuh. QR Code unik pada setiap surat izin. |
| 6 | Skalabilitas | Sistem dapat menangani pertumbuhan data pengguna, ruangan, dan transaksi seiring bertambahnya unit. | Struktur basis data yang skalabel dengan penggunaan parent_id dan unit_id untuk hierarki dinamis. |
| 7 | Auditabilitas | Histori perizinan harus dapat dilacak secara sistematis oleh pihak berwenang. | Sistem menyimpan log seluruh aktivitas: pengajuan, keputusan, revisi, beserta timestamp. Arsip otomatis untuk transparansi hierarki kampus. |

---

## 8. Batasan, Asumsi, dan Ketergantungan

### 8.1 Batasan Sistem

- Sistem hanya dapat digunakan jika terdapat koneksi internet yang stabil.
- Bergantung pada performa server dan hosting yang digunakan.
- Sistem tidak menangani proses pembayaran; jika ada biaya, dilakukan di luar sistem.
- Hak akses dibatasi secara ketat berdasarkan role pengguna (RBAC).
- Sistem hanya dirancang untuk digunakan dalam lingkungan Politeknik Negeri Malang.
- Validasi QR Code membutuhkan perangkat dengan kamera/scanner yang terhubung ke platform.
- Ketergantungan pada input user — human error masih dimungkinkan pada data yang diisikan pengguna.

### 8.2 Asumsi

- Pengguna memiliki perangkat yang terhubung ke internet.
- Data master unit, ruangan, dan user sudah tersedia di sistem sebelum operasional dimulai.
- Setiap pengajuan mengikuti alur workflow yang telah dikonfigurasi oleh Admin Unit.
- Approver akan memproses pengajuan sesuai urutan yang ditetapkan dalam workflow.
- QR Code yang diterbitkan sistem digunakan sebagai satu-satunya bukti validasi resmi peminjaman.
- Sistem berjalan pada browser web modern tanpa memerlukan instalasi tambahan.

### 8.3 Ketergantungan Teknis

- **Server Hosting:** Sistem bergantung pada ketersediaan dan performa server tempat aplikasi di-deploy.
- **Database:** Sistem bergantung pada database relasional yang menyimpan seluruh data transaksi, user, unit, dan workflow.
- **Koneksi Jaringan (TCP/IP):** Semua fitur memerlukan koneksi jaringan aktif.
- **Framework Laravel:** Seluruh logika backend dibangun menggunakan Laravel.
- **QR Code Library:** Sistem bergantung pada library pembangkit QR Code untuk penerbitan surat izin.
- **PDF Generator:** Sistem bergantung pada library pembangkit PDF untuk Surat Izin Peminjaman.

---

## 9. Kebutuhan Antarmuka

### 9.1 Antarmuka Pengguna (UI)

- Antarmuka berbasis web yang responsif dan dapat diakses melalui browser desktop maupun mobile.
- Bahasa antarmuka: Bahasa Indonesia.
- Navigasi intuitif dengan menu yang jelas untuk setiap role pengguna.
- Pesan error dan notifikasi yang informatif dalam Bahasa Indonesia.
- Kalender interaktif untuk pemilihan jadwal peminjaman.
- Dashboard yang menampilkan ringkasan status dan antrian secara sekilas.

### 9.2 Antarmuka Perangkat Keras

- Perangkat komputer desktop/laptop dengan browser modern (Chrome, Firefox, Edge, Safari terbaru).
- Perangkat smartphone (iOS/Android) dengan browser mobile untuk akses portabel.
- Perangkat scanner QR Code (kamera smartphone atau barcode scanner) untuk validasi di hari pelaksanaan.

### 9.3 Antarmuka Perangkat Lunak

- **Framework Backend:** Laravel (PHP).
- **Database:** MySQL/PostgreSQL (relasional).
- **Frontend:** HTML, CSS, JavaScript (dengan framework frontend sesuai implementasi tim).
- **Koneksi:** HTTP/HTTPS melalui protokol TCP/IP.
- **Format Dokumen Output:** PDF dengan QR Code tertanam.

---

## 10. Aturan Bisnis dan Logika Sistem Kritis

### 10.1 Logika Anti-Overlap (Pencegahan Bentrok Jadwal)

Sistem wajib mengimplementasikan logika Anti-Overlap yang memastikan tidak ada dua pengajuan yang memiliki ruangan dan rentang waktu yang sama secara bersamaan. Aturan:

- Saat pengajuan baru dibuat, sistem memeriksa semua booking dengan status Soft-Lock dan Hard-Lock pada ruangan dan waktu yang diminta.
- Jika terdapat tumpang tindih, sistem menolak pengajuan dan menginformasikan kepada Peminjam untuk memilih waktu/ruangan lain.
- Pengajuan yang ditolak karena bentrok tidak memasuki antrean Approver.

### 10.2 Mekanisme Soft-Lock dan Hard-Lock

- **Soft-Lock:** Diterapkan secara otomatis saat pengajuan berhasil disubmit dan memasuki antrean persetujuan. Status ini mengunci jadwal sementara agar tidak dapat diambil pengguna lain, namun dapat dilepas jika pengajuan ditolak atau kedaluwarsa.
- **Hard-Lock:** Diterapkan saat seluruh rantai persetujuan dalam workflow telah dipenuhi. Status ini mengunci jadwal secara permanen dan memicu penerbitan Surat Izin PDF + QR Code.

### 10.3 Penerbitan Surat Izin dan QR Code

- Surat Izin hanya diterbitkan setelah status booking mencapai Hard-Lock.
- Setiap surat izin memiliki QR Code unik yang berisi informasi terenkripsi: ID booking, ruangan, tanggal, dan identitas Peminjam.
- QR Code dapat dipindai melalui fitur scanner pada platform Space.in untuk verifikasi keabsahan izin secara instan oleh pengelola gedung.

### 10.4 Aturan Workflow

- Setiap workflow harus memiliki minimal satu langkah Approver; sistem menolak penyimpanan workflow tanpa Approver.
- Approver diproses secara berurutan sesuai urutan yang dikonfigurasi Admin Unit; Approver berikutnya hanya dapat mengakses pengajuan setelah Approver sebelumnya menyetujui.
- Jika Approver menolak, seluruh rantai berhenti dan pengajuan dikembalikan ke Peminjam dengan catatan revisi.
- Workflow bersifat dinamis dan tersimpan di database; perubahan pada workflow hanya berlaku untuk pengajuan baru (tidak retroaktif).

### 10.5 Aturan Hierarki Unit

- Organisasi harus memiliki unit Jurusan sebagai parent.
- Jurusan harus memiliki unit Pusat sebagai parent.
- Admin Unit hanya dapat mengelola data (user, ruangan, workflow) dalam lingkup unitnya dan unit di bawahnya sesuai parent_id.
- Super Admin adalah satu-satunya yang dapat mengubah struktur hierarki unit.

### 10.6 Manajemen Log dan Audit Trail

- Sistem mencatat log seluruh aktivitas kritis: pengajuan dibuat, keputusan Approver (setuju/tolak + catatan), revisi diajukan, status berubah, surat diterbitkan — semua beserta timestamp.
- Log tidak dapat dihapus oleh pengguna manapun (immutable audit trail).
- Log dapat dilihat oleh Super Admin untuk keperluan audit dan monitoring.

---

## 11. Metodologi Pengembangan

### 11.1 Extreme Programming (XP)

Proyek Space.in dikembangkan menggunakan metodologi Extreme Programming (XP). Pemilihan ini didasari oleh:

- Proyek dikerjakan secara kolaboratif dalam tim kecil dengan batasan waktu pengerjaan yang terbatas.
- XP memungkinkan iterasi cepat mulai dari tahap perencanaan user story hingga deployment.
- XP terbukti mampu mempercepat implementasi tanpa mengurangi kualitas, sekaligus menjaga kualitas kode melalui struktur basis data yang skalabel dan terorganisir.

### 11.2 Tahapan Implementasi XP

1. **Planning (Perencanaan):** Mendefinisikan user stories berdasarkan hasil wawancara dan analisis dokumen. Memprioritaskan fitur berdasarkan nilai bisnis.

2. **Design (Perancangan):** Merancang arsitektur sistem, struktur database, dan antarmuka pengguna berdasarkan spesifikasi dalam dokumen ini.

3. **Coding (Implementasi):** Pengembangan fitur secara iteratif menggunakan Laravel. Pair programming dan code review untuk menjaga kualitas kode.

4. **Testing (Pengujian):** Unit testing dan integration testing untuk memastikan setiap fitur berjalan sesuai spesifikasi. User acceptance testing bersama stakeholder.

5. **Release (Deployment):** Deployment ke server hosting. Monitoring performa dan perbaikan bug pasca-rilis.

---

## 12. Matriks Keterlacakan Kebutuhan

Matriks berikut menghubungkan masalah bisnis yang teridentifikasi dengan kebutuhan fungsional dan use case yang menjadi solusinya:

| Masalah Bisnis | Kebutuhan Fungsional | Use Case | Fitur ID |
|---|---|---|---|
| Inefisiensi birokrasi (banyak pihak fisik) | Digitalisasi alur persetujuan berbasis workflow dinamis terintegrasi satu pintu. | UC-03, UC-04, UC-05 | F-P05, F-P06, F-A05, F-AU03 |
| Blind Spot jadwal (tidak ada kalender real-time) | Kalender ketersediaan ruangan real-time, Soft-Lock Anti-Overlap. | UC-01, UC-03 | F-G03, F-P04, F-P03 |
| Double-booking (pencatatan manual) | Logika Anti-Overlap otomatis dan mekanisme Soft-Lock/Hard-Lock. | UC-03 | F-P05 (Anti-Overlap) |
| Minimnya transparansi status dokumen | Tracking status pengajuan real-time dan notifikasi perubahan status. | UC-03, UC-04 | F-P08, F-P09 |
| Verifikasi lemah (tanda tangan fisik) | Surat izin digital dengan QR Code unik dan fitur scan validasi. | UC-04 | F-P11, F-A05 |
| Dokumen fisik rentan (hilang/rusak) | Upload dokumen digital dan arsip otomatis dengan audit trail. | UC-03, UC-03a | F-P07, F-P10 |

---

## 13. Riwayat Revisi Dokumen

| Versi | Tanggal | Penulis | Keterangan Perubahan |
|---|---|---|---|
| 1.0 | Mei 2026 | Tim Pengembang Space.in | Dokumen awal — mencakup seluruh use case (UC-01 s.d. UC-09), kebutuhan fungsional & non-fungsional, aturan bisnis, matriks keterlacakan, dan spesifikasi teknis. |

---

**Akhir Dokumen**

---

**Space.in PRD v1.0 | Politeknik Negeri Malang | Confidential - Internal Use Only**
