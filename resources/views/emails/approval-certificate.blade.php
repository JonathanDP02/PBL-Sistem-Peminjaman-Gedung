<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #f5f5f5; padding: 20px; border-radius: 5px; margin-bottom: 20px; }
        .header h2 { margin: 0 0 10px 0; color: #2c3e50; }
        .content { background-color: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .section { margin-bottom: 20px; }
        .section-title { font-weight: bold; color: #2c3e50; margin-bottom: 10px; border-bottom: 1px solid #eee; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table td { padding: 8px; border-bottom: 1px solid #eee; }
        table td:first-child { font-weight: bold; width: 150px; color: #555; }
        .footer { background-color: #f5f5f5; padding: 20px; border-radius: 5px; margin-top: 20px; text-align: center; font-size: 12px; color: #777; }
        .button { display: inline-block; background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 10px; }
        .status-badge { display: inline-block; background-color: #d1fae5; color: #065f46; padding: 5px 10px; border-radius: 3px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Surat Izin Peminjaman Ruangan Disetujui ✅</h2>
            <p>Halo <strong>{{ $booking->user->name }}</strong>,</p>
        </div>

        <div class="content">
            <div class="section">
                <p>Pengajuan peminjaman ruangan Anda telah <span class="status-badge">{{ $booking->status }}</span> oleh semua pejabat yang berwenang.</p>
                <p>Silakan lihat detail di bawah dan download PDF untuk keperluan administrasi:</p>
            </div>

            <div class="section">
                <div class="section-title">Detail Peminjaman</div>
                <table>
                    <tr>
                        <td>No. Booking</td>
                        <td>#{{ $booking->id }}</td>
                    </tr>
                    <tr>
                        <td>Nama Kegiatan</td>
                        <td>{{ $booking->event_name }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>{{ $booking->booking_date->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Waktu</td>
                        <td>{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB</td>
                    </tr>
                    <tr>
                        <td>Ruangan</td>
                        <td>{{ $booking->room->name }} ({{ $booking->room->building->name }})</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <p><strong>File PDF Surat Izin telah dilampirkan dalam email ini.</strong> Anda juga bisa mengaksesnya melalui dashboard Space.in.</p>
            </div>

            <div class="section">
                <div class="section-title">Verifikasi Keaslian</div>
                <p>Untuk memverifikasi keaslian dokumen ini, silakan scan QR Code di dalam PDF atau kunjungi:</p>
                <p><code>{{ url(route('booking.validate', $booking->id)) }}</code></p>
            </div>
        </div>

        <div class="footer">
            <p>Email ini dikirim otomatis oleh Space.in (Sistem Reservasi Ruangan Polinema).</p>
            <p>Jangan reply email ini. Hubungi admin jika ada pertanyaan.</p>
        </div>
    </div>
</body>
</html>
