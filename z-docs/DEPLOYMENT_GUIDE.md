# 🚀 SPACE.IN PRODUCTION DEPLOYMENT GUIDE

Panduan ini berisi langkah-langkah lengkap dan *checklist* konfigurasi yang wajib dilakukan saat men-deploy aplikasi **Space.in** ke server produksi (Production/Live VPS). Dokumen ini berfungsi sebagai pengingat bagi Anda maupun AI Agent yang bekerja di proyek ini di masa mendatang.

---

## 1. Konfigurasi File Lingkungan (`.env`)

Saat aplikasi di-deploy ke server produksi, perbarui konfigurasi file `.env` dengan pengaturan keamanan dan performa maksimal berikut:

### 🔒 Keamanan Utama
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://spacein.polinema.ac.id # Ganti dengan domain asli Anda
```
> [!WARNING]
> Pastikan `APP_DEBUG` diset ke `false` agar informasi sensitif seperti *database credentials* atau *stacktrace* error tidak bocor ke publik apabila terjadi kendala sistem.

### ✉️ Integrasi SMTP Brevo (Production Mailer)
Gunakan kredensial SMTP Brevo yang Anda daftarkan agar email notifikasi benar-benar sampai ke kotak masuk Gmail/Yahoo/Outlook asli milik mahasiswa dan pejabat:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=email_akun_brevo_anda@example.com
MAIL_PASSWORD=master_key_smtp_brevo_anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="no-reply@spacein.polinema.ac.id" # Alamat pengirim resmi
MAIL_FROM_NAME="Space.in Polinema"
```

### 📦 Konfigurasi Queue (Antrean Database)
Wajib menggunakan antrean database agar respon halaman pengajuan peminjaman tetap terasa instan dan lancar:
```env
QUEUE_CONNECTION=database
```

---

## 2. Pemasangan Daemon Supervisor (Untuk Menjalankan `queue:work` 24/7)

Karena `QUEUE_CONNECTION=database` diaktifkan, server membutuhkan program latar belakang (daemon) yang bertugas memproses antrean email secara terus-menerus. Di Linux (Ubuntu/Debian), gunakan **Supervisor**.

### Langkah Instalasi di Server VPS Linux:
1. Jalankan perintah instalasi:
   ```bash
   sudo apt update
   sudo apt install supervisor
   ```
2. Buat file konfigurasi baru untuk antrean Space.in:
   ```bash
   sudo nano /etc/supervisor/conf.d/spacein-worker.conf
   ```
3. Rekatkan konfigurasi berikut (sesuaikan path `/var/www/...` dengan lokasi folder proyek Anda di server):
   ```ini
   [program:spacein-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /var/www/PBL-Sistem-Peminjaman-Gedung/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
   autostart=true
   autorestart=true
   stopasgroup=true
   killasgroup=true
   user=www-data
   numprocs=2
   redirect_stderr=true
   stdout_logfile=/var/www/PBL-Sistem-Peminjaman-Gedung/storage/logs/worker.log
   stopwaitsecs=3600
   ```
4. Simpan file tersebut, kemudian muat ulang konfigurasi Supervisor:
   ```bash
   sudo supervisorctl reread
   sudo supervisorctl update
   sudo supervisorctl start spacein-worker:*
   ```

---

## 3. Langkah Pembentukan Awal (Initial Build & Migration)

Setiap kali Anda men-deploy pembaruan pertama kali di server produksi, jalankan rangkaian perintah berikut di dalam folder proyek Anda:

1. **Instal dependensi PHP & JS untuk produksi:**
   ```bash
   composer install --no-dev --optimize-autoloader
   npm ci
   ```
2. **Jalankan migrasi database:**
   ```bash
   php artisan migrate --force
   ```
   > [!IMPORTANT]
   > Flag `--force` wajib digunakan di lingkungan produksi agar Laravel mengeksekusi migrasi tanpa memunculkan prompt konfirmasi interaktif.
3. **Build assets frontend (Vite):**
   ```bash
   npm run build
   ```

---

## 4. Checklist Optimasi Performa Produksi (Production Caching)

Untuk mempercepat respon server hingga 3x lipat, jalankan perintah caching berikut setelah deploy berhasil:

```bash
# Caching konfigurasi aplikasi
php artisan config:cache

# Caching alur routing aplikasi
php artisan route:cache

# Caching template Blade view
php artisan view:cache
```

> [!TIP]
> Setiap kali Anda melakukan perubahan pada file `.env` atau file `routes/*.php` di server produksi, Anda **wajib** membersihkan cache terlebih dahulu menggunakan perintah `php artisan cache:clear` lalu menjalankan kembali perintah caching di atas.

---

## 5. Sinkronisasi Link Media (Storage Symlink)

Pastikan folder berkas lampiran yang diunggah peminjam terhubung secara publik:
```bash
php artisan storage:link
```

---

*Dokumen ini dibuat pada Mei 2026 sebagai standar panduan deploy Space.in.*
