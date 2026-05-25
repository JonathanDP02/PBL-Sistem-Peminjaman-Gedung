<x-mail::message>
# Persetujuan Diperlukan: Peminjaman Ruangan

Halo **{{ $approver->name }}**,

Ada pengajuan peminjaman ruangan baru yang membutuhkan persetujuan Anda sebagai bagian dari alur kerja (workflow) persetujuan.

**Detail Pengajuan:**
- **No. Booking:** #{{ $booking->id }}
- **Kegiatan:** {{ $booking->event_name }}
- **Ruangan:** {{ $booking->room->room_name }}
- **Tanggal:** {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
- **Waktu:** {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
- **Peminjam:** {{ $booking->user->name }}

<x-mail::button :url="config('app.url') . '/approver/meja-kerja'">
Lihat Detail & Berikan Keputusan
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
