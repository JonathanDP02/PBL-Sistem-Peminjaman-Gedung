<x-mail::message>
# Pengajuan Peminjaman Ruangan Berhasil

Halo {{ $booking->user->name }},

Pengajuan peminjaman ruangan Anda telah berhasil dikirim dan saat ini sedang menunggu persetujuan dari pihak terkait.

**Detail Peminjaman:**
- **Nama Acara:** {{ $booking->event_name }}
- **Ruangan:** {{ $booking->room->room_name }}
- **Tanggal:** {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
- **Waktu:** {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}

<x-mail::button :url="config('app.url') . '/peminjam/jadwal-saya'">
Pantau Status Peminjaman
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
