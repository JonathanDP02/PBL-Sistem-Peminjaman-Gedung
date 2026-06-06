<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Disposisi Wadir II - Polinema</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #1a1a1a; margin: 0; padding: 20px; background-color: #fff; }
        .header { text-align: center; border-bottom: 3px double #1a1a1a; padding-bottom: 10px; margin-bottom: 10px; }
        .header h2 { margin: 4px 0; font-size: 15px; text-transform: uppercase; }
        .header p { margin: 2px 0; font-size: 10px; }
        .dispo-checkbox { display: inline-block; width: 12px; height: 12px; border: 1.5px solid #000; text-align: center; line-height: 12px; font-weight: bold; margin-right: 4px; font-size: 9px; vertical-align: middle; }
        .dispo-table { width: 100%; border: 1.5px solid #000; border-collapse: collapse; }
        .dispo-table td, .dispo-table th { border: 1.5px solid #000; padding: 6px; vertical-align: top; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; padding: 10px; background-color: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 8px; text-align: center;">
        <button onclick="window.print()" style="padding: 8px 16px; background-color: #0d9488; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">Cetak Disposisi</button>
        <span style="margin-left: 10px; font-size: 12px; color: #4b5563;">Setelah mencetak, Anda dapat menutup halaman ini dan tombol persetujuan di sistem akan aktif.</span>
    </div>

    {{-- Header Institusi (Kop Surat) --}}
    <div class="header">
        <h2>Politeknik Negeri Malang</h2>
        <p>Jl. Soekarno Hatta No.9, Malang, Jawa Timur 65141</p>
        <p>Telp. (0341) 404424 | www.polinema.ac.id</p>
    </div>

    <div style="text-align: center; position: relative; margin-bottom: 15px;">
        <h3 style="font-size: 14px; text-transform: uppercase; font-weight: bold; margin: 0;">Disposisi Wakil Direktur II</h3>
        <div style="position: absolute; right: 0; top: -5px; border: 1.5px solid #000; padding: 4px 12px; font-weight: bold; font-size: 12px;">
            {{ $booking->id }}
        </div>
    </div>

    {{-- Detail & Klasifikasi --}}
    <table class="dispo-table">
        <tr>
            <td colspan="2" style="padding: 8px;">
                <span style="font-weight: bold; margin-right: 20px; font-size: 10px;">KLASIFIKASI:</span>
                @php
                    $klasifikasi = $booking->disposisi_data['klasifikasi'] ?? 'Biasa';
                @endphp
                <span style="margin-right: 20px;"><span class="dispo-checkbox">{!! $klasifikasi === 'Sangat Rahasia' ? '✓' : '&nbsp;' !!}</span> Sangat Rahasia</span>
                <span style="margin-right: 20px;"><span class="dispo-checkbox">{!! $klasifikasi === 'Rahasia' ? '✓' : '&nbsp;' !!}</span> Rahasia</span>
                <span style="margin-right: 20px;"><span class="dispo-checkbox">{!! $klasifikasi === 'Sangat Segera' ? '✓' : '&nbsp;' !!}</span> Sangat Segera</span>
                <span style="margin-right: 20px;"><span class="dispo-checkbox">{!! $klasifikasi === 'Segera' ? '✓' : '&nbsp;' !!}</span> Segera</span>
                <span><span class="dispo-checkbox">{!! $klasifikasi === 'Biasa' ? '✓' : '&nbsp;' !!}</span> Biasa</span>
            </td>
        </tr>
        <tr>
            <td style="width: 60%; line-height: 1.5;">
                <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                    <tr><td style="width: 120px; padding: 2px 0;">Nomor Dispo</td><td style="width: 10px;">:</td><td><strong>{{ $booking->id }}</strong></td></tr>
                    <tr><td style="padding: 2px 0;">Nomor Surat</td><td>:</td><td><strong>{{ $booking->id }}</strong></td></tr>
                    <tr><td style="padding: 2px 0;">Asal Surat</td><td>:</td><td><strong>{{ $booking->user->unit->unit_name ?? '-' }}</strong></td></tr>
                    <tr><td style="padding: 2px 0;">Perihal</td><td>:</td><td><strong>{{ $booking->event_name }}</strong></td></tr>
                </table>
            </td>
            <td style="width: 40%; line-height: 1.5;">
                <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                    <tr><td style="width: 100px; padding: 2px 0;">Tanggal Terima</td><td style="width: 10px;">:</td><td><strong>{{ $booking->created_at->format('d/m/Y') }}</strong></td></tr>
                    <tr><td style="padding: 2px 0;">Tanggal Surat</td><td>:</td><td><strong>{{ $booking->booking_date->format('d/m/Y') }}</strong></td></tr>
                </table>
            </td>
        </tr>
    </table>

    <div style="margin-top: 15px; font-weight: bold; margin-bottom: 5px; font-size: 10px;">Diteruskan Kepada:</div>
    <table class="dispo-table" style="font-size: 9px;">
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
                    <td style="width: 33.33%; padding: 3px 6px;">
                        @if ($col)
                            <span class="dispo-checkbox">{!! in_array($col, $tujuanList) ? '✓' : '&nbsp;' !!}</span> {{ $col }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>

    <table class="dispo-table" style="margin-top: 15px;">
        <tr>
            <th style="width: 100%; padding: 6px; background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 10px;">ISI DISPOSISI</th>
        </tr>
        <tr>
            <td style="padding: 8px;">
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
                <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                    @for ($i = 0; $i < 5; $i++)
                        <tr>
                            <td style="width: 50%; padding: 3px 0;">
                                <span class="dispo-checkbox">{!! in_array($leftIsi[$i], $isiList) ? '✓' : '&nbsp;' !!}</span> {{ $leftIsi[$i] }}
                            </td>
                            <td style="width: 50%; padding: 3px 0;">
                                <span class="dispo-checkbox">{!! in_array($rightIsi[$i], $isiList) ? '✓' : '&nbsp;' !!}</span> {{ $rightIsi[$i] }}
                            </td>
                        </tr>
                    @endfor
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding: 6px; background-color: #f3f4f6; text-align: center; font-weight: bold; font-size: 10px;">
                JAWABAN DISPOSISI
            </td>
        </tr>
        <tr>
            <td style="padding: 12px; height: 80px; vertical-align: top; font-size: 10px; line-height: 1.4;">
                {!! nl2br(e($booking->disposisi_data['catatan'] ?? '')) !!}
            </td>
        </tr>
    </table>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 300);
        };
    </script>
</body>
</html>
