<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #1a1a1a; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 3px double #1a1a1a; padding-bottom: 10px; margin-bottom: 20px; }
        .header img { height: 70px; }
        .header h2 { margin: 4px 0; font-size: 15px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 11px; }
        .title { text-align: center; margin: 20px 0; }
        .title h3 { font-size: 14px; text-transform: uppercase; text-decoration: underline; letter-spacing: 1px; }
        .title p { font-size: 11px; color: #555; }
        .section { margin-bottom: 16px; }
        .section-title { font-weight: bold; border-bottom: 1px solid #ccc; margin-bottom: 6px; padding-bottom: 2px; }
        table.detail { width: 100%; border-collapse: collapse; }
        table.detail td { padding: 4px 6px; vertical-align: top; }
        table.detail td:first-child { width: 35%; color: #555; }
        table.approval { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.approval th, table.approval td { border: 1px solid #aaa; padding: 6px 8px; text-align: left; font-size: 11px; }
        table.approval th { background-color: #f0f0f0; }
        .footer { margin-top: 30px; display: flex; justify-content: space-between; }
        .sign-box { text-align: center; width: 40%; }
        .sign-box .sign-line { border-top: 1px solid #1a1a1a; margin-top: 60px; padding-top: 4px; font-size: 11px; }
        .qr-section { text-align: center; margin-top: 20px; }
        .qr-section p { font-size: 10px; color: #777; margin-top: 4px; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .badge-approved { background: #d1fae5; color: #065f46; }
        .number { text-align: center; font-size: 11px; margin-bottom: 16px; color: #555; }
    </style>
</head>
<body>

    {{-- Header Institusi --}}
    <div class="header">
        <h2>Politeknik Negeri Malang</h2>
        <p>Jl. Soekarno Hatta No.9, Malang, Jawa Timur 65141</p>
        <p>Telp. (0341) 404424 | www.polinema.ac.id</p>
    </div>

    {{-- Judul Surat --}}
    <div class="title">
        <h3>Surat Izin Peminjaman Ruangan</h3>
        <p>Space.in — Sistem Reservasi Ruangan Polinema</p>
    </div>

    <div class="number">
        No. Booking: <strong>#{{ $booking->id }}</strong> &nbsp;|&nbsp;
        Status: <span style="background: #d1fae5; color: #065f46; padding: 2px 8px; border-radius: 4px; font-weight: bold;">{{ $booking->status }}</span>
    </div>

    {{-- Detail Event --}}
    <div class="section">
        <div class="section-title">Detail Kegiatan</div>
        <table class="detail">
            <tr><td>Nama Kegiatan</td><td>: {{ $booking->event_name }}</td></tr>
            <tr><td>Deskripsi</td><td>: {{ $booking->event_description ?? '-' }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ $booking->booking_date->format('d F Y') }}</td></tr>
            <tr><td>Waktu</td><td>: {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB</td></tr>
            <tr><td>Ruangan</td><td>: {{ $booking->room->name ?? '-' }}</td></tr>
            <tr><td>Peminjam</td><td>: {{ $booking->user->name ?? '-' }}</td></tr>
        </table>
    </div>

    {{-- Tabel Persetujuan --}}
    <div class="section">
        <div class="section-title">Riwayat Persetujuan</div>
        <table class="approval">
            <thead>
                <tr>
                    <th>Langkah</th>
                    <th>Jabatan</th>
                    <th>Disetujui Oleh</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($booking->approvals->where('approval_status', 'Approved') as $approval)
                <tr>
                    <td>{{ $approval->step->step_order ?? '-' }}</td>
                    <td>{{ $approval->step->position->name ?? '-' }}</td>
                    <td>{{ $approval->approver->name ?? '-' }}</td>
                    <td style="color: #065f46; font-weight: bold;">Approved</td>
                    <td>{{ $approval->notes ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#999;">Tidak ada data persetujuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="qr-section">
        {!! $qrCode !!}
        <p>Scan untuk memverifikasi keaslian surat ini</p>
        <p>{{ url('/validate/' . $booking->id) }}</p>
    </div>

</body>
</html>