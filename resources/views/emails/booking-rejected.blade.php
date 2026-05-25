<x-mail::message>
# Peminjaman Ruangan Perlu Revisi (Ditolak) ⚠️

Halo **{{ $booking->user->name }}**,

Mohon maaf, pengajuan peminjaman ruangan Anda saat ini **ditangguhkan / perlu direvisi** dengan rincian sebagai berikut:

**Detail Peminjaman:**
- **No. Booking:** #{{ $booking->id }}
- **Kegiatan:** {{ $booking->event_name }}
- **Ruangan:** {{ $booking->room->room_name }}

**Alasan Penolakan / Catatan Revisi:**
> {{ $notes }}

Status pengajuan Anda saat ini adalah **Revising**. Anda dapat memperbaiki atau mengunggah ulang dokumen persyaratan yang diminta melalui dashboard Space.in Anda untuk diajukan kembali tanpa mengubah ID Booking.

<x-mail::button :url="config('app.url') . '/peminjam/detail/' . $booking->id">
Perbaiki Pengajuan & Kirim Ulang
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>
