<x-app-layout>
    <div class="relative px-8 py-8 space-y-8 min-h-full">
        <div class="absolute top-0 right-0 w-96 h-96 bg-cyan-100/40 dark:bg-kinetic-tertiary/10 rounded-full blur-[120px] pointer-events-none"></div>

        <section class="relative rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none overflow-hidden p-8">
            <div class="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-cyan-50 dark:from-kinetic-secondary/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-cyan-50 dark:bg-kinetic-secondary/10 text-cyan-700 dark:text-kinetic-secondary text-[11px] font-bold uppercase tracking-[0.3em] border border-cyan-200 dark:border-kinetic-secondary/20">Laporan</span>
                    <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">Ringkasan Laporan</h1>
                    <p class="mt-3 text-sm text-slate-500 dark:text-gray-400 max-w-2xl">Pantau performa pemakaian ruang, status booking, dan unit paling aktif dalam sistem peminjaman.</p>
                </div>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-kinetic-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-600 dark:hover:bg-kinetic-secondary">
                        <i class="ph-bold ph-download"></i>
                        Ekspor PDF
                    </button>
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white dark:border-kinetic-border dark:bg-slate-950 px-6 py-3 text-sm font-semibold text-slate-800 dark:text-white transition hover:border-cyan-400 hover:text-cyan-600 dark:hover:border-kinetic-secondary/60 dark:hover:text-kinetic-secondary">
                        <i class="ph-bold ph-faders-horizontal"></i>
                        Filter Laporan
                    </button>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <p class="text-xs uppercase tracking-[0.26em] text-slate-400 dark:text-gray-500 font-bold mb-4">Booking Bulanan</p>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">264</h2>
                <p class="mt-3 text-sm text-slate-500 dark:text-gray-400">Jumlah semua booking di bulan ini.</p>
            </div>
            <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <p class="text-xs uppercase tracking-[0.26em] text-slate-400 dark:text-gray-500 font-bold mb-4">Ruang Terpakai</p>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">18</h2>
                <p class="mt-3 text-sm text-slate-500 dark:text-gray-400">Ruangan aktif dalam periode terpilih.</p>
            </div>
            <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <p class="text-xs uppercase tracking-[0.26em] text-slate-400 dark:text-gray-500 font-bold mb-4">Disetujui</p>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">198</h2>
                <p class="mt-3 text-sm text-slate-500 dark:text-gray-400">Booking yang sudah disetujui.</p>
            </div>
            <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <p class="text-xs uppercase tracking-[0.26em] text-slate-400 dark:text-gray-500 font-bold mb-4">Menunggu</p>
                <h2 class="text-3xl font-extrabold text-slate-900 dark:text-white">27</h2>
                <p class="mt-3 text-sm text-slate-500 dark:text-gray-400">Booking yang masih menunggu persetujuan.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-[1.8fr_1fr] gap-6">
            <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 dark:text-white">Booking Terbaru</h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-gray-400">Daftar booking terbaru yang sedang diproses.</p>
                    </div>
                    <button type="button" class="rounded-full border border-slate-300 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-cyan-400 hover:text-cyan-600 dark:border-kinetic-border dark:bg-slate-950 dark:text-white dark:hover:border-kinetic-secondary/60 dark:hover:text-kinetic-secondary">Lihat Semua</button>
                </div>

                <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200 dark:border-kinetic-border">
                    <table class="min-w-full text-left text-sm">
                        <thead class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Ruang</th>
                                <th class="px-6 py-4 font-semibold">Unit</th>
                                <th class="px-6 py-4 font-semibold">Status</th>
                                <th class="px-6 py-4 font-semibold">Tanggal</th>
                                <th class="px-6 py-4 font-semibold">Pemesan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-kinetic-border/70">
                            @foreach ([
                                ['room' => 'Ruang Rapat A', 'unit' => 'Teknik Sipil', 'status' => 'Disetujui', 'date' => '12 Apr 2026', 'user' => 'Ardi'],
                                ['room' => 'Lab Komputer', 'unit' => 'TI', 'status' => 'Menunggu', 'date' => '13 Apr 2026', 'user' => 'Rina'],
                                ['room' => 'Ruang Serba Guna', 'unit' => 'Humas', 'status' => 'Ditolak', 'date' => '14 Apr 2026', 'user' => 'Sinta'],
                            ] as $booking)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-900 transition">
                                    <td class="px-6 py-4 text-slate-900 dark:text-white">{{ $booking['room'] }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $booking['unit'] }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] {{ $booking['status'] === 'Disetujui' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-500/20' : ($booking['status'] === 'Menunggu' ? 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700' : 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-300 border border-red-200 dark:border-red-500/20') }}">
                                            {{ $booking['status'] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $booking['date'] }}</td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">{{ $booking['user'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <aside class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card p-6 shadow-sm dark:shadow-none">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white">Unit Paling Aktif</h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-gray-400">Unit dengan penggunaan ruangan terbanyak bulan ini.</p>

                <div class="mt-6 space-y-4">
                    @foreach ([
                        ['unit' => 'Teknik Sipil', 'usage' => '34 booking'],
                        ['unit' => 'Teknik Informatika', 'usage' => '27 booking'],
                        ['unit' => 'Humas', 'usage' => '18 booking'],
                    ] as $unit)
                        <div class="rounded-3xl border border-slate-200 dark:border-kinetic-border/80 bg-slate-50 dark:bg-slate-950 p-4">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $unit['unit'] }}</p>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $unit['usage'] }}</p>
                                </div>
                                <div class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600 dark:bg-kinetic-secondary/10 dark:text-kinetic-secondary">
                                    <i class="ph-bold ph-fire"></i>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 rounded-3xl border border-slate-200 dark:border-kinetic-border/80 bg-slate-50 dark:bg-slate-950 p-4">
                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Rekomendasi</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Tingkatkan persetujuan untuk unit yang masih banyak menunggu.</p>
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>