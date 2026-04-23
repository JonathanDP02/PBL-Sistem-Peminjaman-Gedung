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
                                <p class="mt-1 text-sm text-slate-500 dark:text-gray-400">Kelola & pantau status booking Anda.</p>
                            </div>
                            <div class="inline-flex items-center gap-3 rounded-2xl bg-slate-50 dark:bg-[#111111] px-4 py-3 border border-slate-200 dark:border-kinetic-border">
                                <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-gray-400">Sort</span>
                                <select class="bg-transparent outline-none text-sm text-slate-900 dark:text-white cursor-pointer">
                                    <option>Terbaru</option>
                                    <option>Kategori</option>
                                    <option>Status</option>
                                </select>
                            </div>
                        </div>

                        <div class="p-6 pt-0 space-y-3">
                            @foreach ([
                                ['id' => 1, 'building' => 'Gedung Sipil', 'room' => 'Ruang Rapat 01', 'facility' => 'Proyektor Laser', 'capacity' => '40 Orang', 'status' => 'Approve'],
                                ['id' => 2, 'building' => 'Gedung Mesin', 'room' => 'Auditorium', 'facility' => 'Microphone Wireless', 'capacity' => '60 orang', 'status' => 'Approve'],
                                ['id' => 3, 'building' => 'Gedung Mesin', 'room' => 'Lab Komputer', 'facility' => 'Speaker Portable', 'capacity' => '40 orang', 'status' => 'Waiting'],
                                ['id' => 4, 'building' => 'Gedung Sipil', 'room' => 'Gedung A', 'facility' => 'Kursi Lipat', 'capacity' => '30 orang', 'status' => 'Approve'],
                                ['id' => 5, 'building' => 'Aula Pertamina', 'room' => 'Ruang Seminar', 'facility' => 'Laptop Presenter', 'capacity' => '60 orang', 'status' => 'Cancel'],
                            ] as $item)

                               <a href="{{ route('detail') }}"
                                   class="group block flex items-center justify-between p-4 border rounded-xl bg-slate-50 dark:bg-slate-800/30 border-slate-200 dark:border-slate-700/60 hover:bg-white dark:hover:bg-slate-800/80 hover:border-teal-500/50 hover:shadow-md transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                                    
                                    <div>
                                        <h3 class="font-bold text-slate-900 dark:text-white group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors">{{ $item['room'] }}</h3>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $item['building'] }} - {{ $item['capacity'] }}</p>
                                    </div>

                                    <span class="px-3 py-1.5 text-xs font-semibold rounded-full border 
                                        @if($item['status'] === 'Approve') 
                                            text-emerald-700 dark:text-emerald-400 bg-emerald-500/10 border-emerald-500/20
                                        @elseif($item['status'] === 'Waiting') 
                                            text-amber-700 dark:text-amber-400 bg-amber-500/10 border-amber-500/20
                                        @elseif($item['status'] === 'Cancel') 
                                            text-rose-700 dark:text-rose-400 bg-rose-500/10 border-rose-500/20
                                        @else
                                            text-slate-700 dark:text-slate-400 bg-slate-500/10 border-slate-500/20
                                        @endif
                                    ">
                                        {{ $item['status'] }}
                                    </span>
                                </a>

                            @endforeach
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] shadow-sm dark:shadow-none p-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Status Booking</h2>
                        <div class="mt-6 space-y-4">
                            @foreach ([
                                ['label' => 'Approve', 'value' => '3', 'color' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'],
                                ['label' => 'Waiting', 'value' => '1', 'color' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400'],
                                ['label' => 'Cancel', 'value' => '1', 'color' => 'bg-red-500/10 text-red-600 dark:text-red-400'],
                            ] as $status)
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
                            <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 shrink-0 rounded-full bg-amber-500"></span>1 Booking sedang menunggu persetujuan.</li>
                            <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 shrink-0 rounded-full bg-emerald-500"></span>3 Booking sudah di setujui.</li>
                            <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 shrink-0 rounded-full bg-red-500"></span>1 Booking ditolak.</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>