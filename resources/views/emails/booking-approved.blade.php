<x-mail::message>
# Peminjaman Ruangan Disetujui (Hard-Lock) 🎉

Halo **{{ $booking->user->name }}**,

Selamat! Pengajuan peminjaman ruangan Anda telah **disetujui secara keseluruhan (Hard-Lock)**. Jadwal ruangan telah resmi dikunci untuk kegiatan Anda.

**Detail Peminjaman:**
- **No. Booking:** #{{ $booking->id }}
- **Kegiatan:** {{ $booking->event_name }}
- **Ruangan:** {{ $booking->room->room_name }}
- **Tanggal:** {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
- **Waktu:** {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}

Sertifikat/Surat Izin peminjaman resmi telah diterbitkan oleh sistem dengan pengamanan kode verifikasi QR Code. Anda dapat mengunduh dokumen tersebut melalui tautan di bawah ini atau via dashboard Space.in Anda.

<x-mail::button :url="config('app.url') . '/peminjam/detail/' . $booking->id">
Lihat Detail & Unduh Surat Izin
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
