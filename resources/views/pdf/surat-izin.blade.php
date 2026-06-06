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
        .dispo-checkbox { display: inline-block; width: 10px; height: 10px; border: 1px solid #000; text-align: center; line-height: 10px; font-weight: bold; margin-right: 4px; font-size: 8px; vertical-align: middle; }
        .dispo-table { width: 100%; border: 1px solid #000; border-collapse: collapse; }
        .dispo-table td, .dispo-table th { border: 1px solid #000; padding: 4px; vertical-align: top; }
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
            <tr><td>Tanggal</td><td>: {{ $booking->booking_date->format('Y-m-d') === $booking->booking_end_date->format('Y-m-d') ? $booking->booking_date->translatedFormat('d F Y') : $booking->booking_date->translatedFormat('d F Y') . ' – ' . $booking->booking_end_date->translatedFormat('d F Y') }}</td></tr>
            <tr><td>Waktu</td><td>: {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB</td></tr>
            <tr><td>Ruangan</td><td>: {{ $booking->room->room_name ?? '-' }}</td></tr>
            <tr><td>Fasilitas Ruang</td><td>: {{ $booking->room->facilities ?? '-' }}</td></tr>
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
                @forelse ($booking->approvals->where('approval_status', 'Approved')->sortBy(fn ($a) => $a->bookingStep->step_order ?? $a->step->step_order ?? 0) as $approval)
                <tr>
                    <td style="text-align: center;">{{ $approval->bookingStep->step_order ?? $approval->step->step_order ?? '-' }}</td>
                    <td>{{ $approval->bookingStep->position->name ?? $approval->step->position->name ?? '-' }}</td>
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
                <div style="font-size: 10px;">{{ $lastApproval->bookingStep->position->name ?? $lastApproval->step->position->name ?? 'Pejabat Berwenang' }}</div>
                <div style="font-size: 9px; color: #555; margin-bottom: 6px;">{{ $lastApproval->bookingStep->position->unit->unit_name ?? $lastApproval->step->position->unit->unit_name ?? 'Politeknik Negeri Malang' }}</div>
                
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

    </div>

    @if ($booking->disposisi_data)
        <div style="page-break-before: always;"></div>
        
        {{-- Header Institusi (Kop Surat) --}}
        <div class="header">
            <h2>Politeknik Negeri Malang</h2>
            <p>Jl. Soekarno Hatta No.9, Malang, Jawa Timur 65141</p>
            <p>Telp. (0341) 404424 | www.polinema.ac.id</p>
        </div>

        <div style="text-align: center; position: relative; margin-bottom: 10px;">
            <h3 style="font-size: 13px; text-transform: uppercase; font-weight: bold; margin: 0;">Disposisi Wakil Direktur II</h3>
            <div style="position: absolute; right: 0; top: -5px; border: 1px solid #000; padding: 3px 10px; font-weight: bold; font-size: 11px;">
                {{ $booking->id }}
            </div>
        </div>

        {{-- Detail & Klasifikasi --}}
        <table class="dispo-table" style="width: 100%; border: 1px solid #000; border-collapse: collapse; font-size: 9px;">
            <tr>
                <td colspan="2" style="border: 1px solid #000; padding: 6px; text-align: justify;">
                    <span style="font-weight: bold; margin-right: 15px;">KLASIFIKASI:</span>
                    @php
                        $klasifikasi = $booking->disposisi_data['klasifikasi'] ?? 'Biasa';
                    @endphp
                    <span style="margin-right: 15px;"><span class="dispo-checkbox">{!! $klasifikasi === 'Sangat Rahasia' ? '✓' : '&nbsp;' !!}</span> Sangat Rahasia</span>
                    <span style="margin-right: 15px;"><span class="dispo-checkbox">{!! $klasifikasi === 'Rahasia' ? '✓' : '&nbsp;' !!}</span> Rahasia</span>
                    <span style="margin-right: 15px;"><span class="dispo-checkbox">{!! $klasifikasi === 'Sangat Segera' ? '✓' : '&nbsp;' !!}</span> Sangat Segera</span>
                    <span style="margin-right: 15px;"><span class="dispo-checkbox">{!! $klasifikasi === 'Segera' ? '✓' : '&nbsp;' !!}</span> Segera</span>
                    <span><span class="dispo-checkbox">{!! $klasifikasi === 'Biasa' ? '✓' : '&nbsp;' !!}</span> Biasa</span>
                </td>
            </tr>
            <tr>
                <td style="width: 60%; border: 1px solid #000; padding: 6px; line-height: 1.4; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                        <tr><td style="width: 120px; padding: 1px 0;">Nomor Dispo</td><td style="width: 10px;">:</td><td><strong>{{ $booking->id }}</strong></td></tr>
                        <tr><td style="padding: 1px 0;">Nomor Surat</td><td>:</td><td><strong>{{ $booking->id }}</strong></td></tr>
                        <tr><td style="padding: 1px 0;">Asal Surat</td><td>:</td><td><strong>{{ $booking->user->unit->unit_name ?? '-' }}</strong></td></tr>
                        <tr><td style="padding: 1px 0;">Perihal</td><td>:</td><td><strong>{{ $booking->event_name }}</strong></td></tr>
                    </table>
                </td>
                <td style="width: 40%; border: 1px solid #000; padding: 6px; line-height: 1.4; vertical-align: top;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                        <tr><td style="width: 100px; padding: 1px 0;">Tanggal Terima</td><td style="width: 10px;">:</td><td><strong>{{ $booking->created_at->format('d/m/Y') }}</strong></td></tr>
                        <tr><td style="padding: 1px 0;">Tanggal Surat</td><td>:</td><td><strong>{{ $booking->booking_date->format('d/m/Y') }}</strong></td></tr>
                    </table>
                </td>
            </tr>
        </table>

        <div style="margin-top: 10px; font-weight: bold; margin-bottom: 2px; font-size: 9px;">Diteruskan Kepada:</div>
        <table class="dispo-table" style="width: 100%; border: 1px solid #000; border-collapse: collapse; font-size: 8px;">
            @php
                $tujuanList = $booking->disposisi_data['tujuan'] ?? [];
                $allTujuan = [
                    ['Wakil Direktur I', 'Kasubag Akademik', 'Pusat P2MPP'],
                    ['Wakil Direktur III', 'Pokja Adm. Akademik & Registrasi', 'Pusat P3M'],
                    ['Wakil Direktur IV', 'Pokja Eval. Akademik & Pengelolaan Data', 'UPA Perpustakaan'],
                    ['Kajur Teknik Elektro', 'Pokja Pembinaan Keg. Mhs & Alumni', 'UPA TIK'],
                    ['Kajur Teknik Mesin', 'Kepala BPKU', 'UPA Bahasa'],
                    ['Kajur Teknik Sipil', 'Kasubag Umum', 'UPA PP'],
                    ['Kajur Teknik Kimia', 'Pokja Tata Usaha', 'UPA PKK'],
                    ['Kajur Akuntansi', 'Pokja Protokoler', 'UPA LUK'],
                    ['Kajur Administrasi Niaga', 'Pokja Rumah Tangga', 'UPA Percetakan & Penerbitan'],
                    ['Kajur Teknologi Informasi', 'Pokja Perencanaan', 'Pokja Unit Pengelola Usaha'],
                    ['Koordinator Kampus Kediri', 'Pokja Monev', 'Tim Kerja Pimpinan'],
                    ['Koordinator Kampus Lumajang', 'Pokja Keuangan', 'PPK'],
                    ['Koordinator Kampus Pamekasan', 'Pokja Pengelola BMN', 'Pokja UPPBJ'],
                    ['KPS', 'Pokja Kepegawaian', 'Tim Teknis PBJ'],
                    ['Sekretaris Dewas', 'Pokja Ortala', 'Admin PPK'],
                    ['Ketua Senat', 'Pokja Kerja Sama', 'Adm. Wadir II'],
                    ['Ketua SPI', 'Pokja Humas', ''],
                    ['Kepala BAK', 'Pokja Hukum', '']
                ];
            @endphp
            @foreach ($allTujuan as $row)
                <tr>
                    @foreach ($row as $col)
                        <td style="width: 33.33%; border: 1px solid #000; padding: 2px 4px;">
                            @if ($col)
                                <span class="dispo-checkbox">{!! in_array($col, $tujuanList) ? '✓' : '&nbsp;' !!}</span> {{ $col }}
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>

        <table class="dispo-table" style="width: 100%; border: 1px solid #000; border-collapse: collapse; margin-top: 10px; font-size: 9px;">
            <tr>
                <th style="width: 100%; border: 1px solid #000; padding: 4px; background-color: #f0f0f0; text-align: center; font-weight: bold;">ISI DISPOSISI</th>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 6px; vertical-align: top;">
                    @php
                        $isiList = $booking->disposisi_data['isi'] ?? [];
                        $leftIsi = [
                            'Mohon diproses sesuai aturan yang berlaku',
                            'Mohon ditindaklanjuti',
                            'Mohon masukan',
                            'Mohon diinfokan',
                            'Mohon bisa dibantu'
                        ];
                        $rightIsi = [
                            'Mohon diterima dengan baik dan dibalas',
                            'Mohon diagendakan',
                            'Untuk diketahui',
                            'Sebagai refrensi',
                            'Arsip'
                        ];
                    @endphp
                    <table style="width: 100%; border-collapse: collapse; font-size: 8px;">
                        @for ($i = 0; $i < 5; $i++)
                            <tr>
                                <td style="width: 50%; padding: 2px 0;">
                                    <span class="dispo-checkbox">{!! in_array($leftIsi[$i], $isiList) ? '✓' : '&nbsp;' !!}</span> {{ $leftIsi[$i] }}
                                </td>
                                <td style="width: 50%; padding: 2px 0;">
                                    <span class="dispo-checkbox">{!! in_array($rightIsi[$i], $isiList) ? '✓' : '&nbsp;' !!}</span> {{ $rightIsi[$i] }}
                                </td>
                            </tr>
                        @endfor
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 4px; background-color: #f0f0f0; text-align: center; font-weight: bold;">
                    JAWABAN DISPOSISI
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 8px; height: 50px; vertical-align: top; font-size: 9px;">
                    {!! nl2br(e($booking->disposisi_data['catatan'] ?? '')) !!}
                </td>
            </tr>
        </table>
    @endif

</body>
</html>