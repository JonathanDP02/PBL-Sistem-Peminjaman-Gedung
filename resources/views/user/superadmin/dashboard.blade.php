<x-app-layout>  
    <!-- Main Content -->
    <div class="relative px-8 pt-6 pb-8 space-y-6 flex flex-col min-h-full bg-slate-50 dark:bg-[#0A0A0A] transition-colors duration-300">
            
        <!-- Hero / Welcome Title -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-2">
            <div>
                <h2 class="font-heading text-5xl font-extrabold text-teal-600 dark:text-[#5EEAD4] tracking-tight transition-colors duration-300">Ekslusif</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-2 max-w-md transition-colors duration-300">Selamat datang kembali. Berikut adalah performa operasional seluruh ekosistem gedung hari ini.</p>
            </div>
            
            <button onclick="openModal()" class="bg-teal-600 dark:bg-[#5EEAD4] hover:bg-teal-700 dark:hover:bg-teal-400 text-white dark:text-teal-950 font-bold px-5 py-3 rounded-full transition flex items-center gap-2 shadow-sm font-heading">
                <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Ruangan
            </button>

            {{-- @include('user.superadmin.modal-tambah-ruang') --}}
        </div>

        <!-- Row 1: Real Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Card 1 -->
            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 flex flex-col justify-between overflow-hidden relative shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
                <h3 class="text-slate-400 dark:text-[#888] text-[10px] font-bold tracking-[0.2em] relative z-10 uppercase mb-4 transition-colors duration-300">TOTAL GEDUNG</h3>
                <div class="relative z-10 flex flex-col gap-6">
                    <div class="flex items-baseline gap-2 mt-2">
                        <span class="text-6xl font-black text-slate-800 dark:text-white tracking-tight transition-colors duration-300">24</span>
                        <span class="text-teal-600 dark:text-[#5EEAD4] text-xs font-bold flex items-center transition-colors duration-300"><i class="ph-bold ph-trend-up mr-1 text-teal-600 dark:text-[#5EEAD4]"></i>+2</span>
                    </div>
                    
                    <div class="w-full bg-slate-100 dark:bg-[#333] h-1.5 rounded-full overflow-hidden mt-4 transition-colors duration-300">
                        <div class="bg-teal-500 dark:bg-[#5EEAD4] h-full w-[70%] rounded-full shadow-[0_0_10px_rgba(20,184,166,0.3)] dark:shadow-[0_0_10px_#5EEAD4] transition-colors duration-300"></div>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 flex flex-col justify-between overflow-hidden relative shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
                <h3 class="text-slate-400 dark:text-[#888] text-[10px] font-bold tracking-[0.2em] uppercase mb-4 relative z-10 transition-colors duration-300">TOTAL UNIT RUANGAN</h3>
                <div class="relative z-10">
                    <div class="flex items-baseline gap-3 mt-2 mb-2">
                        <span class="text-6xl font-black text-slate-800 dark:text-white tracking-tight transition-colors duration-300">42</span>
                        <span class="text-slate-500 dark:text-[#888] text-xs transition-colors duration-300">Aktif Nasional</span>
                    </div>
                    
                    <div class="flex items-center gap-3 mt-8">
                        <div class="flex -space-x-2">
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1A1A1A] border border-white dark:border-[#333] flex items-center justify-center text-[8px] text-slate-600 dark:text-[#ddd] font-bold z-30 transition-colors duration-300">R1</div>
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1A1A1A] border border-white dark:border-[#333] flex items-center justify-center text-[8px] text-slate-600 dark:text-[#ddd] font-bold z-20 transition-colors duration-300">R2</div>
                            <div class="w-6 h-6 rounded-full bg-slate-100 dark:bg-[#1A1A1A] border border-white dark:border-[#333] flex items-center justify-center text-[8px] text-slate-600 dark:text-[#ddd] font-bold z-10 transition-colors duration-300">R3</div>
                        </div>
                        <span class="text-xs font-medium text-slate-500 dark:text-[#888] transition-colors duration-300">+39 lainnya</span>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="bg-gradient-to-br from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/10 rounded-[24px] p-6 flex flex-col justify-between relative border border-teal-100 dark:border-teal-900/30 transition-colors duration-300">
                <div class="absolute top-6 right-6 w-2 h-2 rounded-full bg-teal-500 dark:bg-[#5EEAD4] shadow-[0_0_8px_rgba(20,184,166,0.6)] dark:shadow-[0_0_8px_#5EEAD4] transition-colors duration-300"></div>
                <h3 class="text-teal-600 dark:text-[#5EEAD4] text-[10px] font-bold tracking-[0.2em] uppercase mb-4 transition-colors duration-300">BOOKING AKTIF GLOBAL</h3>
                <div class="relative">
                    <div class="flex items-baseline gap-2 relative mt-2">
                        <span class="text-7xl font-black text-slate-300/30 dark:text-white absolute -top-4 -left-2 opacity-80" style="text-shadow: 0 4px 20px rgba(0,0,0,0.05); -webkit-text-stroke: 1px currentColor;">156</span>
                        <span class="text-5xl font-black text-transparent bg-clip-text bg-white opacity-0">156</span>
                        <span class="text-teal-600 dark:text-[#5EEAD4] text-xs font-bold z-10 absolute bottom-1 left-24 transition-colors duration-300">Real-time</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-400 text-xs mt-6 max-w-[240px] relative z-10 leading-relaxed font-medium transition-colors duration-300">
                        Peningkatan <span class="font-bold text-slate-800 dark:text-slate-200">12%</span> penggunaan ruang kolaborasi di seluruh cabang.
                    </p>
                </div>
            </div>
        </div>

        <!-- Row 2: Chart & Locations Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left: Tren Booking Global (2/3) -->
            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent lg:col-span-2 flex flex-col transition-colors duration-300">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading transition-colors duration-300">Tren Booking Global</h3>
                        <p class="text-slate-500 dark:text-[#888] text-xs mt-1 transition-colors duration-300">Statistik penggunaan 30 hari terakhir</p>
                    </div>
                    <div class="flex bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#222] rounded-full p-1 transition-colors duration-300">
                        <button class="px-4 py-1.5 rounded-full text-xs font-medium text-slate-500 dark:text-[#888] hover:text-slate-800 dark:hover:text-white transition">7 Hari</button>
                        <button class="px-4 py-1.5 rounded-full text-xs font-bold text-teal-900 dark:text-teal-950 bg-teal-200 dark:bg-[#5EEAD4] shadow-sm transition">30 Hari</button>
                    </div>
                </div>
                
                <div class="flex-grow flex flex-col justify-end pt-4">
                    <div class="h-40 flex items-end justify-between gap-2 md:gap-3 lg:gap-4 px-2">
                        <!-- Simulated Chart Bars -->
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[40%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[50%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[65%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[45%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[80%] transition-colors duration-300"></div>
                        
                        <div class="w-full bg-teal-400 dark:bg-[#5EEAD4] rounded-t-md h-[100%] transition-colors duration-300 relative">
                             <!-- active bright bar -->
                        </div>
                        
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[70%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[55%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[40%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[60%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[50%] transition-colors duration-300"></div>
                        <div class="w-full bg-slate-100 dark:bg-[#1A2624] hover:bg-teal-300 dark:hover:bg-[#5EEAD4] rounded-t-md h-[90%] transition-colors duration-300"></div>
                    </div>
                    <div class="flex justify-between mt-4 px-2 text-[9px] font-bold text-slate-400 dark:text-[#666] tracking-[0.15em] uppercase transition-colors duration-300">
                        <span>MINGGU 1</span>
                        <span>MINGGU 2</span>
                        <span>MINGGU 3</span>
                        <span>MINGGU 4</span>
                    </div>
                </div>
            </div>

            <!-- Right: Distribusi Lokasi (1/3) -->
            <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent flex flex-col justify-between transition-colors duration-300">
                <div>
                    <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading mb-8 transition-colors duration-300">Distribusi Lokasi</h3>
                    <div class="space-y-6">
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span class="text-slate-700 dark:text-white transition-colors duration-300">Auditorium TI</span>
                                <span class="text-teal-600 dark:text-[#5EEAD4] transition-colors duration-300">45%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-[#222] h-1.5 rounded-full overflow-hidden transition-colors duration-300">
                                <div class="bg-teal-500 dark:bg-[#5EEAD4] h-full w-[45%] rounded-full shadow-sm dark:shadow-[0_0_8px_#5EEAD4] transition-colors duration-300"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span class="text-slate-700 dark:text-white transition-colors duration-300">Graha Polinema</span>
                                <span class="text-teal-600 dark:text-[#5EEAD4] transition-colors duration-300">28%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-[#222] h-1.5 rounded-full overflow-hidden transition-colors duration-300">
                                <div class="bg-teal-800 dark:bg-[#2B5047] h-full w-[28%] rounded-full transition-colors duration-300"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span class="text-slate-700 dark:text-white transition-colors duration-300">Rumah Anuuu</span>
                                <span class="text-teal-600 dark:text-[#5EEAD4] transition-colors duration-300">15%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-[#222] h-1.5 rounded-full overflow-hidden transition-colors duration-300">
                                <div class="bg-teal-800 dark:bg-[#2B5047] h-full w-[15%] rounded-full transition-colors duration-300"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between text-xs font-bold mb-2">
                                <span class="text-slate-700 dark:text-white transition-colors duration-300">Ada dehh</span>
                                <span class="text-teal-600 dark:text-[#5EEAD4] transition-colors duration-300">12%</span>
                            </div>
                            <div class="w-full bg-slate-100 dark:bg-[#222] h-1.5 rounded-full overflow-hidden transition-colors duration-300">
                                <div class="bg-teal-500 dark:bg-[#5EEAD4] h-full w-[12%] rounded-full shadow-sm dark:shadow-[0_0_8px_#5EEAD4] transition-colors duration-300"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="w-full py-3 mt-8 rounded-xl bg-slate-50 dark:bg-[#1A1A1A] hover:bg-slate-100 dark:hover:bg-[#222] border border-slate-200 dark:border-[#222] text-slate-600 dark:text-[#ccc] hover:text-slate-900 dark:hover:text-white text-xs font-bold transition">
                    Lihat Laporan Regional
                </button>
            </div>
        </div>
        
        <!-- Row 3: Aktivitas Terbaru -->
        <div class="bg-white dark:bg-[#111111] rounded-[24px] p-6 shadow-sm border border-slate-200 dark:border-transparent transition-colors duration-300">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-slate-800 dark:text-white text-lg font-bold font-heading transition-colors duration-300">Aktivitas Terbaru</h3>
                <a href="#" class="text-xs font-bold text-teal-600 dark:text-[#5EEAD4] hover:text-teal-700 dark:hover:text-[#4fd1c5] transition-colors duration-300">Semua Log</a>
            </div>
            
            <div class="flex flex-col">
                <!-- Item 1 -->
                <div class="flex items-center justify-between py-4 border-b border-slate-100 dark:border-[#222] transition-colors duration-300">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-[#082a20] border border-teal-100 dark:border-[#1a4a3a] text-teal-600 dark:text-[#5EEAD4] flex items-center justify-center shrink-0 transition-colors duration-300">
                            <i class="ph-fill ph-buildings text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-bold text-sm transition-colors duration-300">Pendaftaran Gedung Baru</h4>
                            <p class="text-slate-500 dark:text-[#888] text-xs mt-0.5 transition-colors duration-300">Neo Soho Capital, Jakarta Barat • 2 menit yang lalu</p>
                        </div>
                    </div>
                    <span class="text-[9px] font-bold text-teal-700 dark:text-[#10B981] bg-teal-50 dark:bg-[#052E16] border border-teal-200 dark:border-[#064E3B] px-3 py-1 rounded-full uppercase tracking-wider transition-colors duration-300">Menunggu Approval</span>
                </div>
                
                <!-- Item 2 -->
                <div class="flex items-center justify-between py-4 border-b border-slate-100 dark:border-[#222] group cursor-pointer hover:bg-slate-50 dark:hover:bg-[#151515] -mx-4 px-4 rounded-xl transition">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-sky-50 dark:bg-[#1a2d3d] border border-sky-100 dark:border-[#274059] text-sky-500 dark:text-[#38bdf8] flex items-center justify-center shrink-0 transition-colors duration-300">
                            <i class="ph-bold ph-chart-bar text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-bold text-sm transition-colors duration-300">Laporan Bulanan Diunduh</h4>
                            <p class="text-slate-500 dark:text-[#888] text-xs mt-0.5 transition-colors duration-300">Oleh: Admin Surabaya (Andini) • 45 menit yang lalu</p>
                        </div>
                    </div>
                    <i class="ph-bold ph-caret-right text-slate-400 dark:text-[#555] group-hover:text-slate-800 dark:group-hover:text-white transition-colors duration-300"></i>
                </div>
                
                <!-- Item 3 -->
                <div class="flex items-center justify-between py-4">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-[#3d1616] border border-red-100 dark:border-[#592222] text-red-500 dark:text-[#f87171] flex items-center justify-center shrink-0 transition-colors duration-300">
                            <i class="ph-bold ph-warning text-lg"></i>
                        </div>
                        <div>
                            <h4 class="text-slate-800 dark:text-white font-bold text-sm transition-colors duration-300">Gagal Sinkronisasi Node Fasilitas</h4>
                            <p class="text-slate-500 dark:text-[#888] text-xs mt-0.5 transition-colors duration-300">Hub Utama Medan Technopark • 3 jam yang lalu</p>
                        </div>
                    </div>
                    <span class="text-[9px] font-bold text-red-600 dark:text-[#F87171] bg-red-50 dark:bg-[#450A0A] border border-red-200 dark:border-[#7F1D1D] px-3 py-1 rounded-full uppercase tracking-wider transition-colors duration-300">Penting</span>
                </div>
            </div>
        </div>
            
        <!-- Footer -->
        <footer class="mt-auto pt-8 pb-4 text-center">
            <p class="text-[9px] font-bold tracking-[0.2em] text-slate-400 dark:text-[#bbb] uppercase transition-colors duration-300">© 2026 SPACE.IN INFRASTRUCTURE ECOSYSTEM • V2.4.0 HIGH-PULSE EDITION</p>
        </footer>
    </div>
</x-app-layout>