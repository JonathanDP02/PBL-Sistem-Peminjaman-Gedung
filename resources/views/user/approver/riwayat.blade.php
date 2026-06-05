<x-app-layout title="Riwayat Persetujuan">
    <div class="relative px-8 pt-6 pb-32 space-y-10 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h1 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-2">Riwayat Persetujuan</h1>
                <p class="text-sm text-slate-500 dark:text-gray-400">
                    Menampilkan seluruh pengajuan yang telah Anda tinjau.
                </p>
            </div>
            
            <div class="flex flex-col md:flex-row items-center gap-4 w-full md:w-auto">
                <form action="{{ route('riwayat') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full">
                    
                    <div class="relative w-full md:w-48">
                        <select name="unit_id" onchange="this.form.submit()" 
                            class="w-full pl-4 pr-10 py-2.5 bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm text-slate-700 dark:text-white focus:ring-teal-500 outline-none appearance-none transition-colors">
                            <option value="">Semua Unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->unit_name }}
                                </option>
                            @endforeach
                        </select>
                        <i class="ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>

                    <div class="relative w-full md:w-72">
                        <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Cari riwayat event/ruang..." 
                            class="w-full pl-11 pr-4 py-2.5 bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-teal-500 focus:border-teal-500 transition-colors placeholder:text-slate-400 dark:placeholder:text-gray-600">
                    </div>

                    @if(request('search') || request('unit_id'))
                        <a href="{{ route('riwayat') }}" class="flex items-center justify-center px-2 text-xs font-bold text-red-500 hover:text-red-600 transition-colors">
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-[#2A2A2A]">
                        <th class="px-2 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Nama Event</th>
                        <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Peminjam</th>
                        <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Ruangan</th>
                        <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Keputusan</th>
                        <th class="px-6 pb-4 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest text-center">Detail</th>
                    </tr>
                </thead>
                
                <tbody class="divide-y divide-slate-100 dark:divide-[#1E1E1E]">
                    @forelse($approvals as $approval)
                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group">
                            <td class="px-2 py-5">
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">{{ $approval->booking->event_name }}</h4>
                                <p class="text-[10px] text-slate-500">{{ $approval->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-[#2A2A2A] flex items-center justify-center text-[10px] font-bold text-slate-600 dark:text-gray-300">
                                        {{ substr($approval->booking->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-700 dark:text-gray-300">{{ $approval->booking->user->name }}</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-500">{{ $approval->booking->user->unit->unit_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700 dark:text-gray-300">{{ $approval->booking->room->room_name }}</span>
                                    <span class="text-[10px] text-slate-500">{{ $approval->booking->room->building->building_name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @php
                                    $statusClass = $approval->approval_status === 'Approved' 
                                        ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' 
                                        : 'bg-red-500/10 text-red-500 border-red-500/20';
                                @endphp
                                <span class="px-3 py-1 border rounded-full text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">
                                    {{ $approval->approval_status }}
                                </span>
                                @if($approval->notes)
                                    <p class="text-[10px] text-slate-500 mt-2 italic max-w-xs overflow-hidden text-ellipsis whitespace-nowrap">"{{ $approval->notes }}"</p>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <button onclick="openHistoryModal({{ $approval->booking->id }})" class="text-slate-400 hover:text-teal-600 dark:hover:text-kinetic-primary transition-colors">
                                    <i class="ph-bold ph-eye text-xl"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="ph ph-calendar-x text-5xl text-slate-200 dark:text-[#2A2A2A] mb-4"></i>
                                    <p class="text-slate-400 dark:text-gray-600 font-medium">Data riwayat tidak ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $approvals->appends(request()->input())->links() }}
        </div>
    </div>

    <!-- History Modal -->
    <template x-teleport="body">
        <div id="historyModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-300">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-2xl p-8 relative shadow-2xl transform scale-95 transition-all duration-300 flex flex-col max-h-[90vh]">
                
                <button onclick="closeHistoryModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>

                <div class="mb-6 shrink-0">
                    <h3 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-1">Detail Riwayat Pengajuan</h3>
                    <p id="modalEventName" class="text-xl text-slate-500 dark:text-gray-400 italic">Memuat...</p>
                </div>

                <div class="flex-1 overflow-y-auto pr-1 space-y-6 custom-scrollbar">
                    <!-- Info Grid -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-slate-50 dark:bg-[#1A1A1A] p-4 rounded-2xl border border-slate-100 dark:border-[#2A2A2A]">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Peminjam</p>
                            <p id="modalBorrower" class="text-sm font-bold text-slate-700 dark:text-gray-300">-</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-[#1A1A1A] p-4 rounded-2xl border border-slate-100 dark:border-[#2A2A2A]">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Ruangan</p>
                            <p id="modalRoom" class="text-sm font-bold text-slate-700 dark:text-gray-300">-</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-[#1A1A1A] p-4 rounded-2xl border border-slate-100 dark:border-[#2A2A2A]">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Tanggal & Waktu</p>
                            <p id="modalDateTime" class="text-sm font-bold text-slate-700 dark:text-gray-300">-</p>
                        </div>
                        <div class="bg-slate-50 dark:bg-[#1A1A1A] p-4 rounded-2xl border border-slate-100 dark:border-[#2A2A2A]">
                            <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Status Akhir</p>
                            <p id="modalStatus" class="text-sm font-bold">-</p>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="ph-bold ph-git-merge text-teal-500 dark:text-kinetic-primary"></i>
                            Timeline Persetujuan
                        </h4>
                        
                        <div id="approvalTimeline" class="space-y-6 relative before:absolute before:left-4 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100 dark:before:bg-[#2A2A2A]">
                            <!-- Injected by JS -->
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-200 dark:border-[#2A2A2A] mt-6 flex justify-end shrink-0">
                    <button onclick="closeHistoryModal()" class="px-6 py-2.5 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold text-xs transition-colors shadow-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script>
        function openHistoryModal(bookingId) {
            const modal = document.getElementById('historyModal');
            const timeline = document.getElementById('approvalTimeline');
            if (modal) modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            if (timeline) timeline.innerHTML = '<p class="text-center text-slate-400 text-xs italic py-4">Memuat riwayat...</p>';

            fetch(`/approver/approvals/${bookingId}`, {
                headers: { 'Accept': 'application/json' }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Gagal memuat detail riwayat (Status: ' + response.status + ')');
                }
                return response.json();
            })
            .then(res => {
                if (res.success) {
                    const data = res.data;
                    document.getElementById('modalEventName').textContent = data.booking.event_name;
                    document.getElementById('modalBorrower').textContent = data.peminjam.name;
                    document.getElementById('modalRoom').textContent = data.room.room_name;
                    document.getElementById('modalDateTime').textContent = `${data.booking.booking_date} (${data.booking.start_time} - ${data.booking.end_time})`;
                    
                    const statusEl = document.getElementById('modalStatus');
                    statusEl.textContent = data.booking.status;
                    statusEl.className = `text-sm font-bold ${data.booking.status === 'Approved' ? 'text-emerald-500' : 'text-amber-500'}`;

                    let timelineHtml = '';
                    if (data.approval_history.length > 0) {
                        data.approval_history.forEach(hist => {
                            const isApproved = hist.approval_status === 'Approved';
                            timelineHtml += `
                                <div class="relative pl-10">
                                    <div class="absolute left-1.5 top-1 w-5 h-5 rounded-full flex items-center justify-center ${isApproved ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.3)]' : 'bg-red-500'} z-10">
                                        <i class="ph-bold ${isApproved ? 'ph-check' : 'ph-x'} text-[10px] text-white"></i>
                                    </div>
                                    <div class="bg-white dark:bg-[#151515] border border-slate-100 dark:border-[#2A2A2A] rounded-2xl p-4 shadow-sm">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h5 class="text-sm font-bold text-slate-900 dark:text-white">${hist.position}</h5>
                                                <p class="text-[10px] text-slate-500">Oleh ${hist.approver_name}</p>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-[8px] font-bold uppercase ${isApproved ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700'}">${hist.approval_status}</span>
                                        </div>
                                        ${hist.notes ? `<p class="text-[11px] text-slate-600 bg-slate-50 p-2 rounded-lg mt-2 border-l-2 ${isApproved ? 'border-emerald-400' : 'border-red-400'} italic">"${hist.notes}"</p>` : ''}
                                        <p class="text-[9px] text-slate-400 mt-3"><i class="ph ph-clock"></i> ${hist.approved_at_formatted}</p>
                                    </div>
                                </div>`;
                        });
                    }
                    if (timeline) timeline.innerHTML = timelineHtml;
                } else {
                    throw new Error(res.error || 'Terjadi kesalahan saat memuat data.');
                }
            })
            .catch(err => {
                console.error(err);
                if (timeline) {
                    timeline.innerHTML = `<p class="text-center text-red-500 text-sm font-bold py-4">Error: ${err.message}</p>`;
                }
            });
        }

        function closeHistoryModal() {
            const modal = document.getElementById('historyModal');
            if (modal) modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.body.addEventListener('click', function(e) {
            const modal = document.getElementById('historyModal');
            if (modal && e.target === modal) {
                closeHistoryModal();
            }
        });
    </script>
</x-app-layout>