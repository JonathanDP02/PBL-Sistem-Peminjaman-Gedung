<x-app-layout title="Cari Ruangan">
    <div class="relative px-8 pt-4 pb-8 space-y-10 z-10 flex flex-col min-h-full">
        
        <div>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 dark:bg-kinetic-primary/10 border border-teal-200 dark:border-kinetic-primary/20 rounded-full text-[10px] font-bold text-teal-700 dark:text-kinetic-secondary tracking-widest uppercase mb-4 transition-colors">
                <span class="w-1.5 h-1.5 bg-kinetic-primary rounded-full"></span> Sistem Reservasi Terpadu
            </span>
            <h2 class="font-heading text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 transition-colors">
                Reservasi <span class="text-kinetic-primary">Tanpa Batas</span>
            </h2>
            <p class="text-slate-500 dark:text-gray-400 text-sm max-w-2xl leading-relaxed transition-colors">
                Wujudkan kegiatan akademik dan organisasi Anda dengan akses mudah ke seluruh ruang pertemuan dan auditorium universitas.
            </p>
        </div>

        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none rounded-3xl p-8 lg:p-10 transition-colors duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Pilih Ruangan</label>
                        <div class="relative">
                            <i class="ph ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                            <input type="text" placeholder="Cari nama ruangan atau nomor gedung..." class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Tanggal Penggunaan</label>
                            <div class="relative">
                                <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                <input type="date" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Waktu Pelaksanaan</label>
                            <div class="relative">
                                <i class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                <input type="time" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Alur Prosedur (SOP)</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button class="bg-teal-50 dark:bg-[#1A1A1A] border border-kinetic-primary rounded-xl p-5 text-left relative group transition-colors">
                                <i class="ph-fill ph-check-circle absolute top-4 right-4 text-kinetic-primary text-xl"></i>
                                <p class="font-bold text-slate-900 dark:text-white text-sm mb-1">SOP A</p>
                                <p class="text-[10px] text-slate-500 dark:text-gray-500">Kegiatan Internal Fakultas & Lab</p>
                            </button>
                            <button class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] hover:border-slate-400 dark:hover:border-gray-500 rounded-xl p-5 text-left transition-colors">
                                <p class="font-bold text-slate-600 dark:text-gray-300 text-sm mb-1">SOP B</p>
                                <p class="text-[10px] text-slate-500 dark:text-gray-500">Kegiatan Organisasi & Eksternal</p>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col h-full justify-between">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Lampiran Dokumen</label>
                        <div class="space-y-3">
                            <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-slate-400 dark:text-gray-400">
                                        <i class="ph ph-file-text text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Izin Dekanat</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-500">Format PDF, Maks 5MB</p>
                                    </div>
                                </div>
                                <i class="ph ph-cloud-arrow-up text-xl text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                            </div>
                            <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-slate-400 dark:text-gray-400">
                                        <i class="ph ph-clipboard-text text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Proposal Kegiatan</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-500">Wajib untuk SOP B</p>
                                    </div>
                                </div>
                                <i class="ph ph-cloud-arrow-up text-xl text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                            </div>
                            <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-slate-400 dark:text-gray-400">
                                        <i class="ph ph-identification-card text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">KTM Ketua Pelaksana</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-500">Scan berwarna (JPG/PNG)</p>
                                    </div>
                                </div>
                                <i class="ph ph-cloud-arrow-up text-xl text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button class="w-full bg-gradient-to-r from-kinetic-primary to-kinetic-secondary hover:from-teal-400 hover:to-cyan-400 text-slate-900 font-bold py-4 rounded-xl shadow-[0_0_20px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-1">
                            Pesan Sekarang
                        </button>
                        <p class="text-[10px] text-center text-slate-500 dark:text-gray-500 mt-4">*Proses verifikasi dokumen memakan waktu ±24 jam hari kerja.</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-4">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-500/10 text-kinetic-primary flex items-center justify-center shrink-0 border border-teal-100 dark:border-transparent">
                    <i class="ph-bold ph-shield-check text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Approval Cepat</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Sistem otomatis mendeteksi ketersediaan ruang secara real-time untuk mempercepat birokrasi.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-cyan-50 dark:bg-cyan-500/10 text-kinetic-tertiary flex items-center justify-center shrink-0 border border-cyan-100 dark:border-transparent">
                    <i class="ph-bold ph-git-branch text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Pelacakan Alur</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Pantau status pengajuan Anda dari tahap admin hingga persetujuan rektorat dalam satu dashboard.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-transparent">
                    <i class="ph-bold ph-chart-bar text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Log Digital</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Seluruh riwayat penggunaan dan lampiran tersimpan aman untuk kebutuhan laporan pertanggungjawaban.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 transition-colors duration-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-kinetic-primary rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-kinetic-primary tracking-widest uppercase">Live Insight</span>
                </div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">12 Ruangan Tersedia</h4>
                <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Gedung Fakultas Teknik memiliki slot terbanyak saat ini.</p>
            </div>
        </div>

    </div>
</x-app-layout>