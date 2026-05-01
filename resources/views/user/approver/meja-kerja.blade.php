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

            <div class="bg-teal-400 dark:bg-teal-400 hover:bg-teal-500 dark:hover:bg-teal-400 border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 md:p-8 text-white  dark:text-black flex flex-col justify-center cursor-pointer hover:scale-[1.02] hover:shadow-[0_10px_30px_rgba(45,212,191,0.3)] transition-all relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/20 rounded-full blur-3xl -mr-10 -mt-10 group-hover:bg-white/30 transition-all"></div>
                
                <i class="ph-bold ph-lightning dark:text-yellow-400 text-3xl mb-3"></i>
                <h3 class="font-heading text-2xl font-extrabold mb-1">SatSet Mode</h3>
                <p class="text-sm font-medium opacity-90">Fokus pada pengajuan prioritas.</p>
            </div>

        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                        <tr class="border-b border-slate-200 dark:border-[#2A2A2A]">
                            <th class="px-2 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Nama Event</th>
                            <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Peminjam</th>
                            <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Ruangan</th>
                            <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Waktu Digunakan</th>
                            <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1E1E1E]">
                        @forelse($approvals as $approval)
                            <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group">
                                <td class="px-2 py-5">
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">{{ $approval['booking']['event_name'] }}</h4>
                                    <p class="text-[10px] font-bold tracking-widest {{ $approval['priority_indicator'] === 'urgent' ? 'text-red-500' : ($approval['priority_indicator'] === 'high' ? 'text-yellow-500' : 'text-green-500') }} uppercase mt-1">{{ ucfirst($approval['priority_indicator']) }}</p>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-[#2A2A2A] flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-gray-300">
                                            {{ substr($approval['peminjam']['name'], 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-700 dark:text-gray-300">{{ $approval['peminjam']['name'] }}</p>
                                            <p class="text-[10px] text-slate-500 dark:text-gray-500">{{ $approval['time_remaining'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $approval['priority_indicator'] === 'urgent' ? 'bg-red-500' : ($approval['priority_indicator'] === 'high' ? 'bg-yellow-500' : 'bg-green-500') }} shadow-[0_0_5px_rgba(20,184,166,0.5)]"></span>
                                        <span class="text-sm font-bold text-slate-700 dark:text-gray-300">{{ $approval['room']['room_name'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <p class="text-sm font-medium text-slate-700 dark:text-gray-300">{{ $approval['booking']['booking_date'] }}</p>
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500 mt-0.5">{{ $approval['booking']['start_time'] }} - {{ $approval['booking']['end_time'] }}</p>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <a href="{{ route('approvals.show', $approval['booking']['id']) }}" class="px-5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-[#1A1A1A] dark:hover:bg-[#2A2A2A] border border-slate-200 dark:border-[#2A2A2A] text-slate-700 dark:text-white text-xs font-bold transition-colors">
                                        Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center">
                                    <p class="text-sm text-slate-500 dark:text-gray-400">Tidak ada permintaan tertunda</p>
                                </td>
                            </tr>
                        @endforelse
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
</div>
</x-app-layout>