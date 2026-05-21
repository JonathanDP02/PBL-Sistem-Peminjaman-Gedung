<x-app-layout>
    <div class="bg-slate-50 dark:bg-[#0f0f0f] min-h-screen py-12 text-slate-800 dark:text-[#e5e5e5] font-sans transition-colors duration-300 relative">
        
        @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
            <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-xl text-sm font-bold flex items-center gap-2">
                <i class="ph-fill ph-check-circle text-lg"></i>
                {{ session('success') }}
            </div>
        </div>
        @endif

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-start gap-6 mb-8">
                <div>
                    <p class="text-teal-600 dark:text-[#2dd4bf] text-xs font-bold tracking-widest uppercase mb-2">Inventaris & Fasilitas</p>
                    <h1 class="text-4xl font-bold text-slate-900 dark:text-white leading-tight mb-3">Kelola Fasilitas Ruangan</h1>
                    <p class="text-slate-500 dark:text-gray-400 text-sm max-w-2xl">Pantau ketersediaan fasilitas, kondisi barang, dan kelengkapan ruang agar setiap booking berjalan lancar.</p>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch gap-3">
                    <button onclick="document.getElementById('modalTambah').classList.remove('hidden')" type="button" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-teal-600 px-6 py-3 text-sm font-semibold text-white transition hover:bg-teal-700">
                        <i class="ph-bold ph-plus"></i>
                        Tambah Fasilitas
                    </button>
                    
                    <form action="{{ route('fasilitas') }}" method="GET" class="relative inline-flex items-center">
                        <i class="ph-bold ph-faders-horizontal absolute left-4 text-slate-400 pointer-events-none z-10"></i>
                        
                        <select name="status" onchange="this.form.submit()" 
                            class="pl-11 pr-10 py-3 bg-white dark:bg-[#151515] border border-slate-300 dark:border-[#2A2A2A] rounded-2xl text-sm font-semibold text-slate-800 dark:text-white appearance-none outline-none focus:border-teal-500 transition-colors cursor-pointer w-full sm:w-44">
                            <option value="">Semua Status</option>
                            <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Dipakai" {{ request('status') == 'Dipakai' ? 'selected' : '' }}>Dipakai</option>
                            <option value="Maintenance" {{ request('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                            <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                        </select>
                        
                        <i class="ph-bold ph-caret-down absolute right-4 text-slate-400 pointer-events-none z-10"></i>
                    </form>

                    @if(request('status'))
                        <a href="{{ route('fasilitas') }}" class="flex items-center justify-center text-xs font-bold text-red-500 hover:text-red-600 transition-colors px-2">
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-[1.8fr_1fr] gap-6">
                <div class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                        <div class="rounded-3xl border border-slate-200 dark:border-[#2A2A2A] bg-white dark:bg-[#151515] p-6 shadow-sm dark:shadow-none">
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400 dark:text-gray-500 mb-4">Total Unit</p>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $totalFasilitas ?? 0 }}</h2>
                        </div>
                        <div class="rounded-3xl border border-slate-200 dark:border-[#2A2A2A] bg-white dark:bg-[#151515] p-6 shadow-sm dark:shadow-none">
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400 dark:text-gray-500 mb-4">Kategori</p>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">{{ $kategoriCount ?? 0 }}</h2>
                        </div>
                        <div class="rounded-3xl border border-slate-200 dark:border-[#2A2A2A] bg-white dark:bg-[#151515] p-6 shadow-sm dark:shadow-none">
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400 dark:text-gray-500 mb-4">Tersedia</p>
                            <h2 class="text-3xl font-bold text-teal-600 dark:text-[#2dd4bf]">{{ $tersedia ?? 0 }}</h2>
                        </div>
                        <div class="rounded-3xl border border-slate-200 dark:border-[#2A2A2A] bg-white dark:bg-[#151515] p-6 shadow-sm dark:shadow-none">
                            <p class="text-[10px] font-bold uppercase tracking-[0.28em] text-slate-400 dark:text-gray-500 mb-4">Perlu Servis</p>
                            <h2 class="text-3xl font-bold text-red-500">{{ $maintenance ?? 0 }}</h2>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 dark:border-[#2A2A2A] bg-white dark:bg-[#151515] shadow-sm dark:shadow-none overflow-hidden">
                        <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Daftar Fasilitas</h2>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left text-sm text-slate-600 dark:text-slate-300">
                                <thead class="border-b border-slate-200 dark:border-[#2A2A2A] bg-slate-50 dark:bg-[#111111] text-slate-500">
                                    <tr>
                                        <th class="px-6 py-4 font-semibold">Fasilitas</th>
                                        <th class="px-6 py-4 font-semibold">Kategori</th>
                                        <th class="px-6 py-4 font-semibold">Lokasi Ruang</th>
                                        <th class="px-6 py-4 font-semibold text-center">Jumlah</th>
                                        <th class="px-6 py-4 font-semibold">Status</th>
                                        <th class="px-6 py-4 font-semibold">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-[#2A2A2A]">
                                    @forelse($facilities as $item)
                                        <tr class="hover:bg-slate-50 dark:hover:bg-[#111111] transition-colors">
                                            <td class="px-6 py-4 font-bold text-slate-900 dark:text-white">{{ $item->name }}</td>
                                            <td class="px-6 py-4">{{ $item->category }}</td>
                                            <td class="px-6 py-4">{{ $item->room->room_name ?? '-' }}</td>
                                            <td class="px-6 py-4 text-center">{{ $item->quantity }} unit</td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $bg = 'bg-slate-100 text-slate-700';
                                                    if($item->status == 'Tersedia') $bg = 'bg-emerald-50 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400';
                                                    elseif(in_array($item->status, ['Maintenance', 'Rusak'])) $bg = 'bg-red-50 text-red-700 dark:bg-red-500/10 dark:text-red-400';
                                                    elseif($item->status == 'Dipakai') $bg = 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400';
                                                @endphp
                                                <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-widest {{ $bg }}">
                                                    {{ $item->status }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <button class="rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-100 dark:border-[#333] dark:bg-[#222] dark:text-white">Edit</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-6 py-8 text-center text-slate-500 italic">Belum ada data fasilitas tersimpan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <aside class="space-y-6">
                    <div class="rounded-[2rem] border border-slate-200 dark:border-[#2A2A2A] bg-white dark:bg-[#151515] shadow-sm p-6">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Status Kategori</h2>
                        <div class="mt-6 space-y-4">
                            @php $colors = ['bg-cyan-500/10 text-cyan-600', 'bg-emerald-500/10 text-emerald-600', 'bg-indigo-500/10 text-indigo-600', 'bg-amber-500/10 text-amber-600']; @endphp
                            @forelse($kategoriStats as $index => $stat)
                                <div class="rounded-3xl bg-slate-50 dark:bg-[#111111] p-4 border border-slate-200 dark:border-[#2A2A2A]">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ $stat->category }}</p>
                                            <p class="text-xs text-slate-500 mt-1">{{ $stat->total }} unit total</p>
                                        </div>
                                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $colors[$index % 4] }}">Aktif</span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-slate-500 italic">Belum ada kategori terdaftar.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-slate-200 dark:border-[#2A2A2A] bg-slate-50 dark:bg-[#111111] p-6 shadow-sm">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <i class="ph-fill ph-warning-circle text-amber-500"></i> Perhatian
                        </h2>
                        <ul class="mt-4 space-y-4 text-sm text-slate-500 dark:text-gray-400">
                            @forelse($perhatian as $p)
                                <li class="flex gap-3">
                                    <span class="mt-1.5 inline-flex h-2 w-2 rounded-full shrink-0 {{ $p->status == 'Rusak' ? 'bg-red-500' : 'bg-amber-500' }}"></span>
                                    <span>{{ $p->quantity }} {{ $p->name }} di {{ $p->room->room_name ?? '-' }} statusnya <strong class="{{ $p->status == 'Rusak' ? 'text-red-500' : 'text-amber-500' }}">{{ $p->status }}</strong>.</span>
                                </li>
                            @empty
                                <li class="text-emerald-500 italic text-xs"><i class="ph-bold ph-check"></i> Semua fasilitas dalam kondisi baik!</li>
                            @endforelse
                        </ul>
                    </div>
                </aside>
            </div>
        </div>

        <div id="modalTambah" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center">
            <div class="bg-white dark:bg-[#151515] rounded-3xl border border-slate-200 dark:border-[#2A2A2A] w-full max-w-lg p-6 md:p-8 relative shadow-2xl">
                <button onclick="document.getElementById('modalTambah').classList.add('hidden')" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 dark:hover:text-white">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Tambah Inventaris Baru</h3>
                
                <form action="{{ route('fasilitas.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Barang</label>
                        <input type="text" name="name" required class="w-full rounded-xl bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#333] px-4 py-3 text-sm dark:text-white">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kategori</label>
                            <input type="text" name="category" placeholder="Contoh: IT, Audio..." required class="w-full rounded-xl bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#333] px-4 py-3 text-sm dark:text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Jumlah Unit</label>
                            <input type="number" name="quantity" min="1" value="1" required class="w-full rounded-xl bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#333] px-4 py-3 text-sm dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Lokasi Ruangan</label>
                        <select name="room_id" required class="w-full rounded-xl bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#333] px-4 py-3 text-sm dark:text-white">
                            <option value="">-- Pilih Ruangan --</option>
                            @foreach($rooms as $r)
                                <option value="{{ $r->id }}">{{ $r->room_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Status Kondisi</label>
                        <select name="status" required class="w-full rounded-xl bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#333] px-4 py-3 text-sm dark:text-white">
                            <option value="Tersedia">Tersedia (Siap Pakai)</option>
                            <option value="Dipakai">Sedang Dipakai</option>
                            <option value="Maintenance">Maintenance / Servis</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>
                    <div class="pt-4 mt-4 border-t border-slate-100 dark:border-[#2A2A2A] flex justify-end">
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-6 rounded-xl transition-colors">Simpan Fasilitas</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>