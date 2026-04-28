<x-app-layout title="Dashboard Eksekutif">
    <div class="relative px-8 pt-4 pb-8 space-y-6 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-8 shadow-sm dark:shadow-none transition-colors">
            <p class="text-[10px] font-bold tracking-widest text-teal-600 dark:text-kinetic-primary uppercase mb-2">Ringkasan Eksekutif</p>
            <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2">Selamat Pagi, {{ Auth::user()->name }}.</h2>
            <p class="text-sm text-slate-500 dark:text-gray-400">
                Ada <span class="text-teal-600 dark:text-kinetic-primary font-bold">{{ $stats['pending_count'] }} permintaan tertunda</span> yang memerlukan keputusan Anda segera untuk menjaga kelancaran operasional fakultas.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-2">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary flex items-center justify-center border border-teal-100 dark:border-kinetic-primary/20">
                        <i class="ph-bold ph-dots-three-circle text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-teal-600 dark:text-kinetic-primary uppercase tracking-widest">{{ $stats['urgent_count'] }} MENDESAK</span>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">
                    {{ $stats['pending_count'] }}<span class="text-sm font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase ml-2">Items</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">Menunggu Review Saya</p>
            </div>

            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-500/10 text-blue-500 flex items-center justify-center border border-blue-100 dark:border-blue-500/20">
                        <i class="ph-bold ph-check-circle text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-blue-500 uppercase tracking-widest">BULAN INI</span>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">
                    {{ $monthlyApprovals }}<span class="text-sm font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase ml-2">Total</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">Disetujui Bulan Ini</p>
            </div>

            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center border border-red-100 dark:border-red-500/20">
                        <i class="ph-bold ph-x-circle text-xl"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">DITOLAK</span>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">
                    {{ $monthlyRejections }}<span class="text-sm font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase ml-2">Kasus</span>
                </h2>
                <p class="text-xs text-slate-500 dark:text-gray-400 font-medium">Ditolak / Revisi</p>
            </div>
        </div>

        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl shadow-sm dark:shadow-none transition-colors px-6 py-6 space-y-6">
            
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="font-heading text-xl font-extrabold text-slate-900 dark:text-white">Antrean Paling Mendesak</h3>
                    <p class="text-sm text-slate-500 dark:text-gray-400 mt-1">Permohonan dengan prioritas waktu tertinggi.</p>
                </div>
                <a href="#" class="flex items-center gap-2 px-4 py-2 bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] hover:border-teal-400 dark:hover:border-kinetic-primary rounded-xl text-sm font-bold text-slate-700 dark:text-gray-300 transition-colors">
                    Lihat Semua <i class="ph-bold ph-arrow-right text-teal-600 dark:text-kinetic-primary"></i>
                </a>
            </div>

            <div class="overflow-x-auto custom-scrollbar pt-2">
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
        </div>
        <footer class="mt-auto pt-8 pb-4 text-center">
            <p class="text-[9px] font-bold tracking-[0.2em] text-slate-400 dark:text-[#bbb] uppercase transition-colors duration-300">© 2026 SPACE.IN INFRASTRUCTURE ECOSYSTEM • V2.4.0 HIGH-PULSE EDITION</p>
        </footer>
    </div>
</x-app-layout>