<x-app-layout>
    <div class="relative px-8 py-8 space-y-8 min-h-full">
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-100/30 dark:bg-kinetic-primary/10 rounded-full blur-[100px] pointer-events-none"></div>

        <section class="relative rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none overflow-hidden p-8">
            <div class="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-teal-50 dark:from-kinetic-primary/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-secondary text-[11px] font-bold uppercase tracking-[0.3em] border border-teal-200 dark:border-kinetic-primary/20">Daftar Ruangan</span>
                    <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">Manajemen Ruangan</h1>
                    <p class="mt-3 text-sm text-slate-500 dark:text-gray-400 max-w-2xl">Definisikan peran pemberi persetujuan dalam struktur organisasi unit untuk mengotomatisasi alur kerja pemesanan ruang.</p>
                </div>

                <button onclick="openModal()" class="bg-teal-600 dark:bg-[#5EEAD4] hover:bg-teal-700 dark:hover:bg-teal-400 text-white dark:text-teal-950 font-bold px-5 py-3 rounded-full transition flex items-center gap-2 shadow-sm font-heading">
                    <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Ruangan
                </button>

                @include('user.admin_unit.modal-tambah-ruang')
            </div>
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @php
                $rooms = [
                    ['title' => 'Gedung Sipil', 'subtitle' => 'Lantai 3 • Ruang 304', 'capacity' => '45 Orang', 'estimate' => 'Estimasi Peserta', 'facilities' => ['Microphone', 'Sound', 'Proyektor']],
                    ['title' => 'Gedung Sipil', 'subtitle' => 'Lantai 3 • Ruang 305', 'capacity' => '40 Orang', 'estimate' => 'Estimasi Peserta', 'facilities' => ['Microphone', 'Sound', 'Proyektor']],
                    ['title' => 'Gedung Sipil', 'subtitle' => 'Lantai 2 • Ruang 210', 'capacity' => '35 Orang', 'estimate' => 'Estimasi Peserta', 'facilities' => ['Microphone', 'Sound', 'Proyektor']],
                    ['title' => 'Gedung Sipil', 'subtitle' => 'Lantai 1 • Ruang 110', 'capacity' => '50 Orang', 'estimate' => 'Estimasi Peserta', 'facilities' => ['Microphone', 'Sound', 'Proyektor']],
                    ['title' => 'Gedung Sipil', 'subtitle' => 'Lantai 4 • Ruang 401', 'capacity' => '30 Orang', 'estimate' => 'Estimasi Peserta', 'facilities' => ['Microphone', 'Sound', 'Proyektor']],
                    ['title' => 'Gedung Sipil', 'subtitle' => 'Lantai 4 • Ruang 402', 'capacity' => '20 Orang', 'estimate' => 'Estimasi Peserta', 'facilities' => ['Microphone', 'Sound', 'Proyektor']],
                ];
            @endphp

            @foreach ($rooms as $room)
                <article class="group relative overflow-hidden rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card shadow-sm p-6 transition hover:border-teal-400 dark:hover:border-kinetic-primary/50">
                    <div class="absolute inset-x-0 top-0 h-36 bg-cover bg-center opacity-0 sm:opacity-100" style="background-image: url('https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80');"></div>
                    <div class="relative pt-36 space-y-5">
                        <div class="space-y-2">
                            <p class="text-xs uppercase tracking-[0.28em] text-teal-600 dark:text-kinetic-primary font-bold">{{ $room['subtitle'] }}</p>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $room['title'] }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $room['subtitle'] }}</p>
                        </div>

                        <div class="grid gap-3 text-sm">
                            <div class="flex items-center justify-between rounded-2xl bg-slate-900/80 border border-slate-800/80 px-4 py-3">
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-[0.24em]">Kapasitas</p>
                                    <p class="font-semibold text-white">{{ $room['capacity'] }}</p>
                                </div>
                                <span class="text-[11px] font-semibold uppercase tracking-[0.24em] text-teal-500">{{ $room['estimate'] }}</span>
                            </div>

                            <div class="space-y-2">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Fasilitas</p>
                                <div class="grid gap-2">
                                    @foreach ($room['facilities'] as $facility)
                                        <div class="inline-flex items-center gap-2 rounded-2xl bg-slate-900/80 px-3 py-2 text-sm text-slate-300">
                                            <i class="ph-bold ph-check-circle text-teal-400"></i>
                                            {{ $facility }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="button" class="inline-flex items-center justify-center rounded-full border border-teal-500/20 bg-teal-500/10 px-4 py-2 text-sm font-semibold text-teal-200 transition hover:bg-teal-500/20">Edit</button>
                        </div>
                    </div>
                </article>
            @endforeach

            <button type="button" class="flex min-h-[350px] flex-col items-center justify-center gap-3 rounded-[2rem] border-2 border-dashed border-slate-200/70 bg-white/30 dark:border-kinetic-border/70 dark:bg-slate-900/40 text-slate-500 transition hover:border-teal-400 hover:text-teal-600 dark:hover:border-kinetic-primary/60 dark:hover:text-kinetic-primary">
                <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-teal-50 text-3xl text-teal-600 dark:bg-kinetic-primary/10 dark:text-kinetic-primary">
                    <i class="ph-bold ph-plus"></i>
                </span>
                <span class="text-sm font-semibold">Tambah Ruangan</span>
            </button>
        </div>
    </div>
</x-app-layout>

