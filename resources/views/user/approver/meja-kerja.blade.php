<x-app-layout title="Meja Kerja">
    <div class="relative px-8 pt-6 pb-32 space-y-10 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h1 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-2">Meja Kerja</h1>
                <p class="text-sm text-slate-500 dark:text-gray-400">
                    Anda memiliki <span class="text-teal-600 dark:text-kinetic-primary font-bold">4 pengajuan</span> yang butuh tinjauan segera.
                </p>
            </div>
            
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative w-full md:w-72">
                    <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" placeholder="Cari pemohon atau ruangan..." 
                        class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-teal-500 focus:border-teal-500 dark:focus:ring-kinetic-primary dark:focus:border-kinetic-primary transition-colors placeholder:text-slate-400 dark:placeholder:text-gray-600">
                </div>
                <button class="w-11 h-11 shrink-0 flex items-center justify-center bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-50 dark:hover:bg-[#1A1A1A] rounded-xl text-slate-600 dark:text-gray-400 transition-colors relative">
                    <i class="ph ph-bell text-xl"></i>
                    <span class="absolute top-3 right-3 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-[#151515]"></span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-2 bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-sm dark:shadow-none transition-colors">
                <div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-2">ESTIMASI WAKTU</p>
                    <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-2">~12 Menit</h2>
                    <p class="text-sm text-slate-500 dark:text-gray-400">Rata-rata penyelesaian per-berkas.</p>
                </div>
                
                <div class="flex items-center">
                    <img src="https://ui-avatars.com/api/?name=Dr+Sari&background=0D8ABC&color=fff" class="w-12 h-12 rounded-full border-4 border-white dark:border-[#151515] z-30 object-cover shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Pak+Budi&background=F59E0B&color=fff" class="w-12 h-12 rounded-full border-4 border-white dark:border-[#151515] -ml-4 z-20 object-cover shadow-sm">
                    <img src="https://ui-avatars.com/api/?name=Bu+Ratna&background=10B981&color=fff" class="w-12 h-12 rounded-full border-4 border-white dark:border-[#151515] -ml-4 z-10 object-cover shadow-sm">
                    <div class="w-12 h-12 rounded-full border-4 border-white dark:border-[#151515] -ml-4 z-0 bg-slate-100 dark:bg-[#2A2A2A] flex items-center justify-center text-xs font-bold text-slate-600 dark:text-white shadow-sm">+1</div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-[#4ade80] to-[#2dd4bf] rounded-3xl p-6 md:p-8 text-slate-900 flex flex-col justify-center cursor-pointer hover:scale-[1.02] hover:shadow-[0_10px_30px_rgba(45,212,191,0.3)] transition-all relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full blur-3xl -mr-10 -mt-10 group-hover:bg-white/30 transition-all"></div>
                
                <i class="ph-bold ph-lightning text-3xl mb-3"></i>
                <h3 class="font-heading text-2xl font-extrabold mb-1">SatSet Mode</h3>
                <p class="text-sm font-medium opacity-90">Fokus pada pengajuan prioritas.</p>
            </div>

        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-[#2A2A2A]">
                        <th class="px-4 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Pemohon</th>
                        <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Ruangan</th>
                        <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Jadwal</th>
                        <th class="px-4 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-slate-100 dark:divide-[#1E1E1E]">
                    
                    <tr class="hover:bg-slate-50 dark:hover:bg-[#151515] transition-colors rounded-2xl group">
                        <td class="px-4 py-5">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name=Andika+Pratama&background=475569&color=fff" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Andika Pratama</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-500 mt-0.5">BEM Universitas</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-kinetic-primary shadow-[0_0_5px_rgba(20,184,166,0.5)]"></span>
                                <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Auditorium Utama</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">15 Okt 2023</p>
                            <p class="text-xs text-slate-500 dark:text-gray-500 mt-0.5">08:00 - 17:00</p>
                        </td>
                        <td class="px-4 py-5 text-right">
                            <button onclick="openReviewModal()" class="px-5 py-2.5 rounded-xl bg-transparent border border-slate-300 dark:border-[#2A2A2A] hover:bg-slate-100 dark:hover:bg-[#2A2A2A] text-slate-700 dark:text-white text-xs font-bold transition-colors">
                                Review Pengajuan
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50 dark:hover:bg-[#151515] transition-colors rounded-2xl group">
                        <td class="px-4 py-5">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name=Siti+Rahmawati&background=0284c7&color=fff" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Siti Rahmawati</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-500 mt-0.5">Dosen Informatika</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-kinetic-primary shadow-[0_0_5px_rgba(20,184,166,0.5)]"></span>
                                <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Lab Riset AI</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">16 Okt 2023</p>
                            <p class="text-xs text-slate-500 dark:text-gray-500 mt-0.5">10:00 - 12:00</p>
                        </td>
                        <td class="px-4 py-5 text-right">
                            <button onclick="openReviewModal()" class="px-5 py-2.5 rounded-xl bg-transparent border border-slate-300 dark:border-[#2A2A2A] hover:bg-slate-100 dark:hover:bg-[#2A2A2A] text-slate-700 dark:text-white text-xs font-bold transition-colors">
                                Review Pengajuan
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50 dark:hover:bg-[#151515] transition-colors rounded-2xl group">
                        <td class="px-4 py-5">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name=Raka+Danuarta&background=0f172a&color=fff" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Raka Danuarta</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-500 mt-0.5">Hima Manajemen</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 shadow-[0_0_5px_rgba(239,68,68,0.5)]"></span>
                                <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Ruang Rapat Senat</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">18 Okt 2023</p>
                            <p class="text-xs text-slate-500 dark:text-gray-500 mt-0.5">13:00 - 15:30</p>
                        </td>
                        <td class="px-4 py-5 text-right">
                            <button onclick="openReviewModal()" class="px-5 py-2.5 rounded-xl bg-transparent border border-slate-300 dark:border-[#2A2A2A] hover:bg-slate-100 dark:hover:bg-[#2A2A2A] text-slate-700 dark:text-white text-xs font-bold transition-colors">
                                Review Pengajuan
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-slate-50 dark:hover:bg-[#151515] transition-colors rounded-2xl group">
                        <td class="px-4 py-5">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=94a3b8&color=fff" class="w-10 h-10 rounded-full object-cover">
                                <div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Budi Santoso</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-500 mt-0.5">Admin Fakultas</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-kinetic-primary shadow-[0_0_5px_rgba(20,184,166,0.5)]"></span>
                                <span class="text-sm font-bold text-slate-700 dark:text-gray-300">Amphitheater B</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <p class="text-sm font-bold text-slate-900 dark:text-white">20 Okt 2023</p>
                            <p class="text-xs text-slate-500 dark:text-gray-500 mt-0.5">09:00 - 12:00</p>
                        </td>
                        <td class="px-4 py-5 text-right">
                            <button onclick="openReviewModal()" class="px-5 py-2.5 rounded-xl bg-transparent border border-slate-300 dark:border-[#2A2A2A] hover:bg-slate-100 dark:hover:bg-[#2A2A2A] text-slate-700 dark:text-white text-xs font-bold transition-colors">
                                Review Pengajuan
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div id="summaryCard" class="fixed bottom-8 right-8 w-80 bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 shadow-[0_20px_40px_rgba(0,0,0,0.2)] dark:shadow-[0_20px_40px_rgba(0,0,0,0.5)] z-50 transition-all duration-300">
            
            <div class="flex justify-between items-start mb-6">
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Ringkasan Hari Ini</h4>
                    <i class="ph-fill ph-magic-wand text-teal-500 dark:text-kinetic-primary text-lg"></i>
                </div>
                <button onclick="document.getElementById('summaryCard').classList.add('hidden')" class="text-slate-400 hover:text-slate-700 dark:hover:text-white transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <div class="space-y-4 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500 dark:text-gray-400">Total Pengajuan</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">24</span>    
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500 dark:text-gray-400">Selesai Review</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">20</span>
                </div>
            </div>

            <div class="w-full bg-slate-100 dark:bg-[#2A2A2A] h-2 rounded-full mb-3 overflow-hidden">
                <div class="bg-teal-500 dark:bg-[#4ade80] h-full rounded-full transition-all duration-1000 ease-out" style="width: 83%"></div>
            </div>

            <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase text-center">83% TARGET TERCAPAI</p>
        </div>

    </div>
    <div id="reviewModal" class="hidden fixed inset-0 z-[100] bg-[#0F0F0F] flex-col transition-opacity duration-300">
    
    <div class="h-20 border-b border-[#2A2A2A] flex items-center justify-between px-6 shrink-0 bg-[#151515]">
        <div class="flex items-center gap-4">
            <button onclick="closeReviewModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-[#2A2A2A] text-white transition-colors">
                <i class="ph-bold ph-arrow-left text-xl"></i>
            </button>
            <div>
                <p class="text-[10px] font-bold tracking-widest text-kinetic-primary uppercase mb-0.5">Detail Permohonan</p>
                <h3 class="font-heading text-lg font-bold text-white">Seminar AI & Masa Depan Kerja</h3>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <button class="text-slate-400 hover:text-white transition-colors relative">
                <i class="ph ph-bell text-xl"></i>
                <span class="absolute top-0 right-0.5 w-2 h-2 bg-kinetic-primary rounded-full"></span>
            </button>
            <button class="text-slate-400 hover:text-white transition-colors">
                <i class="ph-fill ph-moon text-xl"></i>
            </button>
            <img src="https://ui-avatars.com/api/?name=Dr+Aris&background=0f172a&color=fff" class="w-9 h-9 rounded-full object-cover ml-2">
        </div>
    </div>

    <div class="flex flex-1 overflow-hidden">
        
        <div class="flex-1 p-6 flex flex-col bg-[#0F0F0F]">
            <div class="bg-[#1A1A1A] border border-[#2A2A2A] rounded-t-2xl p-4 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-2 text-slate-300 text-sm font-medium">
                    <i class="ph-fill ph-file-pdf text-kinetic-primary text-xl"></i>
                    proposal_seminar_ai.pdf
                </div>
                <div class="flex items-center gap-4 bg-[#2A2A2A] rounded-lg px-3 py-1.5 text-slate-300 text-sm">
                    <button class="hover:text-white"><i class="ph-bold ph-minus"></i></button>
                    <span class="w-10 text-center font-bold text-xs">85%</span>
                    <button class="hover:text-white"><i class="ph-bold ph-plus"></i></button>
                    <div class="w-px h-4 bg-slate-600 mx-1"></div>
                    <button class="hover:text-white"><i class="ph-bold ph-corners-out"></i></button>
                </div>
            </div>
            
            <div class="bg-white flex-1 overflow-y-auto rounded-b-2xl border-x border-b border-[#2A2A2A] custom-scrollbar p-10 relative">
                <div class="max-w-3xl mx-auto text-slate-900">
                    <div class="flex justify-between items-end border-b-2 border-slate-200 pb-4 mb-8">
                        <div>
                            <h1 class="font-heading text-2xl font-extrabold text-slate-900">SPACE.IN BOOKING REQUEST</h1>
                            <p class="text-[10px] font-bold text-slate-400 tracking-widest mt-1">DOC REF: PK-2023-0892</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">Date Generated</p>
                            <p class="text-sm font-bold text-slate-900">24 Okt 2023</p>
                        </div>
                    </div>

                    <h2 class="text-3xl font-extrabold font-heading mb-10 leading-tight">Proposal Penggunaan Auditorium Utama untuk Seminar AI 2023</h2>

                    <div class="grid grid-cols-2 gap-8 mb-10">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mb-1">Peminjam</p>
                            <p class="text-sm font-bold">BEM Fakultas Teknik</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mb-1">Penanggung Jawab</p>
                            <p class="text-sm font-bold">Ahmad Subarjo</p>
                        </div>
                    </div>

                    <p class="text-sm text-slate-600 leading-relaxed mb-8">
                        Seminar ini bertujuan untuk memberikan wawasan mendalam kepada mahasiswa mengenai perkembangan Artificial Intelligence dan dampaknya terhadap pasar kerja global di masa depan. Kami mengundang 3 praktisi industri sebagai pembicara utama.
                    </p>

                    <div class="grid grid-cols-3 gap-4 mb-10">
                        <div class="h-28 bg-slate-200 rounded-lg bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=500&q=80')"></div>
                        <div class="h-28 bg-slate-200 rounded-lg bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1591115765373-5207764f72e7?w=500&q=80')"></div>
                        <div class="h-28 bg-slate-200 rounded-lg bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1573164713988-8665fc963095?w=500&q=80')"></div>
                    </div>

                    <hr class="border-dashed border-slate-300 mb-8">

                    <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase mb-4">Verification Stamps</p>
                    <div class="flex gap-4">
                        <div class="w-20 h-20 rounded-full border-2 border-kinetic-primary flex items-center justify-center text-center p-2 text-[9px] font-bold text-kinetic-primary uppercase leading-tight rotate-[-15deg]">Verified By System</div>
                        <div class="w-20 h-20 rounded-full border-2 border-slate-300 flex items-center justify-center text-center p-2 text-[9px] font-bold text-slate-400 uppercase leading-tight">Waiting Signature</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-[420px] bg-[#151515] border-l border-[#2A2A2A] flex flex-col shrink-0">
            
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">
                
                <div class="bg-[#1A1A1A] border border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between mb-8">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-monitor-play text-slate-400 text-xl"></i>
                        <div>
                            <p class="text-sm font-bold text-white">Simulate Engine: Requires</p>
                            <p class="text-xs text-slate-500">Attachment</p>
                        </div>
                    </div>
                    <div class="w-10 h-6 bg-[#2A2A2A] rounded-full relative cursor-pointer">
                        <div class="absolute left-1 top-1 w-4 h-4 bg-slate-400 rounded-full"></div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-kinetic-primary shadow-[0_0_5px_rgba(20,184,166,0.5)]"></span>
                    <span class="text-[10px] font-bold text-kinetic-primary uppercase tracking-widest">Aktif Diproses</span>
                </div>

                <h2 class="font-heading text-3xl font-extrabold text-white mb-2 leading-tight">Seminar AI & Masa Depan Kerja</h2>
                <p class="text-xs text-slate-400 mb-8">Diajukan oleh <span class="font-bold text-white">BEM Teknik</span> • 2 jam yang lalu</p>

                <div class="grid grid-cols-2 gap-4 mb-10">
                    <div class="bg-[#1A1A1A] border border-[#2A2A2A] rounded-2xl p-4">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Ruangan</p>
                        <div class="flex items-start gap-2">
                            <i class="ph-bold ph-map-pin text-kinetic-primary mt-0.5"></i>
                            <p class="text-sm font-bold text-white">Auditorium Utama</p>
                        </div>
                    </div>
                    <div class="bg-[#1A1A1A] border border-[#2A2A2A] rounded-2xl p-4">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Kapasitas</p>
                        <div class="flex items-start gap-2">
                            <i class="ph-bold ph-users text-kinetic-primary mt-0.5"></i>
                            <p class="text-sm font-bold text-white">350 Orang</p>
                        </div>
                    </div>
                    <div class="bg-[#1A1A1A] border border-[#2A2A2A] rounded-2xl p-4">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Waktu</p>
                        <div class="flex items-start gap-2">
                            <i class="ph-bold ph-calendar-blank text-kinetic-primary mt-0.5"></i>
                            <p class="text-sm font-bold text-white">12 Nov 2023</p>
                        </div>
                    </div>
                    <div class="bg-[#1A1A1A] border border-[#2A2A2A] rounded-2xl p-4">
                        <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Durasi</p>
                        <div class="flex items-start gap-2">
                            <i class="ph-bold ph-clock text-kinetic-primary mt-0.5"></i>
                            <p class="text-sm font-bold text-white">08:00 - 15:00</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-white text-sm">Progress Persetujuan</h3>
                    <span class="px-3 py-1 rounded-full bg-[#1A1A1A] border border-[#2A2A2A] text-kinetic-primary text-[10px] font-bold">Tahap 2 dari 4</span>
                </div>

                <div class="space-y-6 relative before:absolute before:inset-0 before:ml-[15px] before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-[#2A2A2A]">
                    
                    <div class="relative flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-kinetic-primary flex items-center justify-center shrink-0 z-10 shadow-[0_0_10px_rgba(45,212,191,0.3)]">
                            <i class="ph-bold ph-check text-[#151515] text-sm"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white">Verifikasi Berkas</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Oleh Admin Ruangan • Disetujui</p>
                        </div>
                    </div>

                    <div class="relative flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-[#151515] border-2 border-kinetic-primary flex items-center justify-center shrink-0 z-10">
                            <div class="w-2.5 h-2.5 rounded-full bg-kinetic-primary shadow-[0_0_8px_rgba(45,212,191,0.8)]"></div>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-kinetic-primary">Kepala Jurusan</h4>
                            <p class="text-xs text-slate-400 mt-0.5">Anda sedang meninjau dokumen ini</p>
                        </div>
                    </div>

                    <div class="relative flex items-start gap-4 opacity-50">
                        <div class="w-8 h-8 rounded-full bg-[#151515] border-2 border-[#2A2A2A] flex items-center justify-center shrink-0 z-10"></div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-500">Dekanat</h4>
                            <p class="text-xs text-slate-600 mt-0.5">Menunggu tahap sebelumnya</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="p-6 border-t border-[#2A2A2A] bg-[#151515]">
                <div class="flex gap-4">
                    <button class="flex-1 py-3.5 rounded-2xl border border-red-500/20 text-red-500 font-bold text-sm hover:bg-red-500/10 transition-colors">
                        Tolak/Revisi
                    </button>
                    <button class="flex-1 py-3.5 rounded-2xl bg-kinetic-primary text-[#151515] font-bold text-sm hover:bg-[#2dd4bf] transition-colors shadow-[0_0_20px_rgba(45,212,191,0.2)]">
                        Setujui Sekarang
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    function openReviewModal() {
        const modal = document.getElementById('reviewModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Opsional: Matikan scroll pada body saat modal terbuka
        document.body.style.overflow = 'hidden';
    }

    function closeReviewModal() {
        const modal = document.getElementById('reviewModal');
        modal.classList.remove('flex');
        modal.classList.add('hidden');
        
        // Kembalikan scroll body
        document.body.style.overflow = 'auto';
    }
</script>
</x-app-layout>