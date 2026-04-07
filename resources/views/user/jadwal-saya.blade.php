<x-app-layout title="Jadwal Saya">
    <div class="relative px-8 pt-4 pb-8 space-y-8 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2 transition-colors">Jadwal Saya</h2>
                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-gray-400 transition-colors">
                    <i class="ph ph-calendar-blank"></i>
                    <span>Oktober 2026 • Minggu ke-3</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 flex items-center gap-4 shadow-sm dark:shadow-none min-w-[220px] transition-colors">
                    <div class="w-12 h-12 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary flex items-center justify-center border border-teal-100 dark:border-transparent transition-colors">
                        <i class="ph ph-clock text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase">Jam Terpakai</p>
                        <p class="font-heading text-2xl font-bold text-slate-900 dark:text-white transition-colors">12 <span class="text-sm font-normal text-slate-500 dark:text-gray-400">Jam</span></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 flex items-center gap-4 shadow-sm dark:shadow-none min-w-[220px] transition-colors">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-transparent transition-colors">
                        <i class="ph ph-shield-check text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase">Skor Kepatuhan</p>
                        <p class="font-heading text-2xl font-bold text-slate-900 dark:text-white transition-colors">4.8 <span class="text-sm font-normal text-slate-500 dark:text-gray-400">/ 5.0</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center bg-white dark:bg-[#151515] p-4 rounded-2xl border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none gap-4 transition-colors">
            <div class="flex flex-wrap items-center gap-6 px-2">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-kinetic-primary"></span> Tersedia
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Tertunda
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Terkunci
                </div>
            </div>
            <div class="flex gap-3 w-full md:w-auto">
                <button class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm font-bold text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-[#222] transition-colors">
                    <i class="ph ph-faders text-lg"></i> Filter
                </button>
                <button class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-kinetic-primary hover:bg-teal-600 dark:hover:bg-kinetic-secondary text-white dark:text-slate-900 rounded-xl text-sm font-bold shadow-[0_0_15px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-0.5">
                    <i class="ph-bold ph-plus text-lg"></i> Booking Baru
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-[#151515] rounded-3xl border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none overflow-hidden transition-colors">
            <div class="overflow-x-auto custom-scrollbar">
                <div class="w-max">
                    
                    <div class="grid grid-cols-[100px_repeat(30,_180px)] border-b border-slate-100 dark:border-[#1E1E1E]">
                        <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] flex items-center justify-center bg-slate-50 dark:bg-[#111]">
                            <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">Waktu</span>
                        </div>
                        @for ($i = 1; $i <= 30; $i++)
                        <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] text-center transition-colors {{ $i == 17 ? 'bg-teal-50/50 dark:bg-[#1A1A1A]' : '' }}">
                            <p class="text-[10px] font-bold {{ $i == 17 ? 'text-kinetic-primary' : 'text-slate-400' }} tracking-widest uppercase mb-1">Okt</p>
                            <p class="text-xl font-heading font-bold {{ $i == 17 ? 'text-kinetic-primary' : 'text-slate-900 dark:text-white' }}">{{ $i }}</p>
                        </div>
                        @endfor
                    </div>

                    <div class="max-h-[480px] overflow-y-auto custom-scrollbar">
                        @php
                            $startHour = 7;
                            $endHour = 22;
                        @endphp

                        @for ($hour = $startHour; $hour <= $endHour; $hour++)
                        <div class="grid grid-cols-[100px_repeat(30,_180px)] border-b border-slate-100 dark:border-[#1E1E1E] group">
                            
                            <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] flex items-start justify-center bg-slate-50 dark:bg-[#111] sticky left-0 z-10">
                                <span class="text-xs font-bold text-slate-500 dark:text-gray-500">{{ sprintf('%02d:00', $hour) }}</span>
                            </div>

                            @for ($day = 1; $day <= 30; $day++)
                            <div class="p-2 border-r border-slate-100 dark:border-[#1E1E1E] min-h-[120px] transition-colors hover:bg-slate-50/50 dark:hover:bg-white/5">
                                
                                {{-- Contoh Tampilan Dummy Jadwal --}}
                                @if($hour == 9 && $day == 17)
                                    <div class="h-full bg-teal-50 dark:bg-[#102A24] border border-teal-200 dark:border-teal-900/50 rounded-xl p-3 shadow-sm relative cursor-pointer">
                                        <i class="ph-fill ph-check-circle text-kinetic-primary absolute top-3 right-3 text-sm"></i>
                                        <span class="text-[9px] font-bold text-teal-700 dark:text-kinetic-primary uppercase tracking-wider block mb-1">Confirmed</span>
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Aula Utama</h4>
                                    </div>
                                @elseif($hour == 11 && $day == 19)
                                    <div class="h-full bg-blue-50 dark:bg-[#101E28] border border-blue-200 dark:border-blue-900/50 rounded-xl p-3 shadow-sm cursor-pointer">
                                        <i class="ph-fill ph-clock-counter-clockwise text-blue-500 absolute top-3 right-3 text-sm"></i>
                                        <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block mb-1">Pending Approval</span>
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Ruang Diskusi 04</h4>
                                    </div>
                                @elseif($hour == 8 && $day == 17)
                                    <div class="h-full bg-red-50 dark:bg-[#2A1515] border border-red-200 dark:border-red-900/50 rounded-xl p-3 shadow-sm cursor-not-allowed">
                                        <i class="ph-fill ph-lock-key text-red-500 absolute top-3 right-3 text-sm"></i>
                                        <span class="text-[9px] font-bold text-red-600 dark:text-red-400 uppercase tracking-wider block mb-1">Locked by IT</span>
                                        <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Lab Komputer A</h4>
                                    </div>
                                @else
                                    <div class="h-full w-full border border-dashed border-slate-200 dark:border-white/10 rounded-xl flex items-center justify-center group/btn cursor-pointer hover:border-kinetic-primary/50 hover:bg-teal-50 dark:hover:bg-kinetic-primary/5 transition-all">
                                        <i class="ph ph-plus text-slate-300 dark:text-white/10 group-hover/btn:text-kinetic-primary text-xl"></i>
                                    </div>
                                @endif

                            </div>
                            @endfor
                        </div>
                        @endfor
                    </div> </div> 
            </div> </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-4">
            
            <div class="lg:col-span-2 bg-white dark:bg-[#151515] rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-heading font-bold flex items-center gap-2 text-slate-900 dark:text-white transition-colors">
                        <i class="ph-fill ph-sparkle text-kinetic-primary text-lg"></i> Rekomendasi Slot Hari Ini
                    </h3>
                    <a href="#" class="text-xs font-bold text-teal-600 dark:text-kinetic-primary hover:underline transition-colors">Lihat Semua</a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 flex justify-between items-center group cursor-pointer hover:border-teal-400 dark:hover:border-kinetic-primary transition-colors">
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mb-1">Ruang Studio 01</p>
                            <p class="font-bold text-sm text-slate-900 dark:text-white mb-3 transition-colors">15:00 - 17:00</p>
                            <span class="px-2.5 py-1 bg-teal-100/50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary text-[10px] font-bold rounded uppercase tracking-wider">Tersedia</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white dark:bg-[#222] flex items-center justify-center border border-slate-200 dark:border-[#333] group-hover:bg-teal-50 dark:group-hover:bg-kinetic-primary/10 transition-colors">
                            <i class="ph ph-arrow-right text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 flex justify-between items-center group cursor-pointer hover:border-teal-400 dark:hover:border-kinetic-primary transition-colors">
                        <div>
                            <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mb-1">Auditorium B</p>
                            <p class="font-bold text-sm text-slate-900 dark:text-white mb-3 transition-colors">18:30 - 20:00</p>
                            <span class="px-2.5 py-1 bg-teal-100/50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary text-[10px] font-bold rounded uppercase tracking-wider">Tersedia</span>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-white dark:bg-[#222] flex items-center justify-center border border-slate-200 dark:border-[#333] group-hover:bg-teal-50 dark:group-hover:bg-kinetic-primary/10 transition-colors">
                            <i class="ph ph-arrow-right text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                        </div>
                    </div>
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
</x-app-layout>