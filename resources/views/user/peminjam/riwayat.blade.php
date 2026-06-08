<x-app-layout>
    <div class="bg-slate-50 dark:bg-[#0f0f0f] min-h-screen py-12 text-slate-800 dark:text-[#e5e5e5] font-sans transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-8">
                <div>
                    <p class="text-teal-600 dark:text-[#2dd4bf] text-xs font-bold tracking-widest uppercase mb-2">Pantau & lihat status</p>
                    <h1 class="text-4xl font-bold text-slate-900 dark:text-white leading-tight mb-3">Riwayat Peminjaman</h1>
                    <p class="text-slate-500 dark:text-gray-400 text-sm max-w-2xl">Pantau Riwayat gedung yang telah diBooking.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[1.8fr_1fr] gap-6">
                <div class="space-y-6">
                    
                    <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] shadow-sm dark:shadow-none overflow-hidden">
                        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Booking</h2>
                                <p class="mt-1 text-sm text-slate-500 dark:text-gray-400">Kelola & pantau status pemesanan Anda.</p>
                            </div>
                            <div class="inline-flex items-center gap-3 rounded-2xl bg-slate-50 dark:bg-[#111111] px-4 py-3 border border-slate-200 dark:border-kinetic-border">
                                <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-gray-400">Sort</span>
                                <select class="bg-transparent outline-none text-sm text-slate-900 dark:text-white cursor-pointer">
                                    <option>Terbaru</option>
                                    <option>Terlama</option>
                                    <option>Status</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-6 pt-0 space-y-3">
                            {{-- Looping Data dari Database --}}
                            @forelse ($bookings as $booking)
                               <a href="{{ route('detail', $booking->id) }}"
                                   class="group block flex items-center justify-between p-4 border rounded-xl bg-slate-50 dark:bg-slate-800/30 border-slate-200 dark:border-slate-700/60 hover:bg-white dark:hover:bg-slate-800/80 hover:border-teal-500/50 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                                    
                                    <div>
                                        <h3 class="font-bold text-slate-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">
                                            {{ $booking->room->room_name ?? 'Ruangan N/A' }}
                                        </h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                            {{ $booking->room->building->name ?? $booking->room->building->building_name ?? 'Gedung' }} - Kapasitas {{ $booking->room->capacity ?? 0 }} Orang
                                        </p>
                                        <p class="text-xs text-teal-600 dark:text-teal-500 font-bold mt-1">
                                            Kegiatan: {{ $booking->event_name }}
                                        </p>
                                        @if(in_array(Auth::user()?->role?->name, ['Administrator Utama', 'Administrator Unit']))
                                            <p class="text-xs text-slate-600 dark:text-gray-400 mt-0.5">
                                                Peminjam: <span class="font-semibold">{{ $booking->user->name ?? 'User' }}</span>
                                            </p>
                                        @endif
                                        <p class="text-[10px] text-slate-400 dark:text-gray-500 mt-1.5">
                                            {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('d M Y') }} • {{ date('H:i', strtotime($booking->start_time)) }} - {{ date('H:i', strtotime($booking->end_time)) }} WIB
                                        </p>
                                    </div>

                                    @php
                                        // Dinamisasi warna badge berdasarkan status dari database
                                        $statusClass = '';
                                        if ($booking->status === 'Approved') {
                                            $statusClass = 'text-emerald-700 dark:text-emerald-400 bg-emerald-500/10 border-emerald-500/20';
                                        } elseif ($booking->status === 'Pending') {
                                            $statusClass = 'text-amber-700 dark:text-amber-400 bg-amber-500/10 border-amber-500/20';
                                        } elseif (in_array($booking->status, ['Rejected', 'Cancelled', 'Revising'])) {
                                            $statusClass = 'text-rose-700 dark:text-rose-400 bg-rose-500/10 border-rose-500/20';
                                        } else {
                                            $statusClass = 'text-slate-700 dark:text-slate-400 bg-slate-500/10 border-slate-500/20';
                                        }
                                    @endphp

                                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full border {{ $statusClass }}">
                                        {{ $booking->status }}
                                    </span>
                                </a>
                            @empty
                                <div class="text-center py-10">
                                    <i class="ph ph-calendar-blank text-4xl text-slate-300 dark:text-gray-600 mb-3 block"></i>
                                    <p class="text-sm text-slate-500 dark:text-gray-400">Belum ada riwayat peminjaman.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] shadow-sm dark:shadow-none p-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Status Pemesanan</h2>
                        <div class="mt-6 space-y-4">
                            @php
                                // Array Status Dinamis dari controller
                                $statusSummary = [
                                    ['label' => 'Approved', 'value' => $statusCounts['Approved'] ?? 0, 'color' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'],
                                    ['label' => 'Waiting', 'value' => $statusCounts['Pending'] ?? 0, 'color' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400'],
                                    ['label' => 'Cancel / Reject', 'value' => $statusCounts['Rejected'] ?? 0, 'color' => 'bg-red-500/10 text-red-600 dark:text-red-400'],
                                ];
                            @endphp

                            @foreach ($statusSummary as $status)
                                <div class="rounded-3xl bg-slate-50 dark:bg-[#111111] p-4 border border-slate-200 dark:border-kinetic-border hover:border-slate-300 dark:hover:border-slate-600 transition-colors">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $status['label'] }}</p>
                                            <p class="text-xs text-slate-500 dark:text-gray-400 mt-1">{{ $status['value'] }} item</p>
                                        </div>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $status['color'] }}">Aktif</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-slate-50 dark:bg-[#111111] p-6 shadow-sm dark:shadow-none">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Perhatian</h2>
                        <ul class="mt-4 space-y-4 text-sm text-slate-600 dark:text-gray-400">
                            @if($statusCounts['Pending'] > 0)
                                <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></span>{{ $statusCounts['Pending'] }} Booking sedang menunggu persetujuan.</li>
                            @endif
                            
                            @if($statusCounts['Approved'] > 0)
                                <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>{{ $statusCounts['Approved'] }} Booking sudah disetujui.</li>
                            @endif

                            @if($statusCounts['Rejected'] > 0)
                                <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 shrink-0 rounded-full bg-red-500"></span>{{ $statusCounts['Rejected'] }} Booking ditolak atau dibatalkan.</li>
                            @endif

                            @if(array_sum($statusCounts) == 0)
                                <li class="text-slate-400 italic">Belum ada aktivitas yang perlu diperhatikan.</li>
                            @endif
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>