<x-mail::message>
# Peminjaman Ruangan Baru

Halo, ada pengajuan peminjaman ruangan baru yang perlu persetujuan Anda.

**Detail Peminjaman:**
- **Nama Acara:** {{ $booking->event_name }}
- **Ruangan:** {{ $booking->room->room_name }}
- **Tanggal:** {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
- **Waktu:** {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
- **Peminjam:** {{ $booking->user->name }}

<x-mail::button :url="config('app.url') . '/approver/meja-kerja'">
Lihat Detail & Berikan Persetujuan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
