# Rencana Kerja 1: Dynamic Workflow Bridging (booking_steps Instantiation)

## 📋 Konsep Dasar
Mengimplementasikan mesin alur kerja dinamis (Workflow Engine) berbasis 3-Tier Dynamic Bridging. Rantai persetujuan di-instansiasi secara *real-time* ke dalam tabel `booking_steps` saat peminjaman dibuat, menggantikan alur statis.

## 🛠️ Algoritma Penyambungan 3-Tier:
1. **Tier 1 (Internal/Ormawa):** Langkah persetujuan dari unit ormawa peminjam sendiri (hanya berlaku jika peminjam tingkat 'Organisasi').
2. **Tier 2a (BEM Polinema):** Langkah persetujuan BEM Polinema disisipkan jika peminjam adalah 'Organisasi' dan scope acara adalah **'Lintas Jurusan'**.
3. **Tier 2b (Pemilik Ruangan):** Langkah persetujuan unit pemilik ruangan (selalu disisipkan).
4. **Tier 3 (Pusat/Wadir 3):** Langkah persetujuan Pusat (Wadir 3) disisipkan jika scope acara adalah **'Lintas Jurusan'** dan pemilik ruangan bukan Pusat.
