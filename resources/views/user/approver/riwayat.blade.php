<x-app-layout title="Riwayat Persetujuan">
    <div class="relative px-8 pt-4 pb-8 space-y-6 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-8 shadow-sm dark:shadow-none transition-colors">
            <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2">Riwayat Persetujuan</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400">
                Audit trail aksi yang telah Anda ambil dalam sistem reservasi.
            </p>
        </div>

        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl shadow-sm dark:shadow-none transition-colors px-6 py-6 space-y-6">
            
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                
                <div class="flex flex-wrap items-center gap-4">
                    <div class="relative">
                        <button onclick="toggleDateDropdown()" id="btnDatePicker" class="flex items-center gap-2 px-4 py-2.5 bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] hover:border-teal-400 dark:hover:border-kinetic-primary rounded-xl text-sm font-bold text-slate-700 dark:text-gray-300 transition-colors">
                            <i class="ph ph-calendar-blank text-teal-600 dark:text-kinetic-primary text-lg"></i>
                            <span>Pilih Tanggal</span>
                            <i class="ph-bold ph-caret-down text-slate-400 ml-2"></i>
                        </button>

                        <div id="dateDropdownMenu" class="hidden absolute left-0 top-full mt-2 w-48 bg-white dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl shadow-xl z-50 overflow-hidden">
                            <ul class="py-2 text-sm text-slate-600 dark:text-gray-300 font-medium">
                                <li><a href="#" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-[#222] hover:text-kinetic-primary transition">Hari Ini</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-[#222] hover:text-kinetic-primary transition">7 Hari Terakhir</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-[#222] hover:text-kinetic-primary transition">Bulan Ini</a></li>
                                <li class="border-t border-slate-100 dark:border-[#2A2A2A] mt-1 pt-1"><a href="#" class="block px-4 py-2 hover:bg-slate-100 dark:hover:bg-[#222] text-slate-400 transition">Kustom (Pilih Range)...</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="flex items-center bg-slate-100 dark:bg-[#1A1A1A] rounded-xl p-1.5 border border-slate-200 dark:border-[#2A2A2A]">
                        <button onclick="switchFilterTab(this)" class="filter-tab px-5 py-1.5 rounded-lg text-sm font-bold transition-all bg-white dark:bg-[#2A2A2A] text-teal-600 dark:text-kinetic-primary shadow-sm">
                            Semua
                        </button>
                        <button onclick="switchFilterTab(this)" class="filter-tab px-5 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-[#222]">
                            Disetujui
                        </button>
                        <button onclick="switchFilterTab(this)" class="filter-tab px-5 py-1.5 rounded-lg text-sm font-medium transition-all text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-[#222]">
                            Ditolak
                        </button>
                    </div>
                </div>

                <button class="flex items-center gap-2 text-xs font-bold text-slate-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white uppercase tracking-widest transition-colors">
                    <i class="ph-bold ph-sort-descending text-lg"></i>
                    Urutkan: Terbaru
                </button>

            </div>

            <div class="overflow-x-auto custom-scrollbar pt-2">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-[#2A2A2A]">
                            <th class="px-2 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Nama Event</th>
                            <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Ruangan</th>
                            <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Tanggal Aksi</th>
                            <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Keputusan</th>
                            <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Catatan</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1E1E1E]">
                        
                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group">
                            <td class="px-2 py-5">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Workshop UI/UX Design</h4>
                                <p class="text-[10px] text-slate-500 dark:text-gray-500">Oleh: Rizky Ramadhan</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-kinetic-primary shadow-[0_0_5px_rgba(20,184,166,0.5)]"></span>
                                    <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Innovation Hub</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-medium text-slate-700 dark:text-gray-300">14 Okt 2026,<br>09:30</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary border border-teal-200 dark:border-kinetic-primary/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    <i class="ph-bold ph-check-circle text-sm"></i> Disetujui
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm text-slate-500 dark:text-gray-400 italic line-clamp-1 group-hover:line-clamp-none transition-all max-w-[200px]">"Sesuai dengan protokol dan perizinan sudah lengkap."</p>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group">
                            <td class="px-2 py-5">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Rapat Senat Akademik</h4>
                                <p class="text-[10px] text-slate-500 dark:text-gray-500">Oleh: Dr. Sarah Wijaya</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-kinetic-primary shadow-[0_0_5px_rgba(20,184,166,0.5)]"></span>
                                    <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Auditorium Utama</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-medium text-slate-700 dark:text-gray-300">13 Okt 2026,<br>15:15</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary border border-teal-200 dark:border-kinetic-primary/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    <i class="ph-bold ph-check-circle text-sm"></i> Disetujui
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm text-slate-500 dark:text-gray-400 italic line-clamp-1 group-hover:line-clamp-none transition-all max-w-[200px]">"Keperluan mendesak untuk evaluasi semester."</p>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group">
                            <td class="px-2 py-5">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Latihan Band Mahasiswa</h4>
                                <p class="text-[10px] text-slate-500 dark:text-gray-500">Oleh: UKM Musik</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 shadow-[0_0_5px_rgba(239,68,68,0.5)]"></span>
                                    <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Studio 02</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-medium text-slate-700 dark:text-gray-300">12 Okt 2026,<br>11:20</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 dark:bg-red-500/10 text-red-500 border border-red-200 dark:border-red-500/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    <i class="ph-bold ph-x-circle text-sm"></i> Ditolak
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm text-slate-500 dark:text-gray-400 italic line-clamp-1 group-hover:line-clamp-none transition-all max-w-[200px]">"Ruangan sedang dalam perbaikan akustik, harap pilih jadwal lain."</p>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group">
                            <td class="px-2 py-5">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Seminar Karir & Startup</h4>
                                <p class="text-[10px] text-slate-500 dark:text-gray-500">Oleh: Career Center</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-kinetic-primary shadow-[0_0_5px_rgba(20,184,166,0.5)]"></span>
                                    <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Theater Room</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm font-medium text-slate-700 dark:text-gray-300">10 Okt 2026,<br>16:45</p>
                            </td>
                            <td class="px-6 py-5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary border border-teal-200 dark:border-kinetic-primary/20 rounded-full text-[10px] font-bold uppercase tracking-wider">
                                    <i class="ph-bold ph-check-circle text-sm"></i> Disetujui
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <p class="text-sm text-slate-500 dark:text-gray-400 italic line-clamp-1 group-hover:line-clamp-none transition-all max-w-[200px]">"Sudah dikoordinasikan dengan tim teknisi untuk proyektor."</p>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 dark:border-[#2A2A2A] pt-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <p class="text-xs text-slate-500 dark:text-gray-400">
                    Menampilkan <span class="font-bold text-slate-900 dark:text-white">1-4</span> dari <span class="font-bold text-slate-900 dark:text-white">48</span> aktivitas
                </p>
                <div class="flex items-center gap-1 text-sm">
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-[#222] transition-colors">
                        <i class="ph-bold ph-caret-left"></i>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-kinetic-primary text-slate-900 font-bold transition-colors">
                        1
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-[#222] font-medium transition-colors">
                        2
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-[#222] font-medium transition-colors">
                        3
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-[#222] transition-colors">
                        <i class="ph-bold ph-caret-right"></i>
                    </button>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary flex items-center justify-center border border-teal-100 dark:border-kinetic-primary/20">
                        <i class="ph-bold ph-check-square-offset text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-teal-600 dark:text-kinetic-primary uppercase tracking-widest">+12% BULAN INI</span>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">124</h2>
                <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">Total Disetujui</p>
            </div>

            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center border border-red-100 dark:border-red-500/20">
                        <i class="ph-bold ph-prohibit text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">STABIL</span>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">12</h2>
                <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">Total Ditolak</p>
            </div>

            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-500 flex items-center justify-center border border-blue-100 dark:border-blue-500/20">
                        <i class="ph-bold ph-gauge text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">SANGAT CEPAT</span>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">4.2 Jam</h2>
                <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">Rata-rata Waktu Aksi</p>
            </div>
        </div>

    </div>

    <script>
        // 1. Script untuk Dropdown "Pilih Tanggal"
        function toggleDateDropdown() {
            const dropdown = document.getElementById('dateDropdownMenu');
            dropdown.classList.toggle('hidden');
        }

        // Tutup dropdown kalau user klik di luar area dropdown
        window.addEventListener('click', function(e) {
            const btn = document.getElementById('btnDatePicker');
            const dropdown = document.getElementById('dateDropdownMenu');
            if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // 2. Script untuk Animasi Filter Tabs (Semua, Disetujui, Ditolak)
        function switchFilterTab(clickedTab) {
            const allTabs = document.querySelectorAll('.filter-tab');
            
            // Definisikan class untuk State Aktif dan Non-Aktif
            const activeClasses = ['bg-white', 'dark:bg-[#2A2A2A]', 'text-teal-600', 'dark:text-kinetic-primary', 'shadow-sm', 'font-bold'];
            const inactiveClasses = ['text-slate-500', 'dark:text-slate-400', 'hover:text-slate-900', 'dark:hover:text-white', 'hover:bg-slate-50', 'dark:hover:bg-[#222]', 'font-medium'];

            // Reset semua tab menjadi non-aktif
            allTabs.forEach(tab => {
                tab.classList.remove(...activeClasses);
                tab.classList.add(...inactiveClasses);
            });

            // Jadikan tab yang diklik menjadi aktif
            clickedTab.classList.remove(...inactiveClasses);
            clickedTab.classList.add(...activeClasses);
        }
    </script>
</x-app-layout>