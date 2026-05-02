<div id="modalTambahRuang" class="hidden fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    
    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-lg p-8 relative shadow-2xl transform scale-95 transition-transform duration-300">
        
        <button onclick="closeModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
            <i class="ph-bold ph-x text-xl"></i>
        </button>

        <div class="mb-6">
            <h3 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-1">Tambah Ruangan Baru</h3>
            <p class="text-xs text-slate-500 dark:text-gray-400">Masukkan detail fasilitas dan kapasitas ruangan.</p>
        </div>

        <!-- 1. Tambahkan action URL dan method POST -->
        <form action="/admin_unit/rooms" method="POST" class="space-y-5">
            
            <!-- 2. Wajib tambahkan CSRF token untuk keamanan Laravel -->
            @csrf

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nama Ruangan</label>
                <!-- 3. Tambahkan name="room_name" dan required -->
                <input type="text" name="room_name" placeholder="Contoh: Lab Komputer B" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Kapasitas</label>
                    <div class="relative">
                        <!-- 4. Tambahkan name="capacity" dan required -->
                        <input type="number" name="capacity" placeholder="40" min="0" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-4 pr-14 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors" required>
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-slate-500">Orang</span>
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Lokasi Gedung</label>
                    <!-- 5. Tambahkan name="building_id" dan loop data gedung dari database -->
                    <select name="building_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none" required>
                        <option value="">Pilih Gedung</option>
                        @foreach(App\Models\Building::all() as $building)
                            <option value="{{ $building->id }}">{{ $building->building_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Fasilitas (Pisahkan dengan koma)</label>
                <!-- 6. Tambahkan name="description" -->
                <input type="text" name="description" placeholder="Contoh: Proyektor, AC, Papan Tulis" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors">
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-[#2A2A2A] mt-6">
                <button type="button" onclick="closeModal()" class="w-1/3 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3.5 rounded-xl transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="w-2/3 bg-kinetic-primary hover:bg-teal-400 text-slate-900 font-bold py-3.5 rounded-xl transition-colors shadow-[0_0_15px_rgba(20,184,166,0.2)] text-sm">
                    Simpan Ruangan
                </button>
            </div>
        </form>
    </div>
</div>