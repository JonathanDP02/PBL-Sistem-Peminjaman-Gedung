<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 0; }
        .header { text-align: center; border-bottom: 3px double #1a1a1a; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 4px 0; font-size: 15px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        .title { text-align: center; margin: 20px 0; }
        .title h3 { font-size: 14px; text-transform: uppercase; text-decoration: underline; letter-spacing: 1px; margin-bottom: 4px; }
        .title p { font-size: 10px; color: #555; }
        .section { margin-bottom: 16px; }
        .section-title { font-weight: bold; border-bottom: 1px solid #ccc; margin-bottom: 6px; padding-bottom: 2px; text-transform: uppercase; font-size: 10px; }
        table.detail { width: 100%; border-collapse: collapse; }
        table.detail td { padding: 3px 6px; vertical-align: top; }
        table.detail td:first-child { width: 30%; color: #555; }
        table.approval { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.approval th, table.approval td { border: 1px solid #aaa; padding: 6px 8px; text-align: left; font-size: 10px; }
        table.approval th { background-color: #f0f0f0; text-transform: uppercase; }
        
        .footer-table { width: 100%; margin-top: 40px; border-collapse: collapse; }
        .footer-table td { vertical-align: bottom; }
        .sign-section { text-align: center; }
        .qr-section { text-align: right; }
        
        .sign-title { margin-bottom: 5px; font-weight: bold; }
        .sign-image { height: 80px; margin: 5px 0; }
        .sign-name { font-weight: bold; text-decoration: underline; font-size: 12px; margin-top: 5px; }
        .sign-nip { font-size: 11px; margin-top: 2px; }
        
        .digital-stamp {
            border: 2px dashed #065f46;
            background-color: #f0fdf4;
            color: #065f46;
            padding: 8px;
            margin: 10px auto;
            width: 200px;
            text-align: center;
            border-radius: 4px;
        }
        .stamp-title {
            font-size: 8px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }
        .stamp-desc {
            font-size: 8px;
            margin-bottom: 2px;
            color: #065f46;
        }
        .stamp-date {
            font-size: 7px;
            color: #047857;
            font-weight: bold;
        }
        
        /* .qr-box { display: inline-block; text-align: center; } */
        .qr-box p { font-size: 8px; color: #777; margin-top: 5px; }
        
        .number { text-align: center; font-size: 10px; margin-bottom: 16px; color: #555; }
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
        Status: <span style="color: #065f46; font-weight: bold;">{{ strtoupper($booking->status) }}</span>
    </div>

    {{-- Detail Event --}}
    <div class="section">
        <div class="section-title">Detail Kegiatan</div>
        <table class="detail">
            <tr><td>Nama Kegiatan</td><td>: {{ $booking->event_name }}</td></tr>
            <tr><td>Deskripsi</td><td>: {{ $booking->event_description ?? '-' }}</td></tr>
            <tr><td>Tanggal</td><td>: {{ $booking->booking_date->format('d F Y') }}</td></tr>
            <tr><td>Waktu</td><td>: {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB</td></tr>
            <tr><td>Ruangan</td><td>: {{ $booking->room->room_name ?? '-' }}</td></tr>
            <tr><td>Peminjam</td><td>: {{ $booking->user->name ?? '-' }}</td></tr>
        </table>
    </div>

    {{-- Tabel Persetujuan --}}
    <div class="section">
        <div class="section-title">Riwayat Persetujuan</div>
        <table class="approval">
            <thead>
                <tr>
                    <th style="width: 10%;">Langkah</th>
                    <th style="width: 30%;">Jabatan</th>
                    <th style="width: 40%;">Disetujui Oleh</th>
                    <th style="width: 20%;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($booking->approvals->where('approval_status', 'Approved')->sortBy('step.step_order') as $approval)
                <tr>
                    <td style="text-align: center;">{{ $approval->step->step_order ?? '-' }}</td>
                    <td>{{ $approval->step->position->name ?? '-' }}</td>
                    <td>{{ $approval->approver->name ?? '-' }}</td>
                    <td style="color: #065f46; font-weight: bold; text-align: center;">APPROVED</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center;color:#999;">Tidak ada data persetujuan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer dengan TTD QR Resmi --}}
    <table class="footer-table">
        <tr>
            {{-- Kolom Kiri Dikosongkan agar TTD tetap di Kanan --}}
            <td style=""></td>
            {{-- Bagian Kanan: Tanda Tangan Digital Berbasis QR Code --}}
            <td class="sign-section">
                <div class="sign-title">Menyetujui,</div>
                <div style="font-size: 10px;">{{ $lastApproval->step->position->name ?? 'Pejabat Berwenang' }}</div>
                <div style="font-size: 9px; color: #555; margin-bottom: 6px;">{{ $lastApproval->step->position->unit->unit_name ?? 'Politeknik Negeri Malang' }}</div>
                
                {{-- QR Code Resmi sebagai TTD Elektronik (Berada di Tengah Signature Block) --}}
                <div style="margin: 8px 0;">
                    <div style="display: inline-block; padding: 5px; border: 1px solid #ddd; background-color: #ffffff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                        <img src="data:image/png;base64, {!! $qrCode !!}" style="width: 80px; height: 80px; display: block; margin: 0 auto;">
                    </div>
                    <div style="font-size: 7px; color: #065f46; font-weight: bold; margin-top: 4px; letter-spacing: 0.5px;">✓ DISETUJUI SECARA ELEKTRONIK</div>
                </div>
                
                <div class="sign-name">{{ $lastApproval->approver->name ?? 'Dr. Hj. Susi Evanita, M. S.' }}</div>
                <div class="sign-nip">NIP : {{ $lastApproval->approver->profile_data['nip'] ?? '19630608 198703 2 002' }}</div>
            </td>
            <td style=""></td>
        </tr>
    </table>

    {{-- Catatan Penting di Pojok Kiri Paling Bawah Halaman --}}
    <div style="position:bottom: 0; left: 0;margin-top: 40px; font-size: 11px; color: #555; line-height: 1.3; border-left: 2px solid #aaa; padding-left: 6px; width: 280px;">
        <strong>PENTING:</strong><br>
        1. Surat Izin ini resmi diterbitkan secara elektronik oleh sistem <strong>Space.in</strong>.<br>
        2. Dokumen ini sah dan tidak memerlukan tanda tangan basah serta stempel fisik.<br>
        3. Keaslian dokumen dapat diverifikasi dengan memindai QR Code di atas.
    </div>

</body>
</html>