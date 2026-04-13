<x-app-layout title="Organisasi Unit">
    <div class="relative px-8 pt-4 pb-8 space-y-6 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
            <div>
                <span class="px-2 py-1 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary border border-teal-200 dark:border-kinetic-primary/20 rounded text-[10px] font-bold uppercase tracking-wider mb-3 inline-block">
                    Master Data
                </span>
                <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Organisasi Unit</h2>
                <p class="text-sm text-slate-500 dark:text-gray-400 max-w-xl leading-relaxed">
                    Kelola struktur hierarki universitas mulai dari Rektorat, Fakultas, hingga Program Studi dalam satu tampilan terpadu.
                </p>
            </div>
            <button class="bg-kinetic-primary hover:bg-teal-400 text-slate-900 font-bold px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-[0_0_15px_rgba(20,184,166,0.3)] shrink-0">
                <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Unit Baru
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <div class="lg:col-span-8 bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 md:p-8 shadow-sm dark:shadow-none flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white">Struktur Hierarki</h3>
                    <div class="flex gap-3 text-slate-400 dark:text-gray-500">
                        <button class="hover:text-kinetic-primary transition"><i class="ph-bold ph-caret-up-down text-lg"></i></button>
                        <button class="hover:text-kinetic-primary transition"><i class="ph-bold ph-arrows-clockwise text-lg"></i></button>
                    </div>
                </div>

                <div class="space-y-4 relative ml-2">
                    
                    <div class="relative bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between border-l-4 border-l-kinetic-primary shadow-sm dark:shadow-none z-10">
                        <div class="flex items-center gap-4">
                            <i class="ph-fill ph-bank text-2xl text-kinetic-primary"></i>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Level 1: Pusat (Rektorat)</h4>
                                <p class="text-[10px] text-slate-500">Unit Utama Universitas • 12 Sub-unit</p>
                            </div>
                        </div>
                        <i class="ph-bold ph-caret-down text-slate-400 dark:text-gray-500"></i>
                    </div>

                    <div class="relative ml-8 pb-2">
                        <div class="absolute -left-6 top-0 bottom-4 w-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>

                        <div class="relative mb-4">
                            <div class="absolute -left-6 top-1/2 w-6 h-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>
                            
                            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between z-10 relative">
                                <div class="flex items-center gap-4">
                                    <i class="ph-fill ph-users-three text-xl text-cyan-500"></i>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Level 2: Fakultas Teknik (FT)</h4>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Dekanat • 5 Program Studi</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-[#222] text-slate-500 dark:text-gray-400 rounded-md text-[10px] font-bold">+5</span>
                            </div>

                            <div class="ml-10 mt-2 relative">
                                <div class="absolute -left-6 top-0 bottom-4 w-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>

                                <div class="relative flex items-center justify-between py-2.5 group">
                                    <div class="absolute -left-6 top-1/2 w-6 h-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>
                                    <div class="flex items-center gap-3 relative z-10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500 dark:bg-kinetic-primary group-hover:scale-150 transition-transform"></span>
                                        <h4 class="text-xs font-bold text-slate-700 dark:text-gray-300 group-hover:text-kinetic-primary transition-colors cursor-pointer">S1 Teknik Informatika</h4>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-wider">Prodi</span>
                                </div>
                                
                                <div class="relative flex items-center justify-between py-2.5 group">
                                    <div class="absolute -left-6 top-1/2 w-6 h-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>
                                    <div class="flex items-center gap-3 relative z-10">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500 dark:bg-kinetic-primary group-hover:scale-150 transition-transform"></span>
                                        <h4 class="text-xs font-bold text-slate-700 dark:text-gray-300 group-hover:text-kinetic-primary transition-colors cursor-pointer">S1 Teknik Sipil</h4>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-gray-600 uppercase tracking-wider">Prodi</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative mb-4">
                            <div class="absolute -left-6 top-1/2 w-6 h-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>
                            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between cursor-pointer hover:border-slate-400 dark:hover:border-gray-600 transition-colors z-10 relative">
                                <div class="flex items-center gap-4">
                                    <i class="ph-fill ph-money text-xl text-kinetic-primary"></i>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Level 2: Fakultas Ekonomi & Bisnis (FEB)</h4>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Dekanat • 3 Program Studi</p>
                                    </div>
                                </div>
                                <i class="ph-bold ph-caret-right text-slate-400 dark:text-gray-500"></i>
                            </div>
                        </div>

                        <div class="relative">
                            <div class="absolute -left-6 top-1/2 w-6 h-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>
                            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between cursor-pointer hover:border-slate-400 dark:hover:border-gray-600 transition-colors z-10 relative">
                                <div class="flex items-center gap-4">
                                    <i class="ph-fill ph-scales text-xl text-red-400"></i>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Level 2: Fakultas Hukum (FH)</h4>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">Dekanat • 2 Program Studi</p>
                                    </div>
                                </div>
                                <i class="ph-bold ph-caret-right text-slate-400 dark:text-gray-500"></i>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="lg:col-span-4 space-y-6">
                
                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 md:p-8 shadow-sm dark:shadow-none">
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-kinetic-primary shrink-0">
                            <i class="ph-bold ph-plus-square text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-slate-900 dark:text-white">Tambah Unit</h3>
                            <p class="text-[10px] text-slate-500 dark:text-gray-400 leading-tight mt-0.5">Konfigurasi entitas organisasi baru</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nama Unit</label>
                            <input type="text" placeholder="Contoh: Fakultas Kedokteran" class="w-full bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Kode Unit</label>
                            <input type="text" placeholder="Contoh: FK-01" class="w-full bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors">
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Parent Unit (Induk)</label>
                            <div class="relative">
                                <select class="w-full bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-4 pr-10 py-3.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none cursor-pointer">
                                    <option>Level 1: Pusat (Rektorat)</option>
                                    <option>Level 2: Fakultas Teknik (FT)</option>
                                    <option>Level 2: Fakultas Ekonomi & Bisnis (FEB)</option>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Tipe Unit</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button class="bg-teal-50 dark:bg-[#151515] border border-kinetic-primary rounded-xl py-3 flex flex-col items-center justify-center gap-1 transition-colors">
                                    <i class="ph-fill ph-buildings text-kinetic-primary text-xl"></i>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">Fakultas</span>
                                </button>
                                <button class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] hover:border-slate-400 dark:hover:border-gray-500 rounded-xl py-3 flex flex-col items-center justify-center gap-1 transition-colors">
                                    <i class="ph-fill ph-graduation-cap text-slate-400 dark:text-gray-500 text-xl"></i>
                                    <span class="text-xs font-bold text-slate-600 dark:text-white">Prodi</span>
                                </button>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button class="w-full bg-kinetic-primary hover:bg-teal-400 text-slate-900 font-bold py-3.5 rounded-xl transition-colors shadow-[0_0_15px_rgba(20,184,166,0.2)]">
                                Simpan Perubahan
                            </button>
                            <p class="text-[9px] text-center text-slate-500 dark:text-gray-500 mt-4 leading-relaxed px-4">
                                Pastikan data yang Anda masukkan telah sesuai dengan struktur SK Rektorat terbaru.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Live Sync Status</p>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Database Terhubung</h4>
                    </div>
                    <div class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-kinetic-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-kinetic-primary"></span>
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 flex flex-col shadow-sm dark:shadow-none transition-colors">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] flex items-center justify-center text-kinetic-primary mb-5">
                    <i class="ph-fill ph-users-three text-xl"></i>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">42</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Total Unit</p>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 flex flex-col shadow-sm dark:shadow-none transition-colors">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] flex items-center justify-center text-cyan-500 mb-5">
                    <i class="ph-fill ph-buildings text-xl"></i>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">8</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Fakultas</p>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 flex flex-col shadow-sm dark:shadow-none transition-colors">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] flex items-center justify-center text-blue-400 mb-5">
                    <i class="ph-fill ph-graduation-cap text-xl"></i>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">34</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Program Studi</p>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 flex flex-col shadow-sm dark:shadow-none transition-colors">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] flex items-center justify-center text-slate-400 dark:text-gray-300 mb-5">
                    <i class="ph-fill ph-stack text-xl"></i>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">3</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Level Kedalaman</p>
            </div>
        </div>

    </div>
</x-app-layout>