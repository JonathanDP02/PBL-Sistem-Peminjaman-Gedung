<x-app-layout title="Jadwal Saya">
    <div class="relative px-8 pt-4 pb-8 space-y-8 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2 transition-colors">Jadwal Saya</h2>
                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-gray-400 transition-colors">
                    <i class="ph ph-calendar-blank"></i>
                    <span>{{ $monthName }} {{ $year }} • Minggu ke-{{ now()->weekOfMonth }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 flex items-center gap-4 shadow-sm dark:shadow-none min-w-[220px] transition-colors">
                    <div class="w-12 h-12 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary flex items-center justify-center border border-teal-100 dark:border-transparent transition-colors">
                        <i class="ph ph-clock text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase">Jam Terpakai</p>
                        <p class="font-heading text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $hoursUsed }} <span class="text-sm font-normal text-slate-500 dark:text-gray-400">Jam</span></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 flex items-center gap-4 shadow-sm dark:shadow-none min-w-[220px] transition-colors">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-transparent transition-colors">
                        <i class="ph ph-shield-check text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase">Skor Kepatuhan</p>
                        <p class="font-heading text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ number_format($complianceScore, 1) }} <span class="text-sm font-normal text-slate-500 dark:text-gray-400">/ 5.0</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center bg-white dark:bg-[#151515] p-4 rounded-2xl border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none gap-4 transition-colors">
            <div class="flex flex-wrap items-center gap-6 px-2">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-kinetic-primary"></span> Tersedia / Dikonfirmasi
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Tertunda
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Terkunci
                </div>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                {{-- Tombol Filter dengan ID untuk JS --}}
                <button id="filterBtn" type="button" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm font-bold text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-[#222] transition-colors">
                    <i class="ph ph-faders text-lg"></i> Filter
                </button>
                <a href="{{ route('booking') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-kinetic-primary hover:bg-teal-600 dark:hover:bg-kinetic-secondary text-white dark:text-slate-900 rounded-xl text-sm font-bold shadow-[0_0_15px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-0.5">
                    <i class="ph-bold ph-plus text-lg"></i> Booking Baru
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none overflow-hidden transition-colors">
            <div class="overflow-x-auto custom-scrollbar">
                <div class="w-max">
                    
                    @php
                        // Menentukan jumlah hari sesuai bulan dan tahun
                        $daysInMonth = \Carbon\Carbon::create($year, $month)->daysInMonth;
                        
                        // Menangkap query filter status (default: centang semua)
                        $statusFilters = request('status_filters', ['tersedia', 'tertunda', 'terkunci']);
                        if (!is_array($statusFilters)) $statusFilters = [$statusFilters];
                    @endphp

                    {{-- HEADER HARI / TANGGAL --}}
                    <div class="grid border-b border-slate-100 dark:border-[#1E1E1E]" style="grid-template-columns: 100px repeat({{ $daysInMonth }}, 180px);">
                        <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] flex items-center justify-center bg-slate-50 dark:bg-[#111]">
                            <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">Waktu</span>
                        </div>
                        @for ($i = 1; $i <= $daysInMonth; $i++)
                        @php 
                            $isToday = ($i == now()->day && $month == now()->month && $year == now()->year);
                        @endphp
                        <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] text-center transition-colors {{ $isToday ? 'bg-teal-50/50 dark:bg-[#1A1A1A]' : '' }}">
                            <p class="text-[10px] font-bold {{ $isToday ? 'text-kinetic-primary' : 'text-slate-400' }} tracking-widest uppercase mb-1">{{ substr($monthName, 0, 3) }}</p>
                            <p class="text-xl font-heading font-bold {{ $isToday ? 'text-kinetic-primary' : 'text-slate-900 dark:text-white' }}">{{ $i }}</p>
                        </div>
                        @endfor
                    </div>

                    {{-- BODY JADWAL BERDASARKAN JAM --}}
                    <div class="max-h-[480px] overflow-y-auto custom-scrollbar">
                        @php
                            $startHour = 7;
                            $endHour = 22;
                        @endphp

                        @for ($hour = $startHour; $hour <= $endHour; $hour++)
                        <div class="grid border-b border-slate-100 dark:border-[#1E1E1E] group" style="grid-template-columns: 100px repeat({{ $daysInMonth }}, 180px);">
                            
                            <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] flex items-start justify-center bg-slate-50 dark:bg-[#111] sticky left-0 z-10">
                                <span class="text-xs font-bold text-slate-500 dark:text-gray-500">{{ sprintf('%02d:00', $hour) }}</span>
                            </div>

                            @for ($day = 1; $day <= $daysInMonth; $day++)
                            @php
                                $currentDateStr = \Carbon\Carbon::create($year, $month, $day)->format('Y-m-d');
                                
                                // LOGIKA FILTER DITERAPKAN DI SINI
                                $currentSlotBookings = $allBookings->filter(function($booking) use ($currentDateStr, $hour, $statusFilters) {
                                    if ($booking->booking_date !== $currentDateStr) return false;
                                    
                                    $startH = (int) date('H', strtotime($booking->start_time));
                                    $endH = (int) date('H', strtotime($booking->end_time));
                                    if ($hour < $startH || $hour >= $endH) return false;

                                    $isLocked = str_contains(strtoupper($booking->event_name), '[MAINTENANCE HARD-LOCK]');
                                    
                                    // Saring berdasarkan Status yang dicentang di Modal
                                    if ($isLocked && !in_array('terkunci', $statusFilters)) return false;
                                    if (!$isLocked && $booking->status === 'Approved' && !in_array('tersedia', $statusFilters)) return false;
                                    if (!$isLocked && $booking->status === 'Pending' && !in_array('tertunda', $statusFilters)) return false;

                                    return true;
                                });
                            @endphp

                            <div class="p-2 border-r border-slate-100 dark:border-[#1E1E1E] min-h-[120px] transition-colors hover:bg-slate-50/50 dark:hover:bg-white/5 flex flex-col gap-2">
                                
                                @if($currentSlotBookings->isNotEmpty())
                                    @foreach($currentSlotBookings as $b)
                                        @if(str_contains(strtoupper($b->event_name), '[MAINTENANCE HARD-LOCK]'))
                                            {{-- Tampilan Terkunci --}}
                                            <div class="h-full bg-red-50 dark:bg-[#2A1515] border border-red-200 dark:border-red-900/50 rounded-xl p-3 shadow-sm cursor-not-allowed">
                                                <span class="text-[9px] font-bold text-red-600 dark:text-red-400 uppercase tracking-wider block mb-1">Locked by IT</span>
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">{{ $b->room->room_name ?? 'Ruangan' }}</h4>
                                            </div>
                                        @elseif($b->status === 'Approved')
                                            {{-- Tampilan Dikonfirmasi --}}
                                            <div class="h-full bg-teal-50 dark:bg-[#102A24] border border-teal-200 dark:border-teal-900/50 rounded-xl p-3 shadow-sm relative cursor-pointer">
                                                <i class="ph-fill ph-check-circle text-kinetic-primary absolute top-3 right-3 text-sm"></i>
                                                <span class="text-[9px] font-bold text-teal-700 dark:text-kinetic-primary uppercase tracking-wider block mb-1">Confirmed</span>
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">{{ $b->room->room_name ?? 'Ruangan' }}</h4>
                                            </div>
                                        @elseif($b->status === 'Pending')
                                            {{-- Tampilan Menunggu Persetujuan --}}
                                            <div class="h-full bg-blue-50 dark:bg-[#101E28] border border-blue-200 dark:border-blue-900/50 rounded-xl p-3 shadow-sm cursor-pointer">
                                                <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block mb-1">Pending Approval</span>
                                                <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">{{ $b->room->room_name ?? 'Ruangan' }}</h4>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    {{-- Tampilan Kosong / Benar-benar Tersedia --}}
                                    <div class="h-full w-full border border-dashed border-slate-200 dark:border-white/10 rounded-xl flex items-center justify-center group/btn cursor-pointer hover:border-kinetic-primary/50 hover:bg-teal-50 dark:hover:bg-kinetic-primary/5 transition-all">
                                        <i class="ph ph-plus text-slate-300 dark:text-white/10 group-hover/btn:text-kinetic-primary text-xl"></i>
                                    </div>
                                @endif

                            </div>
                            @endfor
                        </div>
                        @endfor
                    </div> 
                </div> 
            </div> 
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-4">
            {{-- REKOMENDASI --}}
            <div class="lg:col-span-2 bg-white dark:bg-[#151515] rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-heading font-bold flex items-center gap-2 text-slate-900 dark:text-white transition-colors">
                        <i class="ph-fill ph-sparkle text-kinetic-primary text-lg"></i> Rekomendasi Slot Hari Ini
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $recommendedRooms = \App\Models\Room::take(2)->get();
                        $recTimes = [['start' => '15:00', 'end' => '17:00'], ['start' => '08:30', 'end' => '10:00']];
                        $todayStr = now()->format('Y-m-d');
                    @endphp

                    @foreach($recommendedRooms as $index => $room)
                        @if(isset($recTimes[$index]))
                        <a href="{{ route('booking', ['room_id' => $room->id, 'date' => $todayStr, 'start_time' => $recTimes[$index]['start']]) }}" class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 flex justify-between items-center group cursor-pointer hover:border-teal-400 dark:hover:border-kinetic-primary transition-colors">
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ $room->room_name }}</p>
                                <p class="font-bold text-sm text-slate-900 dark:text-white mb-3 transition-colors">{{ $recTimes[$index]['start'] }} - {{ $recTimes[$index]['end'] }}</p>
                                <span class="px-2.5 py-1 bg-teal-100/50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary text-[10px] font-bold rounded uppercase tracking-wider">Tersedia</span>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-white dark:bg-[#222] flex items-center justify-center border border-slate-200 dark:border-[#333] group-hover:bg-teal-50 dark:group-hover:bg-kinetic-primary/10 transition-colors">
                                <i class="ph ph-arrow-right text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                            </div>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-[#151515] rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none flex flex-col justify-center relative overflow-hidden transition-colors">
                <div class="absolute -right-10 -bottom-10 opacity-5 dark:opacity-10 pointer-events-none">
                    <i class="ph-fill ph-chart-bar text-9xl text-slate-900 dark:text-white"></i>
                </div>
                
                <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mb-3 relative z-10">Kapasitas Mingguan</p>
                <div class="flex items-end gap-3 mb-6 relative z-10">
                    <h2 class="font-heading text-6xl font-extrabold text-slate-900 dark:text-white transition-colors">84<span class="text-3xl text-slate-400">%</span></h2>
                    <span class="text-xs font-bold text-teal-600 dark:text-kinetic-primary mb-2.5 flex items-center gap-1 bg-teal-50 dark:bg-kinetic-primary/10 px-2 py-1 rounded-md">
                        <i class="ph-bold ph-arrow-up text-[10px]"></i> 12% dari bln lalu
                    </span>
                </div>
                
                <div class="h-2.5 w-full bg-slate-100 dark:bg-[#2A2A2A] rounded-full overflow-hidden relative z-10 transition-colors">
                    <div class="h-full bg-gradient-to-r from-kinetic-primary to-kinetic-secondary rounded-full shadow-[0_0_10px_rgba(20,184,166,0.5)]" style="width: 84%"></div>
                </div>
            </div>
            
        </div>

    </div>

    {{-- MODAL FILTER JADWAL BERDASARKAN STATUS --}}
    <div id="filterModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-sm flex items-center justify-center transition-all duration-300 opacity-0">
        <div id="filterModalContent" class="bg-white dark:bg-[#151515] rounded-3xl border border-slate-200 dark:border-kinetic-border p-6 md:p-8 w-full max-w-sm shadow-2xl transform scale-95 transition-all duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold font-heading text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="ph-fill ph-faders text-kinetic-primary"></i> Filter Status
                </h3>
                <button type="button" id="closeFilterBtn" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 dark:bg-[#222] text-slate-500 hover:text-red-500 transition-colors">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            
            <form action="{{ route('jadwal-saya') }}" method="GET" class="space-y-6">
                {{-- Mempertahankan bulan/tahun yang ada agar tidak keriset saat difilter --}}
                <input type="hidden" name="month" value="{{ request('month', $month) }}">
                <input type="hidden" name="year" value="{{ request('year', $year) }}">

                <div class="space-y-3">
                    <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-[#2A2A2A] rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors">
                        <input type="checkbox" name="status_filters[]" value="tersedia" class="w-5 h-5 text-teal-500 rounded border-slate-300 focus:ring-teal-500" {{ in_array('tersedia', $statusFilters) ? 'checked' : '' }}>
                        <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Tersedia / Confirmed</span>
                        <span class="ml-auto w-3 h-3 rounded-full bg-kinetic-primary"></span>
                    </label>
                    
                    <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-[#2A2A2A] rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors">
                        <input type="checkbox" name="status_filters[]" value="tertunda" class="w-5 h-5 text-blue-500 rounded border-slate-300 focus:ring-blue-500" {{ in_array('tertunda', $statusFilters) ? 'checked' : '' }}>
                        <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Tertunda (Pending)</span>
                        <span class="ml-auto w-3 h-3 rounded-full bg-blue-500"></span>
                    </label>

                    <label class="flex items-center gap-3 p-3 border border-slate-200 dark:border-[#2A2A2A] rounded-xl cursor-pointer hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors">
                        <input type="checkbox" name="status_filters[]" value="terkunci" class="w-5 h-5 text-red-500 rounded border-slate-300 focus:ring-red-500" {{ in_array('terkunci', $statusFilters) ? 'checked' : '' }}>
                        <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Terkunci (Maintenance)</span>
                        <span class="ml-auto w-3 h-3 rounded-full bg-red-500"></span>
                    </label>
                </div>

                <div class="pt-2 flex gap-3">
                    <a href="{{ route('jadwal-saya', ['month' => request('month'), 'year' => request('year')]) }}" class="flex-1 flex justify-center items-center px-4 py-3 border border-slate-200 dark:border-[#2A2A2A] text-slate-600 dark:text-gray-300 rounded-xl text-sm font-bold hover:bg-slate-50 dark:hover:bg-[#222] transition-colors">
                        Reset Semua
                    </a>
                    <button type="submit" class="flex-1 flex justify-center items-center px-4 py-3 bg-kinetic-primary hover:bg-teal-600 dark:hover:bg-kinetic-secondary text-white dark:text-slate-900 rounded-xl text-sm font-bold shadow-[0_0_15px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-0.5">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const filterBtn = document.getElementById('filterBtn');
        const filterModal = document.getElementById('filterModal');
        const filterModalContent = document.getElementById('filterModalContent');
        const closeFilterBtn = document.getElementById('closeFilterBtn');

        // Buka Modal
        filterBtn.addEventListener('click', () => {
            filterModal.classList.remove('hidden');
            setTimeout(() => {
                filterModal.classList.remove('opacity-0');
                filterModalContent.classList.remove('scale-95');
                filterModalContent.classList.add('scale-100');
            }, 10);
        });

        // Tutup Modal
        const closeModal = () => {
            filterModal.classList.add('opacity-0');
            filterModalContent.classList.remove('scale-100');
            filterModalContent.classList.add('scale-95');
            setTimeout(() => {
                filterModal.classList.add('hidden');
            }, 300);
        };

        closeFilterBtn.addEventListener('click', closeModal);

        // Tutup jika user klik area background gelap di luar modal
        filterModal.addEventListener('click', (e) => {
            if (e.target === filterModal) {
                closeModal();
            }
        });
    });
</script>