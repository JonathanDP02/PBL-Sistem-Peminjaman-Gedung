# Rencana Kerja 2: General Workflow per Unit (Penyederhanaan Arsitektur)

## 📋 Konsep Dasar
Menyederhanakan arsitektur **Space.in** dari pemetaan spesifik per ruangan menjadi **1 Alur Kerja Umum per Unit (General Workflow per Unit)**, serta menghapus fitur **Ruangan Terhubung (Connected Room)** yang redundan.

## 🛠️ Perubahan yang Dilakukan:
1. **WorkflowBridgeService:** Mengubah kueri pencarian alur kerja agar dicari menggunakan general workflow (di mana `room_id IS NULL` untuk unit tersebut).
2. **BookingController:**
   * `showBookingForm()`: Memuat alur umum untuk semua unit pemilik ruangan (di mana `room_id IS NULL`).
   * `store()`: Mencari workflow umum milik unit pemilik ruangan (`unit_id = $room->unit_id` dan `room_id IS NULL`).
3. **WorkflowController:**
   * `index()`: Mengembalikan alur umum milik unit admin. Menambahkan logika *auto-create* workflow umum jika unit admin belum memilikinya.
4. **View (Blade) Updates:**
   * `booking.blade.php`: Menyesuaikan pencarian alur di sisi JS untuk mencocokkan `w.unit_id == selectedRoom.unit_id && w.room_id == null`.
   * `workflowsIndex.blade.php`: Menghapus tab "Ruangan Terhubung" sepenuhnya beserta logika AlpineJS.
   * `workflowsBuilder.blade.php`: Mengganti daftar workflow dengan satu kartu premium ("Atur Alur Unit Saya") yang langsung mengarah ke halaman pengeditan.
