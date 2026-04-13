<x-app-layout>
    <div class="bg-slate-50 dark:bg-[#0f0f0f] min-h-screen py-12 text-slate-800 dark:text-[#e5e5e5] font-sans transition-colors duration-300">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-start mb-8 gap-4 md:gap-0">
                <div>
                    <p class="text-teal-600 dark:text-[#2dd4bf] text-xs font-bold tracking-widest uppercase mb-2">Security & Maintenance</p>
                    <h1 class="text-4xl font-bold text-slate-900 dark:text-white leading-tight mb-3">Pemblokiran Jadwal<br>(Maintenance)</h1>
                    <p class="text-slate-500 dark:text-gray-400 text-sm max-w-xl">Kunci ruangan secara manual untuk keperluan perawatan atau acara internal unit tanpa melalui alur reguler.</p>
                </div>
                <div class="flex items-center gap-3 bg-red-50 dark:bg-[#1a1111] border border-red-200 dark:border-red-900/50 px-4 py-2 rounded-xl shadow-sm dark:shadow-none transition-colors">
                    <div class="bg-red-100 dark:bg-red-500/20 p-2 rounded-lg text-red-600 dark:text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-red-500 dark:text-gray-400 font-semibold uppercase tracking-wider">Sistem Lock</p>
                        <p class="text-red-900 dark:text-white text-sm font-semibold">Hard-Lock Active</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
                
                <div x-data="{ ruangan: 'auditorium' }" class="lg:col-span-3 bg-white dark:bg-[#161616] border border-slate-200 dark:border-gray-800 shadow-sm dark:shadow-none rounded-2xl p-6 transition-colors">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="text-teal-600 dark:text-[#2dd4bf]" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/><path d="m9 16 2 2 4-4"/></svg>
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Konfigurasi Blokir</h2>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-gray-400 tracking-wider uppercase mb-3">Pilih Ruangan</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            
                            <div @click="ruangan = 'auditorium'" 
                                 :class="ruangan === 'auditorium' ? 'border-teal-500 bg-teal-50/50 dark:border-[#2dd4bf] dark:bg-[#1a1a1a] opacity-100' : 'border-slate-200 bg-white dark:border-gray-800 dark:bg-[#1c1c1c] opacity-60 hover:opacity-100'"
                                 class="p-4 rounded-xl cursor-pointer relative transition duration-200 border">
                                
                                <div :class="ruangan === 'auditorium' ? 'border-teal-500 dark:border-[#2dd4bf]' : 'border-slate-300 dark:border-gray-600'" 
                                     class="absolute top-4 right-4 w-4 h-4 rounded-full border-2 flex items-center justify-center transition duration-200">
                                    <div x-show="ruangan === 'auditorium'" class="w-2 h-2 rounded-full bg-teal-500 dark:bg-[#2dd4bf]" style="display: none;"></div>
                                </div>
                                
                                <svg :class="ruangan === 'auditorium' ? 'text-teal-600 dark:text-[#2dd4bf]' : 'text-slate-400 dark:text-gray-400'" class="mb-3 transition duration-200" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                                <h3 class="text-slate-900 dark:text-white font-semibold text-sm">Auditorium Utama</h3>
                                <p class="text-slate-500 dark:text-gray-500 text-xs mt-1">Gedung A • Lt. 3</p>
                                
                                <input type="radio" name="ruangan" value="auditorium" x-model="ruangan" class="hidden">
                            </div>

                            <div @click="ruangan = 'rapat202'" 
                                 :class="ruangan === 'rapat202' ? 'border-teal-500 bg-teal-50/50 dark:border-[#2dd4bf] dark:bg-[#1a1a1a] opacity-100' : 'border-slate-200 bg-white dark:border-gray-800 dark:bg-[#1c1c1c] opacity-60 hover:opacity-100'"
                                 class="p-4 rounded-xl cursor-pointer relative transition duration-200 border">
                                
                                <div :class="ruangan === 'rapat202' ? 'border-teal-500 dark:border-[#2dd4bf]' : 'border-slate-300 dark:border-gray-600'" 
                                     class="absolute top-4 right-4 w-4 h-4 rounded-full border-2 flex items-center justify-center transition duration-200">
                                    <div x-show="ruangan === 'rapat202'" class="w-2 h-2 rounded-full bg-teal-500 dark:bg-[#2dd4bf]" style="display: none;"></div>
                                </div>
                                
                                <svg :class="ruangan === 'rapat202' ? 'text-teal-600 dark:text-[#2dd4bf]' : 'text-slate-400 dark:text-gray-400'" class="mb-3 transition duration-200" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                                <h3 class="text-slate-900 dark:text-white font-semibold text-sm">Ruang Rapat 202</h3>
                                <p class="text-slate-500 dark:text-gray-500 text-xs mt-1">Gedung B • Lt. 2</p>
                                
                                <input type="radio" name="ruangan" value="rapat202" x-model="ruangan" class="hidden">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-gray-400 tracking-wider uppercase mb-3">Mulai Dari</label>
                            <div class="bg-slate-50 dark:bg-[#1c1c1c] border border-slate-200 dark:border-transparent focus-within:border-teal-500 focus-within:ring-1 focus-within:ring-teal-500 dark:focus-within:border-gray-700 dark:focus-within:ring-0 rounded-xl px-4 py-3 flex items-center transition-shadow">
                                <input type="datetime-local" name="mulai_dari" class="bg-transparent border-none outline-none text-sm text-slate-900 dark:text-gray-300 w-full focus:ring-0 p-0 dark:[color-scheme:dark] cursor-pointer">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 dark:text-gray-400 tracking-wider uppercase mb-3">Hingga</label>
                            <div class="bg-slate-50 dark:bg-[#1c1c1c] border border-slate-200 dark:border-transparent focus-within:border-teal-500 focus-within:ring-1 focus-within:ring-teal-500 dark:focus-within:border-gray-700 dark:focus-within:ring-0 rounded-xl px-4 py-3 flex items-center transition-shadow">
                                <input type="datetime-local" name="hingga" class="bg-transparent border-none outline-none text-sm text-slate-900 dark:text-gray-300 w-full focus:ring-0 p-0 dark:[color-scheme:dark] cursor-pointer">
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-semibold text-slate-500 dark:text-gray-400 tracking-wider uppercase mb-3">Alasan Pemblokiran</label>
                        <textarea name="alasan" rows="3" placeholder="Contoh: Perbaikan AC Sentral atau Renovasi Plafon..." 
                            class="bg-slate-50 dark:bg-[#1c1c1c] border border-slate-200 dark:border-transparent focus:border-teal-500 dark:focus:border-[#2dd4bf] focus:ring-1 focus:ring-teal-500 dark:focus:ring-[#2dd4bf] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-gray-300 w-full outline-none resize-none placeholder-slate-400 dark:placeholder-gray-600 transition-shadow"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 dark:bg-[#2dd4bf] dark:hover:bg-[#20b29f] text-white dark:text-gray-900 font-bold py-3 rounded-xl flex justify-center items-center gap-2 transition duration-200 shadow-md shadow-teal-500/20 dark:shadow-none">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m4.9 4.9 14.2 14.2"/></svg>
                        Blokir Kalender
                    </button>
                    <p class="text-center text-[11px] text-slate-500 dark:text-gray-500 mt-3">*Tindakan ini akan membatalkan semua reservasi yang beririsan secara otomatis.</p>
                </div>

                <div class="lg:col-span-2 flex flex-col gap-5">
                    
                    <div class="bg-white dark:bg-[#161616] border border-slate-200 dark:border-gray-800 shadow-sm dark:shadow-none rounded-2xl p-6 relative overflow-hidden transition-colors">
                        <div class="flex justify-between items-start mb-6 relative z-10">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white">Status Ruangan</h3>
                                <p class="text-xs text-slate-500 dark:text-gray-500">Real-time occupancy info</p>
                            </div>
                            <span class="text-[10px] font-bold tracking-wider text-teal-700 bg-teal-100 dark:text-white dark:bg-white/10 px-2 py-1 rounded border border-teal-200 dark:border-transparent">TERSEDIA</span>
                        </div>

                        <div class="space-y-5 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="bg-slate-100 dark:bg-[#222] p-3 rounded-full text-slate-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold text-slate-500 dark:text-gray-500 tracking-wider uppercase">Kapasitas</p>
                                    <p class="text-slate-900 dark:text-white font-semibold text-sm">500 Peserta</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="bg-slate-100 dark:bg-[#222] p-3 rounded-full text-slate-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-semibold text-slate-500 dark:text-gray-500 tracking-wider uppercase">Fasilitas</p>
                                    <p class="text-slate-900 dark:text-white font-semibold text-sm">Full Sound, Projector, AC</p>
                                </div>
                            </div>
                        </div>
                        <svg class="absolute -bottom-4 -right-4 w-32 h-32 text-slate-100 dark:text-white/5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                    </div>

                    <div class="bg-white dark:bg-[#161616] border border-slate-200 dark:border-gray-800 shadow-sm dark:shadow-none rounded-2xl p-6 transition-colors">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-[15px] font-bold text-slate-900 dark:text-white">Daftar Pemblokiran Aktif</h3>
                            <a href="#" class="text-xs text-teal-600 dark:text-[#2dd4bf] hover:underline font-medium">Lihat Semua</a>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex items-center gap-4 relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 dark:bg-red-400 rounded-r-md"></div>
                                <div class="bg-slate-100 dark:bg-[#222] rounded-lg text-center p-2 min-w-[50px] ml-3 border border-slate-200 dark:border-transparent">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-400 font-bold uppercase leading-none mb-1">Okt</p>
                                    <p class="text-lg text-slate-900 dark:text-white font-bold leading-none">24</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Lab Komputer A</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-500">Instalasi Software Ujian</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 relative">
                                <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500 dark:bg-red-400 rounded-r-md"></div>
                                <div class="bg-slate-100 dark:bg-[#222] rounded-lg text-center p-2 min-w-[50px] ml-3 border border-slate-200 dark:border-transparent">
                                    <p class="text-[10px] text-slate-500 dark:text-gray-400 font-bold uppercase leading-none mb-1">Okt</p>
                                    <p class="text-lg text-slate-900 dark:text-white font-bold leading-none">26</p>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Ruang Teater</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-500">Maintenance Proyektor</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-teal-50 dark:bg-[#111f1c] border border-teal-200 dark:border-[#164e43] rounded-xl p-4 flex gap-3 items-start mt-auto transition-colors">
                        <svg class="text-teal-600 dark:text-[#2dd4bf] shrink-0 mt-0.5" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" x2="12" y1="8" y2="12"/><line x1="12" x2="12.01" y1="16" y2="16"/></svg>
                        <p class="text-xs text-slate-600 dark:text-gray-400 leading-relaxed">
                            Pemblokiran jadwal bersifat <span class="text-teal-700 dark:text-[#2dd4bf] font-bold">High Priority</span>. Admin Unit lain tidak dapat mengajukan peminjaman selama periode blokir aktif.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>