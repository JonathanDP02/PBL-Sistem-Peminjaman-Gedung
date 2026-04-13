<x-app-layout>
    <div class="bg-slate-50 dark:bg-[#0f0f0f] min-h-screen py-12 text-slate-800 dark:text-[#e5e5e5] font-sans transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-8">
                <div>
                    <p class="text-teal-600 dark:text-[#2dd4bf] text-xs font-bold tracking-widest uppercase mb-2">Inventaris & Fasilitas</p>
                    <h1 class="text-4xl font-bold text-slate-900 dark:text-white leading-tight mb-3">Kelola Fasilitas Ruangan</h1>
                    <p class="text-slate-500 dark:text-gray-400 text-sm max-w-2xl">Pantau ketersediaan fasilitas, kondisi barang, dan kelengkapan ruang agar setiap booking berjalan lancar.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch gap-3">
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-kinetic-primary px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-600 dark:hover:bg-kinetic-secondary">
                        <i class="ph-bold ph-plus"></i>
                        Tambah Fasilitas
                    </button>
                    <button type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-slate-300 bg-white dark:border-kinetic-border dark:bg-slate-950 px-6 py-3 text-sm font-semibold text-slate-800 dark:text-white transition hover:border-cyan-400 hover:text-cyan-600 dark:hover:border-kinetic-secondary/60 dark:hover:text-kinetic-secondary">
                        <i class="ph-bold ph-faders-horizontal"></i>
                        Filter
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[1.8fr_1fr] gap-6">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                        <div class="rounded-3xl border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] p-6 shadow-sm dark:shadow-none">
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400 dark:text-gray-500 mb-4">Total Fasilitas</p>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">128</h2>
                            <p class="mt-3 text-sm text-slate-500 dark:text-gray-400">Semua item fasilitas dalam inventaris.</p>
                        </div>

                        <div class="rounded-3xl border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] p-6 shadow-sm dark:shadow-none">
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400 dark:text-gray-500 mb-4">Kategori</p>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">5</h2>
                            <p class="mt-3 text-sm text-slate-500 dark:text-gray-400">Audio, Visual, Furnitur, IT, Keamanan.</p>
                        </div>

                        <div class="rounded-3xl border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] p-6 shadow-sm dark:shadow-none">
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400 dark:text-gray-500 mb-4">Tersedia</p>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">102</h2>
                            <p class="mt-3 text-sm text-slate-500 dark:text-gray-400">Siap dipakai untuk pemesanan ruangan.</p>
                        </div>

                        <div class="rounded-3xl border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] p-6 shadow-sm dark:shadow-none">
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400 dark:text-gray-500 mb-4">Perlu Servis</p>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">8</h2>
                            <p class="mt-3 text-sm text-slate-500 dark:text-gray-400">Fasilitas dalam status maintenance atau rusak.</p>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] shadow-sm dark:shadow-none overflow-hidden">
                        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Fasilitas</h2>
                                <p class="mt-1 text-sm text-slate-500 dark:text-gray-400">Kelola item fasilitas bersama status dan lokasi ruangannya.</p>
                            </div>
                            <div class="inline-flex items-center gap-3 rounded-2xl bg-slate-50 dark:bg-[#111111] px-4 py-3 border border-slate-200 dark:border-kinetic-border">
                                <span class="text-xs font-semibold uppercase tracking-[0.3em] text-slate-500 dark:text-gray-400">Sort</span>
                                <select class="bg-transparent outline-none text-sm text-slate-900 dark:text-white">
                                    <option>Terbaru</option>
                                    <option>Kategori</option>
                                    <option>Status</option>
                                </select>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm text-slate-600 dark:text-slate-300">
                                <thead class="border-b border-slate-200 dark:border-kinetic-border bg-slate-50 dark:bg-[#111111] text-slate-500 dark:text-slate-400">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Fasilitas</th>
                                        <th class="px-6 py-4 font-semibold">Kategori</th>
                                        <th class="px-6 py-4 font-semibold">Lokasi</th>
                                        <th class="px-6 py-4 font-semibold">Jumlah</th>
                                        <th class="px-6 py-4 font-semibold">Status</th>
                                        <th class="px-6 py-4 font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-kinetic-border/70">
                                    @foreach ([
                                        ['name' => 'Proyektor Laser', 'category' => 'Visual', 'location' => 'Ruang Rapat 01', 'count' => '4 unit', 'status' => 'Tersedia'],
                                        ['name' => 'Microphone Wireless', 'category' => 'Audio', 'location' => 'Auditorium', 'count' => '12 unit', 'status' => 'Tersedia'],
                                        ['name' => 'Speaker Portable', 'category' => 'Audio', 'location' => 'Lab Komputer', 'count' => '6 unit', 'status' => 'Maintenance'],
                                        ['name' => 'Kursi Lipat', 'category' => 'Furnitur', 'location' => 'Gedung A', 'count' => '30 unit', 'status' => 'Tersedia'],
                                        ['name' => 'Laptop Presenter', 'category' => 'IT', 'location' => 'Ruang Seminar', 'count' => '8 unit', 'status' => 'Dipakai'],
                                    ] as $item)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-[#111111] transition-colors">
                                            <td class="px-6 py-4 font-semibold text-slate-900 dark:text-white">{{ $item['name'] }}</td>
                                            <td class="px-6 py-4">{{ $item['category'] }}</td>
                                            <td class="px-6 py-4">{{ $item['location'] }}</td>
                                            <td class="px-6 py-4">{{ $item['count'] }}</td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.24em] {{ $item['status'] === 'Tersedia' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300' : ($item['status'] === 'Maintenance' ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300') }}">
                                                    {{ $item['status'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button type="button" class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-kinetic-border dark:bg-[#111111] dark:text-slate-300 dark:hover:bg-slate-900">Edit</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-[#161616] shadow-sm dark:shadow-none p-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Status Kategori</h2>
                        <p class="mt-2 text-sm text-slate-500 dark:text-gray-400">Ringkasan kondisi fasilitas per kategori.</p>
                        <div class="mt-6 space-y-4">
                            @foreach ([
                                ['label' => 'Audio', 'value' => '34', 'color' => 'bg-cyan-500/10 text-cyan-600'],
                                ['label' => 'Visual', 'value' => '22', 'color' => 'bg-emerald-500/10 text-emerald-600'],
                                ['label' => 'IT', 'value' => '18', 'color' => 'bg-indigo-500/10 text-indigo-600'],
                            ] as $status)
                                <div class="rounded-3xl bg-slate-50 dark:bg-[#111111] p-4 border border-slate-200 dark:border-kinetic-border">
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
                        <ul class="mt-4 space-y-4 text-sm text-slate-500 dark:text-gray-400">
                            <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-amber-500"></span>4 proyektor akan masuk jadwal maintenance besok.</li>
                            <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>Microphone wireless siap digunakan untuk acara besar.</li>
                            <li class="flex gap-3"><span class="mt-1 inline-flex h-2.5 w-2.5 rounded-full bg-red-500"></span>2 laptop presenter rusak dan sedang diperbaiki.</li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>