<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman Ruangan Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo {{ $booking->user->name }},</h2>
    <p>Selamat! Pengajuan peminjaman ruangan Anda telah disetujui secara keseluruhan (Hard-Lock).</p>
    <ul>
        <li><strong>No. Booking:</strong> #{{ $booking->id }}</li>
        <li><strong>Kegiatan:</strong> {{ $booking->event_name }}</li>
        <li><strong>Tanggal:</strong> {{ $booking->booking_date->format('d M Y') }}</li>
        <li><strong>Ruangan:</strong> {{ $booking->room->room_name ?? 'Ruangan' }}</li>
    </ul>
    <p>Sertifikat/Surat Izin peminjaman akan/telah digenerate dan Anda dapat mengunduhnya melalui dashboard Space.in.</p>
    <br>
    <p>Terima kasih,</p>
    <p><strong>Space.in System</strong></p>
</body>
</html>
