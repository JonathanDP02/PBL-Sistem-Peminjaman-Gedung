<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman Ruangan Ditolak</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo {{ $booking->user->name }},</h2>
    <p>Mohon maaf, pengajuan peminjaman ruangan Anda telah <strong style="color: red;">ditolak</strong> dengan rincian sebagai berikut:</p>
    <ul>
        <li><strong>No. Booking:</strong> #{{ $booking->id }}</li>
        <li><strong>Kegiatan:</strong> {{ $booking->event_name }}</li>
        <li><strong>Ruangan:</strong> {{ $booking->room->room_name ?? 'Ruangan' }}</li>
    </ul>
    <h3>Alasan Penolakan:</h3>
    <blockquote style="border-left: 4px solid #f87171; padding-left: 10px; color: #555;">
        <em>"{{ $notes }}"</em>
    </blockquote>
    <p>Status booking Anda saat ini adalah <strong>Revising</strong>. Silakan login ke sistem untuk mengunggah ulang dokumen atau memperbaiki pengajuan Anda.</p>
    <br>
    <p>Terima kasih,</p>
    <p><strong>Space.in System</strong></p>
</body>
</html>
