<x-app-layout>
    @php
        
        // 1. Statistik Utama (Hitungan Global)
        $totalGedung = \App\Models\Building::count();
        $totalRooms = \App\Models\Room::count();
        $bookingAktif = \App\Models\Booking::whereIn('status', ['Pending', 'Approved'])->count();

        // 2. Distribusi Lokasi (Top 4 Gedung dengan ruangan terbanyak secara Global)
        $distribusiLokasi = \App\Models\Building::withCount('rooms')
            ->orderBy('rooms_count', 'desc')
            ->take(4)
            ->get();

        // 3. Aktivitas Terbaru (3 Peminjaman Terakhir dari seluruh Unit/Sistem)
        $aktivitasTerbaru = \App\Models\Booking::with(['room.building', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
            
        // 4. Hitung tren sederhana (perbandingan booking bulan ini vs bulan lalu)
        $bulanIni = \App\Models\Booking::whereMonth('created_at', now()->month)->count();
        $bulanLalu = \App\Models\Booking::whereMonth('created_at', now()->subMonth()->month)->count();
        $kenaikan = $bulanLalu > 0 ? round((($bulanIni - $bulanLalu) / $bulanLalu) * 100) : 100;
    @endphp

    <div class="relative px-8 pt-6 pb-8 space-y-6 flex flex-col min-h-full bg-slate-50 dark:bg-[#0A0A0A] transition-colors duration-300">
            
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-2">
            <div>
                <h2 class="font-heading text-5xl font-extrabold text-teal-600 dark:text-[#5EEAD4] tracking-tight transition-colors duration-300">Pusat Kendali</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-md transition-colors duration-300">Selamat datang kembali, Administrator Utama. Berikut adalah ringkasan operasional seluruh infrastruktur hari ini.</p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('unit') }}" class="bg-teal-600 dark:bg-[#5EEAD4] hover:bg-teal-700 dark:hover:bg-teal-400 text-white dark:text-teal-950 font-bold px-5 py-3 rounded-full transition flex items-center gap-2 shadow-sm font-heading">
                    <i class="ph-bold ph-plus-circle text-lg"></i> Kelola Unit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 flex flex-col justify-between overflow-hidden relative shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
                <h3 class="text-slate-400 dark:text-[#888] text-[10px] font-bold tracking-[0.2em] relative z-10 uppercase mb-4 transition-colors duration-300">TOTAL GEDUNG</h3>
                <div class="relative z-10 flex flex-col gap-6">
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-6xl font-black text-slate-800 dark:text-white tracking-tight transition-colors duration-300">{{ $totalGedung }}</span>
                        <span class="text-teal-600 dark:text-[#5EEAD4] text-xs font-bold flex items-center transition-colors duration-300"><i class="ph-bold ph-buildings mr-1"></i>Lokasi</span>
                    </div>
                    
                    <div class="w-full bg-slate-100 dark:bg-[#333] h-1.5 rounded-full overflow-hidden mt-4 transition-colors duration-300">
                        <div class="bg-teal-500 dark:bg-[#5EEAD4] h-full w-full rounded-full shadow-[0_0_10px_rgba(20,184,166,0.3)] dark:shadow-[0_0_10px_#5EEAD4] transition-colors duration-300"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 flex flex-col justify-between overflow-hidden relative shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
                <h3 class="text-slate-400 dark:text-[#888] text-[10px] font-bold tracking-[0.2em] uppercase mb-4 relative z-10 transition-colors duration-300">TOTAL UNIT RUANGAN</h3>
                <div class="relative z-10">
                    <div class="flex items-baseline gap-3 mt-2 mb-2">
                        <span class="text-6xl font-black text-slate-800 dark:text-white tracking-tight transition-colors duration-300">{{ $totalRooms }}</span>
                        <span class="text-slate-500 dark:text-[#888] text-xs transition-colors duration-300">Aktif Nasional</span>
                    </div>
                    
                    <div class="flex items-center gap-3 mt-8">
                        <div class="flex -space-x-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1A1A1A] border border-white dark:border-[#333] flex items-center justify-center text-[8px] text-slate-600 dark:text-[#ddd] font-bold z-30 transition-colors duration-300">A1</div>
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1A1A1A] border border-white dark:border-[#333] flex items-center justify-center text-[8px] text-slate-600 dark:text-[#ddd] font-bold z-20 transition-colors duration-300">A2</div>
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1A1A1A] border border-white dark:border-[#333] flex items-center justify-center text-[8px] text-slate-600 dark:text-[#ddd] font-bold z-10 transition-colors duration-300">A3</div>
                        </div>
                        <span class="text-xs font-medium text-slate-500 dark:text-[#888] transition-colors duration-300">Semua Fasilitas</span>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/10 rounded-[24px] p-6 flex flex-col justify-between relative border border-teal-100 dark:border-teal-900/30 transition-colors duration-300">
                <div class="absolute top-6 right-6 w-2 h-2 rounded-full bg-teal-500 dark:bg-[#5EEAD4] animate-pulse shadow-[0_0_8px_rgba(20,184,166,0.6)] dark:shadow-[0_0_8px_#5EEAD4] transition-colors duration-300"></div>
                <h3 class="text-teal-600 dark:text-[#5EEAD4] text-[10px] font-bold tracking-[0.2em] uppercase mb-4 transition-colors duration-300">BOOKING AKTIF GLOBAL</h3>
                <div class="relative">
                    <div class="flex items-baseline gap-2 relative mt-2">
                        <span class="text-7xl font-black text-slate-300/30 dark:text-white absolute -top-4 -left-2 opacity-80" style="text-shadow: 0 4px 20px rgba(0,0,0,0.05); -webkit-text-stroke: 1px currentColor;">{{ $bookingAktif }}</span>
                        <span class="text-5xl font-black text-transparent bg-clip-text bg-white opacity-0">{{ $bookingAktif }}</span>
                        <span class="text-teal-600 dark:text-[#5EEAD4] text-xs font-bold z-10 absolute bottom-1 left-24 transition-colors duration-300">Real-time</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-6 max-w-[240px] relative z-10 leading-relaxed font-medium transition-colors duration-300">
                        Peningkatan <span class="font-bold text-slate-800 dark:text-slate-200">{{ $kenaikan }}%</span> penggunaan ruang kolaborasi dibanding bulan lalu.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent lg:col-span-2 flex flex-col transition-colors duration-300">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading transition-colors duration-300">Tren Booking Global</h3>
                        <p class="text-slate-500 dark:text-[#888] text-xs mt-1 transition-colors duration-300">Statistik penggunaan di seluruh unit</p>
                    </div>
                </div>
                
                <div class="flex-grow flex flex-col justify-end pt-4">
                    <div class="h-40 flex items-end justify-between gap-2 md:gap-3 lg:gap-4 px-2">
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 rounded-t-md h-[40%] transition-all"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 rounded-t-md h-[60%] transition-all"></div>
                        <div class="w-full bg-teal-400 dark:bg-[#5EEAD4] rounded-t-md h-[100%] transition-all shadow-lg relative"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 rounded-t-md h-[45%] transition-all"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 rounded-t-md h-[70%] transition-all"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 rounded-t-md h-[55%] transition-all"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 rounded-t-md h-[80%] transition-all"></div>
                    </div>
                    <div class="flex justify-between mt-4 px-2 text-[9px] font-bold text-slate-400 dark:text-[#666] tracking-[0.15em] uppercase">
                        <span>Januari</span>
                        <span>Maret</span>
                        <span>Mei</span>
                        <span>Juli</span>
                        <span>September</span>
                        <span>November</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent flex flex-col justify-between transition-colors duration-300">
                <div>
                    <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading mb-8 transition-colors duration-300">Distribusi Gedung</h3>
                    <div class="space-y-6">
                        @foreach($distribusiLokasi as $index => $gedung)
                            @php
                                $percent = $totalRooms > 0 ? round(($gedung->rooms_count / $totalRooms) * 100) : 0;
                                // Multi-teal palette
                                $colors = [
                                    'bg-teal-500 dark:bg-[#5EEAD4]', 
                                    'bg-teal-700 dark:bg-[#2B5047]', 
                                    'bg-cyan-500 dark:bg-[#06b6d4]', 
                                    'bg-cyan-700 dark:bg-[#164e63]'
                                ];
                            @endphp
                            <div>
                                <div class="flex justify-between text-xs font-bold mb-2">
                                    <span class="text-slate-700 dark:text-white">{{ $gedung->building_name }}</span>
                                    <span class="text-teal-600 dark:text-[#5EEAD4]">{{ $percent }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 dark:bg-[#222] h-1.5 rounded-full overflow-hidden">
                                    <div class="{{ $colors[$index % 4] }} h-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <a href="{{ route('fasilitas') }}" class="w-full py-3 mt-8 rounded-xl bg-slate-50 dark:bg-[#1A1A1A] hover:bg-slate-100 dark:hover:bg-[#222] border border-slate-200 dark:border-[#222] text-slate-600 dark:text-[#ccc] text-xs font-bold text-center transition">
                    Kelola Seluruh Gedung
                </a>
            </div>
        </div>
        
        <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading transition-colors duration-300">Aktivitas Terbaru Sistem</h3>
                <span class="text-xs font-bold text-teal-600 dark:text-[#5EEAD4]">Seluruh Unit</span>
            </div>
            
            <div class="flex flex-col">
                @forelse($aktivitasTerbaru as $log)
                <a href="{{ route('detail', $log->id) }}" class="flex items-center justify-between py-4 border-b border-slate-100 dark:border-[#222] transition-colors duration-300 hover:bg-slate-50 dark:hover:bg-[#151515] -mx-4 px-4 rounded-xl">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-[#082a20] border border-teal-100 dark:border-[#1a4a3a] text-teal-600 dark:text-[#5EEAD4] flex items-center justify-center shrink-0">
                            <i class="ph-bold ph-activity text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-bold text-sm">{{ $log->event_name }}</h4>
                            <p class="text-slate-500 dark:text-[#888] text-xs mt-0.5">
                                {{ $log->room->room_name }} ({{ $log->room->building->building_name ?? '-' }}) • Oleh: {{ $log->user->name ?? 'User' }}
                            </p>
                        </div>
                    </div>
                    <span class="text-[10px] text-slate-400">{{ $log->created_at->diffForHumans() }}</span>
                </a>
                @empty
                    <p class="text-sm text-slate-500 text-center py-6">Belum ada aktivitas sistem terdeteksi.</p>
                @endforelse
            </div>
        </div>
            
        <footer class="mt-auto pt-8 pb-4 text-center">
            <p class="text-[9px] font-bold tracking-[0.2em] text-slate-400 dark:text-[#bbb] uppercase transition-colors duration-300">© 2026 SPACE.IN GLOBAL INFRASTRUCTURE • V2.4.0 ADMINISTRATOR UTAMA EDITION</p>
        </footer>
    </div>
</x-app-layout>