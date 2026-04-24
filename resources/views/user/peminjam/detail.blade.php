<x-app-layout title="Lacak Pesanan">
    <div class="relative px-8 pt-4 pb-8 space-y-8 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-2">
                    <a href="#" class="hover:text-kinetic-primary transition">Riwayat</a>
                    <i class="ph ph-caret-right text-[10px]"></i>
                    <span class="text-slate-500">Detail Pesanan</span>
                </nav>
                <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Lacak Pesanan</h2>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-mono font-bold text-slate-400">#SP-20261024-001</span>
                    <span class="px-2 py-0.5 bg-red-500/10 text-red-500 border border-red-500/20 rounded text-[10px] font-bold uppercase tracking-wider">Butuh Tindakan</span>
                </div>
            </div>
            <div class="flex gap-3">
                <button class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-[#222] transition shadow-sm dark:shadow-none">
                    <i class="ph ph-printer text-lg"></i> Cetak Bukti
                </button>
                <button class="flex items-center gap-2 px-5 py-2.5 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary border border-teal-200 dark:border-kinetic-primary/20 rounded-xl text-sm font-bold hover:bg-teal-100 dark:hover:bg-kinetic-primary/20 transition">
                    <i class="ph ph-headset text-lg"></i> Bantuan CS
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-5 space-y-6">
                <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-5 flex gap-4">
                    <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/20">
                        <i class="ph-fill ph-warning text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-red-500 mb-1">Butuh Tindakan Segera</h4>
                        <p class="text-[11px] text-red-400 leading-relaxed">Dokumen pengajuan Anda dikembalikan oleh Kaprodi karena format proposal belum sesuai standar terbaru.</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-8 shadow-sm dark:shadow-none transition-colors">
                    <h3 class="font-heading font-bold text-slate-900 dark:text-white mb-8">Status Progress</h3>
                    
                    <div class="relative space-y-10">
                        <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-slate-100 dark:bg-[#2A2A2A]"></div>

                        <div class="relative flex gap-6">
                            <div class="w-8 h-8 rounded-full bg-kinetic-primary text-slate-900 flex items-center justify-center z-10 shadow-lg shadow-kinetic-primary/20">
                                <i class="ph-bold ph-check text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-teal-600 dark:text-kinetic-primary mb-1 uppercase tracking-widest">24 OKT 2026 • 09:12</p>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Pesanan Diajukan (Submitted)</h4>
                                <p class="text-xs text-slate-500 dark:text-gray-500 leading-relaxed">Peminjaman Ruang Lab Komputer A</p>
                            </div>
                        </div>

                        <div class="relative flex gap-6">
                            <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center z-10 shadow-lg shadow-red-500/20">
                                <i class="ph-bold ph-x text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-red-500 mb-1 uppercase tracking-widest">25 OKT 2026 • 14:00</p>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Revisi Kaprodi</h4>
                                <p class="text-xs text-slate-500 dark:text-gray-500 leading-relaxed">Menunggu perbaikan dokumen dari pemohon</p>
                            </div>
                        </div>

                        <div class="relative flex gap-6">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#151515] border-4 border-white dark:border-[#151515] ring-2 ring-slate-200 dark:ring-[#2A2A2A] flex items-center justify-center z-10">
                                <div class="w-2 h-2 rounded-full bg-slate-400 dark:bg-gray-600"></div>
                            </div>
                            <div class="opacity-40">
                                <p class="text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-widest">PENDING</p>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Persetujuan Dekan</h4>
                                <p class="text-xs text-slate-500 dark:text-gray-500 leading-relaxed">Verifikasi akhir dan penerbitan izin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-6">
                
                <div class="relative h-64 rounded-3xl overflow-hidden group shadow-xl border border-slate-200 dark:border-[#2A2A2A]">
                    <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1200" alt="Room" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-8 left-8">
                        <span class="px-2 py-1 bg-kinetic-primary/20 backdrop-blur-md text-kinetic-primary border border-kinetic-primary/30 rounded text-[10px] font-bold uppercase mb-3 inline-block">Lab Komputer A</span>
                        <h3 class="text-3xl font-heading font-extrabold text-white">Gedung Science Center</h3>
                        <p class="text-sm text-gray-300">Lantai 3 • Ruang 304</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 transition-colors shadow-sm dark:shadow-none">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-4">Waktu Peminjaman</p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <i class="ph ph-calendar text-kinetic-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">Kamis, 2 Nov 2026</p>
                                    <p class="text-[10px] text-slate-500">Tanggal Kegiatan</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="ph ph-clock text-kinetic-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">08:00 - 12:00 WIB</p>
                                    <p class="text-[10px] text-slate-500">Durasi: 4 Jam</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 transition-colors shadow-sm dark:shadow-none">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-4">Keperluan & Kapasitas</p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <i class="ph ph-users text-kinetic-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">45 Orang</p>
                                    <p class="text-[10px] text-slate-500">Estimasi Peserta</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="ph ph-article text-kinetic-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">Workshop UI/UX Design</p>
                                    <p class="text-[10px] text-slate-500">Nama Kegiatan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-8 flex items-center gap-8 transition-colors">
                    <div class="w-32 h-32 bg-slate-200 dark:bg-[#222] rounded-2xl flex items-center justify-center border border-slate-300 dark:border-[#333] shrink-0 relative overflow-hidden group">
                        <i class="ph-fill ph-lock-key text-4xl text-slate-400 dark:text-gray-600 transition-transform group-hover:scale-110"></i>
                        <div class="absolute inset-0 bg-black/5 dark:bg-black/20"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white">E-Sertifikat & Izin Digital</h3>
                            <div class="flex gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-gray-600"></span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed mb-6">
                            Dokumen digital dan QR Code akses pintu akan aktif secara otomatis setelah status pesanan berubah menjadi <span class="text-teal-600 dark:text-kinetic-primary font-bold">"Disetujui"</span>.
                        </p>
                        <button disabled class="flex items-center gap-2 px-6 py-2.5 bg-slate-200 dark:bg-[#111] text-slate-400 dark:text-gray-600 rounded-xl text-xs font-bold border border-slate-300 dark:border-[#2A2A2A] cursor-not-allowed transition-colors">
                            <i class="ph ph-download-simple"></i> Unduh PDF Izin
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="fixed bottom-8 right-8 z-30">
            <div class="bg-slate-900 dark:bg-[#2A2A2A] border border-slate-800 dark:border-[#3A3A3A] text-white px-4 py-2 rounded-full shadow-2xl flex items-center gap-3 animate-bounce">
                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Menunggu Tindakan</span>
            </div>
        </div>

    </div>
</x-app-layout>