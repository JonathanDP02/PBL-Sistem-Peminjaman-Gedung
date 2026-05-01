<!DOCTYPE html>
<html>
<head>
    <title>Persetujuan Diperlukan</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo {{ $approver->name }},</h2>
    <p>Ada pengajuan peminjaman ruangan baru yang membutuhkan persetujuan Anda.</p>
    <ul>
        <li><strong>No. Booking:</strong> #{{ $booking->id }}</li>
        <li><strong>Kegiatan:</strong> {{ $booking->event_name }}</li>
        <li><strong>Peminjam:</strong> {{ $booking->user->name }}</li>
        <li><strong>Tanggal:</strong> {{ $booking->booking_date->format('d M Y') }}</li>
        <li><strong>Ruangan:</strong> {{ $booking->room->room_name ?? 'Ruangan' }}</li>
    </ul>
    <p>Silakan login ke sistem Space.in untuk meninjau dan memberikan persetujuan.</p>
    <br>
    <p>Terima kasih,</p>
    <p><strong>Space.in System</strong></p>
</body>
</html>
