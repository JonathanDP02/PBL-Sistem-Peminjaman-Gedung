<x-app-layout>
    <style>
        /* =======================================================
           CSS SURGICAL PRINT (STABIL, PRESISI & BEBAS SCROLLBAR)
           ======================================================= */
        @media print {
            @page { 
                size: A4 portrait; 
                margin: 1cm; 
            }

            /* 1. Reset Global untuk Print & Hilangkan Scrollbar */
            html, body {
                background-color: white !important;
                color: black !important;
                margin: 0 !important;
                padding: 0 !important;
                /* MATIKAN SEMUA OVERFLOW AGAR SCROLLBAR HILANG */
                overflow: visible !important; 
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            /* Matikan Scrollbar Webkit Khusus Chrome/Safari saat Print */
            ::-webkit-scrollbar {
                display: none !important;
            }

            /* 2. Matikan Dark Mode secara Paksa */
            * {
                color: #0f172a !important; 
                background-color: transparent !important; 
                box-shadow: none !important;
                text-shadow: none !important;
            }

            /* 3. Sembunyikan Elemen yang Tidak Dibutuhkan */
            nav, aside, header, footer, .no-print, 
            .fixed, .sticky, [class*="sidebar"], [class*="navbar"] {
                display: none !important;
            }

            /* 4. Buka Gembok Layout Utama */
            #main-content, main, .ml-64, .lg\:ml-64, .pl-64, .min-h-screen {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
                position: static !important;
                /* MATIKAN OVERFLOW DI CONTAINER UTAMA */
                overflow: visible !important; 
                height: auto !important;
                min-height: auto !important;
            }

            /* 5. Styling Laporan (Grid & Tabel) */
            .print-wrapper {
                width: 100% !important;
                padding: 0 !important;
                display: block !important;
                background-color: white !important;
                /* MATIKAN OVERFLOW DI WRAPPER LAPORAN */
                overflow: visible !important; 
            }

            .print-grid-4 { 
                display: grid !important; 
                grid-template-columns: repeat(4, 1fr) !important; 
                gap: 15px !important; 
                margin-bottom: 25px !important;
            }

            .print-card {
                border: 1px solid #94a3b8 !important;
                padding: 15px !important;
                border-radius: 8px !important;
                page-break-inside: avoid !important;
            }

            .print-card h2 { 
                font-size: 24pt !important; 
                font-weight: 800 !important; 
                margin: 5px 0 !important; 
            }

            .print-card p.label { 
                font-size: 8pt !important; 
                font-weight: 700 !important; 
                letter-spacing: 1px !important; 
                text-transform: uppercase !important; 
                color: #64748b !important;
            }

            /* Styling Tabel */
            .print-table-container {
                border: 1px solid #cbd5e1 !important;
                border-radius: 8px !important;
                margin-top: 15px !important;
                /* MATIKAN OVERFLOW DI CONTAINER TABEL */
                overflow: visible !important; 
                max-height: none !important;
            }
            
            table { width: 100% !important; border-collapse: collapse !important; page-break-inside: auto !important;}
            tr { page-break-inside: avoid !important; page-break-after: auto !important; }
            th { 
                background-color: #f8fafc !important; 
                border-bottom: 2px solid #cbd5e1 !important; 
                font-size: 9pt !important;
                padding: 10px !important;
                text-align: left !important;
            }
            td { 
                border-bottom: 1px solid #e2e8f0 !important; 
                font-size: 10pt !important;
                padding: 10px !important;
            }

            /* Tipografi KOP */
            .print-title { font-size: 22pt !important; font-weight: 900 !important; margin: 0 0 5px 0 !important; text-transform: uppercase !important; text-align: center !important;}
            .print-subtitle { font-size: 11pt !important; text-align: center !important; margin-bottom: 25px !important; color: #475569 !important;}

            /* Badge Status */
            .print-badge { 
                padding: 4px 8px !important; 
                border-radius: 4px !important; 
                font-size: 9px !important; 
                font-weight: 800 !important; 
                border: 1px solid !important; 
                display: inline-block !important; 
            }
            .print-badge.approved { border-color: #059669 !important; color: #059669 !important; }
            .print-badge.pending { border-color: #475569 !important; color: #475569 !important; }
            .print-badge.rejected { border-color: #dc2626 !important; color: #dc2626 !important; }

            /* Paksa Area Tanda Tangan */
            .print-signature-area {
                display: block !important;
                margin-top: 50px !important;
                page-break-inside: avoid !important;
            }
        }
    </style>

    <div class="relative px-8 py-8 space-y-8 min-h-full print-wrapper">
        
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-100/40 dark:bg-kinetic-tertiary/10 rounded-full blur-[120px] pointer-events-none no-print"></div>

        <section class="relative rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none overflow-hidden p-8 no-print">
            <div class="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-cyan-50 dark:from-kinetic-secondary/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-50 dark:bg-kinetic-secondary/10 text-cyan-700 dark:text-kinetic-secondary text-[11px] font-bold uppercase tracking-[0.3em] border border-cyan-200 dark:border-kinetic-secondary/20">Laporan</span>
                    <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">Ringkasan Laporan</h1>
                    <p class="mt-3 text-sm text-slate-500 dark:text-gray-400 max-w-2xl">Pantau performa pemakaian ruang, status booking, dan unit paling aktif dalam sistem peminjaman.</p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button onclick="window.print()" type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-kinetic-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-600 dark:hover:bg-kinetic-secondary shadow-lg">
                        <i class="ph-bold ph-printer"></i>
                        Cetak / PDF
                    </button>
                </div>
            </div>
        </section>

        <div class="hidden print:block border-b-2 border-slate-800 pb-4 mb-6">
            <h1 class="print-title">Laporan Peminjaman Ruangan</h1>
            <p class="print-subtitle">Unit: <strong>{{ auth()->user()->unit->unit_name ?? 'Fakultas / Unit Kerja' }}</strong> &nbsp;|&nbsp; Periode: <strong>{{ now()->translatedFormat('F Y') }}</strong></p>
        </div>

        @php
            // Logika Pengambilan Data Dinamis
            $unitId = auth()->user()->unit_id ?? null;
            $query = \App\Models\Booking::query();
            
            if (auth()->user() && auth()->user()->role->name === 'Admin_Unit') {
                $query->whereHas('room', function($q) use ($unitId) {
                    $q->where('unit_id', $unitId);
                });
            }

            $currentMonth = now()->month;
            $currentYear = now()->year;
            
            // Siapkan base query bulanan
            $monthlyBookings = (clone $query)->whereMonth('booking_date', $currentMonth)->whereYear('booking_date', $currentYear);

            // ==========================================
            // FIX: Gunakan (clone) di setiap eksekusi 
            // agar perintah SQL tidak saling bercampur
            // ==========================================
            
            $totalBooking = (clone $monthlyBookings)->count();
            
            // Hitung ruang terpakai dengan aman untuk PostgreSQL
            $ruangTerpakai = (clone $monthlyBookings)->select('room_id')->distinct()->count('room_id');
            
            $disetujui = (clone $monthlyBookings)->where('status', 'Approved')->count();
            
            $menunggu = (clone $monthlyBookings)->where('status', 'Pending')->count();

            // Ambil semua data bulan ini untuk di print (bukan cuma 10)
            $recentBookingsList = (clone $monthlyBookings)->with(['room', 'user'])->orderBy('booking_date', 'asc')->get();
        @endphp

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 print-grid-4">
            <div class="print-card rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <p class="label text-[10px] md:text-xs uppercase tracking-[0.26em] text-slate-400 dark:text-gray-500 font-bold mb-2 md:mb-4">Booking Bulanan</p>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $totalBooking ?: 0 }}</h2>
                <p class="no-print mt-2 md:mt-3 text-[10px] md:text-sm text-slate-500 dark:text-gray-400">Total aktivitas di sistem bulan ini.</p>
            </div>
            <div class="print-card rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <p class="label text-[10px] md:text-xs uppercase tracking-[0.26em] text-slate-400 dark:text-gray-500 font-bold mb-2 md:mb-4">Ruang Terpakai</p>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $ruangTerpakai ?: 0 }}</h2>
                <p class="no-print mt-2 md:mt-3 text-[10px] md:text-sm text-slate-500 dark:text-gray-400">Jumlah ruangan dengan status aktif.</p>
            </div>
            <div class="print-card rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <p class="label text-[10px] md:text-xs uppercase tracking-[0.26em] text-slate-400 dark:text-gray-500 font-bold mb-2 md:mb-4">Disetujui</p>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $disetujui ?: 0 }}</h2>
                <p class="no-print mt-2 md:mt-3 text-[10px] md:text-sm text-slate-500 dark:text-gray-400">Total booking yang telah di-acc.</p>
            </div>
            <div class="print-card rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <p class="label text-[10px] md:text-xs uppercase tracking-[0.26em] text-slate-400 dark:text-gray-500 font-bold mb-2 md:mb-4">Menunggu</p>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ $menunggu ?: 0 }}</h2>
                <p class="no-print mt-2 md:mt-3 text-[10px] md:text-sm text-slate-500 dark:text-gray-400">Peminjaman butuh tindakan Anda.</p>
            </div>
        </div>

        <div class="print-card rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
            <div class="mb-4">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Detail Riwayat Peminjaman</h2>
            </div>

            <div class="rounded-xl border border-slate-200 dark:border-kinetic-border print-table-container">
                <table class="min-w-full text-left text-sm">
                    <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400">
                        <tr>
                            <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[10px]">Ruang</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[10px]">Kegiatan</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[10px] text-center">Status</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[10px]">Tanggal</th>
                            <th class="px-6 py-4 font-semibold uppercase tracking-wider text-[10px]">Pemesan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-kinetic-border/70">
                        @forelse ($recentBookingsList as $booking)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 transition">
                                <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">{{ $booking->room->room_name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $booking->event_name }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        // Mapping Class untuk PDF maupun Web
                                        $badgePrintClass = '';
                                        $badgeWebClass = '';
                                        if($booking->status == 'Approved') {
                                            $badgePrintClass = 'approved';
                                            $badgeWebClass = 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20';
                                        } elseif($booking->status == 'Rejected' || $booking->status == 'Cancelled') {
                                            $badgePrintClass = 'rejected';
                                            $badgeWebClass = 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20';
                                        } else {
                                            $badgePrintClass = 'pending';
                                            $badgeWebClass = 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
                                        }
                                    @endphp
                                    <span class="print-badge {{ $badgePrintClass }} inline-flex border rounded-full px-3 py-1 text-[9px] font-bold uppercase tracking-[0.2em] {{ $badgeWebClass }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $booking->user->name ?? 'N/A' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data peminjaman di bulan ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="hidden print-signature-area print:block">
            <div style="float: right; text-align: center; width: 250px;">
                <p class="print-subtitle mb-16" style="margin-bottom: 80px;">Mengetahui,<br>Admin Pengelola Ruangan</p>
                <p style="font-weight: bold; text-decoration: underline; color: #000;">{{ auth()->user()->name ?? 'Administrator' }}</p>
            </div>
            <div style="clear: both;"></div>
        </div>

    </div>
</x-app-layout>