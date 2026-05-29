<x-app-layout>  
    @php
        // LOGIKA PENARIKAN DATA DINAMIS KHUSUS ADMIN UNIT
        $user = auth()->user();
        $unitId = $user->unit_id ?? null;

        // 1. Total Ruangan di Unit Ini
        $totalRuangan = \App\Models\Room::where('unit_id', $unitId)->count();

        // 2. Total Gedung (Menghitung jumlah gedung berbeda yang memiliki ruangan unit ini)
        $totalGedung = \App\Models\Room::where('unit_id', $unitId)->distinct('building_id')->count('building_id');

        // 3. Booking Aktif Bulan Ini (Pending & Approved)
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $bookingAktif = \App\Models\Booking::whereHas('room', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            })
            ->whereIn('status', ['Pending', 'Approved'])
            ->whereMonth('booking_date', $currentMonth)
            ->whereYear('booking_date', $currentYear)
            ->count();

        // 4. Distribusi Lokasi (Top 4 Gedung dengan ruangan terbanyak di unit ini)
        $distribusiLokasi = \App\Models\Room::where('unit_id', $unitId)
            ->with('building')
            ->selectRaw('building_id, count(*) as total')
            ->groupBy('building_id')
            ->orderByDesc('total')
            ->take(4)
            ->get();

        // 5. Aktivitas Terbaru (3 Peminjaman Terakhir)
        $aktivitasTerbaru = \App\Models\Booking::whereHas('room', function($q) use ($unitId) {
                $q->where('unit_id', $unitId);
            })
            ->with(['room', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
    @endphp

    <div class="relative px-8 pt-6 pb-8 space-y-6 flex flex-col min-h-full bg-slate-50 dark:bg-[#0A0A0A] transition-colors duration-300">
            
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-2">
            <div>
                <h2 class="font-heading text-5xl font-extrabold text-teal-600 dark:text-[#5EEAD4] tracking-tight transition-colors duration-300">Beranda Unit</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-md transition-colors duration-300">Selamat datang kembali, {{ $user->name ?? 'Admin' }}. Berikut adalah performa operasional ruangan di unit Anda hari ini.</p>
            </div>
            
            <button onclick="openModal()" class="bg-teal-600 dark:bg-[#5EEAD4] hover:bg-teal-700 dark:hover:bg-teal-400 text-white dark:text-teal-950 font-bold px-5 py-3 rounded-full transition flex items-center gap-2 shadow-sm font-heading">
                <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Ruangan
            </button>

            @include('user.admin_unit.modal-tambah-ruang')
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 flex flex-col justify-between overflow-hidden relative shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
                <h3 class="text-slate-400 dark:text-[#888] text-[10px] font-bold tracking-[0.2em] relative z-10 uppercase mb-4 transition-colors duration-300">TOTAL GEDUNG OPERASIONAL</h3>
                <div class="relative z-10 flex flex-col gap-6">
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-6xl font-black text-slate-800 dark:text-white tracking-tight transition-colors duration-300">{{ $totalGedung }}</span>
                        <span class="text-slate-500 dark:text-[#888] text-xs transition-colors duration-300">Lokasi</span>
                    </div>
                    
                    <div class="w-full bg-slate-100 dark:bg-[#333] h-1.5 rounded-full overflow-hidden mt-4 transition-colors duration-300">
                        <div class="bg-teal-500 dark:bg-[#5EEAD4] h-full w-[100%] rounded-full shadow-[0_0_10px_rgba(20,184,166,0.3)] dark:shadow-[0_0_10px_#5EEAD4] transition-colors duration-300"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 flex flex-col justify-between overflow-hidden relative shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
                <h3 class="text-slate-400 dark:text-[#888] text-[10px] font-bold tracking-[0.2em] uppercase mb-4 relative z-10 transition-colors duration-300">TOTAL RUANGAN UNIT</h3>
                <div class="relative z-10">
                    <div class="flex items-baseline gap-3 mt-2 mb-2">
                        <span class="text-6xl font-black text-slate-800 dark:text-white tracking-tight transition-colors duration-300">{{ $totalRuangan }}</span>
                        <span class="text-slate-500 dark:text-[#888] text-xs transition-colors duration-300">Ruangan Aktif</span>
                    </div>
                    
                    <div class="flex items-center gap-3 mt-8">
                        <div class="flex -space-x-2">
                            <div class="w-6 h-6 rounded-full bg-teal-100 dark:bg-[#0F3D35] border border-white dark:border-[#333] flex items-center justify-center text-[10px] text-teal-600 dark:text-[#5EEAD4] z-30 transition-colors duration-300"><i class="ph-bold ph-door"></i></div>
                            <div class="w-6 h-6 rounded-full bg-teal-100 dark:bg-[#0F3D35] border border-white dark:border-[#333] flex items-center justify-center text-[10px] text-teal-600 dark:text-[#5EEAD4] z-20 transition-colors duration-300"><i class="ph-bold ph-monitor"></i></div>
                            <div class="w-6 h-6 rounded-full bg-teal-100 dark:bg-[#0F3D35] border border-white dark:border-[#333] flex items-center justify-center text-[10px] text-teal-600 dark:text-[#5EEAD4] z-10 transition-colors duration-300"><i class="ph-bold ph-chalkboard"></i></div>
                        </div>
                        <span class="text-xs font-medium text-slate-500 dark:text-[#888] transition-colors duration-300">Fasilitas terdata</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/10 rounded-[24px] p-6 flex flex-col justify-between relative border border-teal-100 dark:border-teal-900/30 transition-colors duration-300">
                <div class="absolute top-6 right-6 w-2 h-2 rounded-full bg-teal-500 dark:bg-[#5EEAD4] animate-pulse shadow-[0_0_8px_rgba(20,184,166,0.6)] dark:shadow-[0_0_8px_#5EEAD4] transition-colors duration-300"></div>
                <h3 class="text-teal-600 dark:text-[#5EEAD4] text-[10px] font-bold tracking-[0.2em] uppercase mb-4 transition-colors duration-300">BOOKING AKTIF BULAN INI</h3>
                <div class="relative">
                    <div class="flex items-baseline gap-2 relative mt-2">
                        <span class="text-7xl font-black text-slate-300/30 dark:text-white absolute -top-4 -left-2 opacity-80" style="text-shadow: 0 4px 20px rgba(0,0,0,0.05); -webkit-text-stroke: 1px currentColor;">{{ $bookingAktif }}</span>
                        <span class="text-5xl font-black text-transparent bg-clip-text bg-white opacity-0">{{ $bookingAktif }}</span>
                        <span class="text-teal-600 dark:text-[#5EEAD4] text-xs font-bold z-10 absolute bottom-1 left-24 transition-colors duration-300">Reservasi</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-6 max-w-[240px] relative z-10 leading-relaxed font-medium transition-colors duration-300">
                        Total pengajuan masuk dan disetujui pada periode <span class="font-bold text-slate-800 dark:text-slate-200">{{ now()->translatedFormat('F Y') }}</span>.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent lg:col-span-2 flex flex-col transition-colors duration-300">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading transition-colors duration-300">Tren Booking Unit</h3>
                        <p class="text-slate-500 dark:text-[#888] text-xs mt-1 transition-colors duration-300">Ilustrasi kepadatan jadwal bulan ini</p>
                    </div>
                </div>
                
                <div class="flex-grow flex flex-col justify-end pt-4">
                    <div class="h-40 flex items-end justify-between gap-2 md:gap-3 lg:gap-4 px-2">
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[40%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[50%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[65%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[45%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[80%] transition-colors duration-300"></div>
                        <div class="w-full bg-teal-400 dark:bg-[#5EEAD4] rounded-t-md h-[100%] transition-colors duration-300 relative shadow-[0_0_15px_rgba(20,184,166,0.3)]"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[70%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[55%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[40%] transition-colors duration-300"></div>
                    </div>
                    <div class="flex justify-between mt-4 px-2 text-[9px] font-bold text-slate-400 dark:text-[#666] tracking-[0.15em] uppercase transition-colors duration-300">
                        <span>Awal Bulan</span>
                        <span>Pertengahan Bulan</span>
                        <span>Akhir Bulan</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent flex flex-col justify-between transition-colors duration-300">
                <div>
                    <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading mb-8 transition-colors duration-300">Distribusi Ruangan</h3>
                    <div class="space-y-6">
                        @forelse ($distribusiLokasi as $index => $dist)
                            @php
                                // Hitung persentase ruangan di gedung tsb terhadap total ruangan
                                $percentage = $totalRuangan > 0 ? round(($dist->total / $totalRuangan) * 100) : 0;
                                // Variasi warna bar
                                $colors = [
                                    'bg-teal-500 dark:bg-[#5EEAD4]', 
                                    'bg-teal-700 dark:bg-[#2B5047]', 
                                    'bg-cyan-500 dark:bg-[#06b6d4]', 
                                    'bg-cyan-700 dark:bg-[#164e63]'
                                ];
                                $barColor = $colors[$index % 4];
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-2">
                                    <span class="text-slate-700 dark:text-white transition-colors duration-300">{{ $dist->building->building_name ?? 'Gedung Unknown' }}</span>
                                    <span class="text-teal-600 dark:text-[#5EEAD4] transition-colors duration-300">{{ $percentage }}% ({{ $dist->total }})</span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-[#222] h-1.5 rounded-full overflow-hidden transition-colors duration-300">
                                    <div class="{{ $barColor }} h-full rounded-full transition-all duration-1000" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-500 italic text-center py-4">Belum ada data ruangan di unit ini.</p>
                        @endforelse
                    </div>
                </div>
                <a href="{{ route('laporan') ?? '#' }}" class="block text-center w-full py-3 mt-8 rounded-xl bg-slate-50 dark:bg-[#1A1A1A] hover:bg-slate-100 dark:hover:bg-[#222] border border-slate-200 dark:border-[#222] text-slate-600 dark:text-[#ccc] hover:text-slate-900 dark:hover:text-white text-xs font-bold transition">
                    Lihat Rekapitulasi Lengkap
                </a>
            </div>
        </div>
        
        <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading transition-colors duration-300">Aktivitas Peminjaman Terbaru</h3>
                <a href="#" class="text-xs font-bold text-teal-600 dark:text-[#5EEAD4] hover:text-teal-700 dark:hover:text-[#4fd1c5] transition-colors duration-300">Semua Log</a>
            </div>
            
            <div class="flex flex-col">
                @forelse ($aktivitasTerbaru as $aktivitas)
                    @php
                        // Logic untuk icon dan warna berdasarkan status
                        $icon = 'ph-calendar-check';
                        $colorClass = 'text-slate-500 bg-slate-50 border-slate-200';
                        $badgeClass = 'text-slate-600 bg-slate-100 border-slate-200';
                        $statusText = $aktivitas->status;

                        if ($aktivitas->status == 'Pending') {
                            $icon = 'ph-clock-countdown';
                            $colorClass = 'text-sky-500 bg-sky-50 border-sky-100 dark:text-[#38bdf8] dark:bg-[#1a2d3d] dark:border-[#274059]';
                            $badgeClass = 'text-sky-600 bg-sky-50 border-sky-200 dark:text-[#38bdf8] dark:bg-[#0c1a26] dark:border-[#1a364d]';
                            $statusText = 'Menunggu Approval';
                        } elseif ($aktivitas->status == 'Approved') {
                            $icon = 'ph-check-circle';
                            $colorClass = 'text-teal-600 bg-teal-50 border-teal-100 dark:text-[#5EEAD4] dark:bg-[#082a20] dark:border-[#1a4a3a]';
                            $badgeClass = 'text-teal-700 bg-teal-50 border-teal-200 dark:text-[#10B981] dark:bg-[#052E16] dark:border-[#064E3B]';
                            $statusText = 'Disetujui';
                        } elseif ($aktivitas->status == 'Rejected' || $aktivitas->status == 'Cancelled') {
                            $icon = 'ph-warning-circle';
                            $colorClass = 'text-red-500 bg-red-50 border-red-100 dark:text-[#f87171] dark:bg-[#3d1616] dark:border-[#592222]';
                            $badgeClass = 'text-red-600 bg-red-50 border-red-200 dark:text-[#F87171] dark:bg-[#450A0A] dark:border-[#7F1D1D]';
                            $statusText = 'Ditolak / Batal';
                        }
                    @endphp
                    
                    <a href="{{ route('detail', $aktivitas->id) }}" class="flex items-center justify-between py-4 border-b border-slate-100 dark:border-[#222] transition-colors duration-300 hover:bg-slate-50 dark:hover:bg-[#151515] -mx-4 px-4 rounded-xl cursor-pointer">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full {{ $colorClass }} flex items-center justify-center shrink-0 transition-colors duration-300">
                                <i class="ph-bold {{ $icon }} text-lg"></i>
                            </div>
                            <div>
                                <h4 class="text-slate-800 dark:text-white font-bold text-sm transition-colors duration-300">{{ $aktivitas->event_name }}</h4>
                                <p class="text-slate-500 dark:text-[#888] text-xs mt-0.5 transition-colors duration-300">
                                    {{ $aktivitas->room->room_name ?? 'N/A' }} • Oleh: {{ $aktivitas->user->name ?? 'User' }} • {{ $aktivitas->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <span class="text-[9px] font-bold px-3 py-1 rounded-full uppercase tracking-wider transition-colors duration-300 border {{ $badgeClass }}">
                            {{ $statusText }}
                        </span>
                    </a>
                @empty
                    <div class="py-8 text-center">
                        <p class="text-slate-500 text-sm">Belum ada aktivitas peminjaman di unit ini.</p>
                    </div>
                @endforelse
            </div>
        </div>
            
        <footer class="mt-auto pt-8 pb-4 text-center">
            <p class="text-[9px] font-bold tracking-[0.2em] text-slate-400 dark:text-[#bbb] uppercase transition-colors duration-300">© {{ now()->year }} SPACE.IN INFRASTRUCTURE ECOSYSTEM • V2.4.0 HIGH-PULSE EDITION</p>
        </footer>
    </div>

    @push('scripts')
    <script>
        function openModal() {
            const modal = document.getElementById('modalTambahRuang');
            if (modal) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.querySelector('div').classList.remove('scale-95');
                }, 10);
            }
        }

        function closeModal() {
            const modal = document.getElementById('modalTambahRuang');
            if (modal) {
                modal.classList.add('opacity-0');
                modal.querySelector('div').classList.add('scale-95');
                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }
        }
    </script>
    @endpush
</x-app-layout>