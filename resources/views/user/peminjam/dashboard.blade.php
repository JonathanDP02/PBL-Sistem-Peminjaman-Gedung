<x-app-layout title="Dashboard">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <style>
        /* Kustomisasi Tema FullCalendar Mini untuk Dashboard */
        .fc {
            --fc-border-color: #f1f5f9;
            --fc-button-text-color: #64748b;
            --fc-button-bg-color: transparent;
            --fc-button-border-color: transparent;
            --fc-button-hover-bg-color: #f8fafc;
            --fc-button-active-bg-color: transparent;
            --fc-today-bg-color: rgba(20, 184, 166, 0.05);
            --fc-list-event-hover-bg-color: transparent;
            font-family: inherit;
            font-size: 0.7rem; /* Mengecilkan seluruh kalender */
        }

        .dark .fc {
            --fc-border-color: #2A2A2A;
            --fc-button-text-color: #94a3b8;
            --fc-button-hover-bg-color: #222;
            --fc-page-bg-color: transparent;
        }

        /* Styling spesifik widget kalender */
        .fc .fc-toolbar-title {
            font-size: 0.85rem;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .dark .fc .fc-toolbar-title { color: #ffffff; }
        
        .fc .fc-button {
            padding: 2px !important;
        }
        
        .fc .fc-button:focus { box-shadow: none !important; }
        .fc .fc-col-header-cell-cushion { font-size: 0.65rem; text-transform: uppercase; padding: 2px; }
        .fc .fc-daygrid-day-number { font-size: 0.7rem; font-weight: 600; padding: 4px !important; }
        
        /* Hilangkan bullet point event untuk menghemat ruang di dashboard */
        .fc-daygrid-event-dot { display: none !important; }
        
        .fc-event {
            cursor: pointer;
            border: none !important;
            border-radius: 3px;
            padding: 0px 2px;
            font-size: 0.6rem;
            line-height: 1.2;
            margin-bottom: 1px !important;
        }
    </style>

    @php
        // Mengambil semua jadwal user yang aktif untuk dipetakan ke Kalender Dashboard
        // (Agar tidak perlu mengubah route di web.php)
        $calendarBookings = \App\Models\Booking::with('room')
            ->where('user_id', auth()->id())
            ->whereIn('status', ['Approved', 'Pending', 'Revising'])
            ->get();
    @endphp

    <div class="relative px-8 pt-4 pb-8 space-y-8 z-10 flex flex-col min-h-full">
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-100/50 dark:bg-kinetic-primary/5 rounded-full blur-[100px] pointer-events-none transition-colors duration-300"></div>
            
            <div class="relative rounded-2xl bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border p-8 overflow-hidden glow-primary flex flex-col md:flex-row justify-between items-start md:items-center gap-6 transition-colors duration-300">
                <div class="absolute right-0 top-0 bottom-0 w-1/2 bg-gradient-to-l from-teal-50 dark:from-kinetic-primary/10 to-transparent pointer-events-none transition-colors duration-300"></div>
                
                <div class="relative z-10">
                    <span class="px-2 py-1 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-secondary text-[10px] font-bold rounded uppercase tracking-wider border border-teal-200 dark:border-kinetic-primary/20 mb-3 inline-block">Selamat Datang Kembali</span>
                    <h2 class="font-heading text-3xl font-extrabold mb-1 text-slate-900 dark:text-white">Ruang Kerja <span class="text-gradient">Akademis</span></h2>
                    <p class="text-sm text-slate-500 dark:text-gray-400 max-w-md">Pantau status pengajuan ruanganmu dan pastikan tidak ada dokumen yang tertinggal.</p>
                </div>
                
                <a href="{{ route('booking') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-kinetic-primary hover:bg-teal-600 dark:hover:bg-kinetic-secondary text-white dark:text-slate-900 rounded-xl text-sm font-bold shadow-[0_0_15px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-0.5">
                    <i class="ph-bold ph-plus text-lg"></i> Booking Baru
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-6 relative overflow-hidden group hover:border-teal-400 dark:hover:border-kinetic-primary/50 transition">
                    <div class="absolute right-6 top-6 text-teal-100 dark:text-kinetic-primary/20 group-hover:text-teal-200 dark:group-hover:text-kinetic-primary/40 transition">
                        <i class="ph-fill ph-check-circle text-5xl"></i>
                    </div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-4">Disetujui</p>
                    <p class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">{{ sprintf('%02d', $stats['approved'] ?? 0) }}</p>
                    <p class="text-xs text-teal-600 dark:text-kinetic-primary font-medium">Jadwal Hard-Lock</p>
                </div>

                <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-6 relative overflow-hidden group hover:border-cyan-400 dark:hover:border-kinetic-tertiary/50 transition">
                    <div class="absolute right-6 top-6 text-cyan-100 dark:text-kinetic-tertiary/20 group-hover:text-cyan-200 dark:group-hover:text-kinetic-tertiary/40 transition">
                        <i class="ph-fill ph-clock-countdown text-5xl"></i>
                    </div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-4">Menunggu</p>
                    <p class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">{{ sprintf('%02d', $stats['pending'] ?? 0) }}</p>
                    <p class="text-xs text-cyan-600 dark:text-kinetic-tertiary font-medium">Verifikasi Birokrasi</p>
                </div>

                <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-red-200 dark:border-red-500/20 rounded-2xl p-6 relative overflow-hidden group hover:border-red-400 dark:hover:border-red-500/50 transition">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-50 dark:from-red-500/5 to-transparent transition-colors duration-300"></div>
                    <div class="absolute right-6 top-6 text-red-100 dark:text-red-500/20 group-hover:text-red-200 dark:group-hover:text-red-500/40 transition">
                        <i class="ph-fill ph-warning-circle text-5xl"></i>
                    </div>
                    <p class="text-[10px] font-bold tracking-widest text-red-500 dark:text-red-400 uppercase mb-4 relative z-10">Urgent</p>
                    <p class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1 relative z-10">{{ sprintf('%02d', $stats['rejected'] ?? 0) }}</p>
                    <p class="text-xs text-red-600 dark:text-red-400 font-medium relative z-10">Perlu Revisi Dokumen</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-end">
                        <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white">Booking Terkini</h3>
                        <a href="{{ route('riwayat') }}" class="text-xs font-medium text-teal-600 dark:text-kinetic-primary hover:text-teal-700 dark:hover:text-kinetic-secondary transition">Lihat Semua</a>
                    </div>
                    
                    <div class="space-y-4">
                        @forelse($recentBookings as $booking)
                            <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-5 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-kinetic-surface transition group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border flex items-center justify-center transition-colors group-hover:bg-white dark:group-hover:bg-kinetic-card">
                                        @if(str_contains(strtolower($booking->room->room_name ?? ''), 'lab'))
                                            <i class="ph-fill ph-buildings text-xl text-teal-600 dark:text-kinetic-primary"></i>
                                        @else
                                            <i class="ph-fill ph-users-three text-xl text-cyan-600 dark:text-kinetic-tertiary"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">{{ $booking->room->room_name ?? 'Ruangan N/A' }}</h4>
                                        <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">{{ $booking->event_name }} • {{ date('H:i', strtotime($booking->start_time)) }} - {{ date('H:i', strtotime($booking->end_time)) }} WIB</p>
                                    </div>
                                </div>
                                <div class="text-right flex items-center gap-6">
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Tanggal</p>
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }}</p>
                                    </div>
                                    
                                    @php
                                        $statusClasses = [
                                            'Approved' => 'bg-teal-50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary border-teal-200 dark:border-kinetic-primary/20',
                                            'Pending'  => 'bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-400 border-slate-200 dark:border-gray-700',
                                            'Rejected' => 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20',
                                            'Revising' => 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20'
                                        ];
                                    @endphp
                                    <span class="px-3 py-1 border rounded-md text-[10px] font-bold tracking-wider uppercase {{ $statusClasses[$booking->status] ?? $statusClasses['Pending'] }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 bg-white dark:bg-kinetic-card rounded-2xl border border-dashed border-slate-200 dark:border-kinetic-border transition-colors duration-300">
                                <i class="ph ph-folder-open text-4xl text-slate-300 dark:text-gray-600 mb-3 block"></i>
                                <p class="text-sm text-slate-500 dark:text-gray-400">Belum ada riwayat booking terbaru.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="flex justify-between items-end">
                        <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white">Kalender Saya</h3>
                        <a href="{{ route('jadwal-saya') }}" class="text-xs font-medium text-teal-600 dark:text-kinetic-primary hover:text-teal-700 dark:hover:text-kinetic-secondary transition">Detail</a>
                    </div>
                    
                    <a class="block group relative transition-transform">
                        <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 transition-all group-hover:border-teal-400 dark:group-hover:border-kinetic-primary group-hover:shadow-md dark:group-hover:shadow-[0_0_20px_rgba(20,184,166,0.1)]">
                            <div id="dashboardCalendar" class="text-slate-800 dark:text-slate-200"></div>
                        </div>
                    </a>



                </div>
            </div>
            
        <footer class="mt-auto pt-8 pb-4 text-center">
            <p class="text-[9px] font-bold tracking-[0.2em] text-slate-400 dark:text-[#bbb] uppercase transition-colors duration-300">© 2026 SPACE.IN INFRASTRUCTURE ECOSYSTEM • V2.4.0 HIGH-PULSE EDITION</p>
        </footer>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Data dari PHP
        const rawBookings = @json($calendarBookings);

        const calendarEvents = rawBookings.map(b => {
            const statusLower = b.status ? b.status.toLowerCase() : '';
            let bgColor = statusLower === 'approved' ? '#14b8a6' : (statusLower === 'revising' ? '#ef4444' : '#3b82f6'); // Teal untuk Approved, Merah untuk Revising, Biru untuk Pending
            
            // Format split agar mendukung YYYY-MM-DD
            const datePart = b.booking_date.split('T')[0].split(' ')[0];

            return {
                id: b.id,
                title: b.room ? b.room.room_name : 'Ruangan',
                start: datePart, // Hanya tanggal agar dirender full-day style di kalender mini
                backgroundColor: bgColor,
                borderColor: bgColor,
                textColor: '#ffffff'
            };
        });

        // Inisialisasi FullCalendar untuk Widget Dashboard
        const calendarEl = document.getElementById('dashboardCalendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'id',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev',
                center: 'title',
                right: 'next'
            },
            height: 'auto',
            contentHeight: 'auto',
            fixedWeekCount: false, // Menghilangkan baris kosong di akhir bulan
            events: calendarEvents,
            
            // Tangani klik pada hari (cell)
            dateClick: function(info) {
                // Arahkan ke Jadwal Saya dengan parameter tanggal
                window.location.href = "{{ route('jadwal-saya') }}?date=" + info.dateStr;
            },
            
            // Arahkan ke halaman Jadwal Saya saat event di klik
            eventClick: function(info) {
                // Ambil tanggal event
                const eventDate = info.event.startStr;
                window.location.href = "{{ route('jadwal-saya') }}?date=" + eventDate;
            }
        });
        
        calendar.render();
    });
</script>