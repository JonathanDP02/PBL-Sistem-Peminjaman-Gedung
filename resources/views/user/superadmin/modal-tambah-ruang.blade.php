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
        <form action="/superadmin/rooms" method="POST" enctype="multipart/form-data" class="space-y-5">
            
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Pemilik (Unit)</label>
                    <select name="unit_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none" required>
                        <option value="">Pilih Unit</option>
                        @foreach(App\Models\Unit::whereIn('level', ['Pusat', 'Jurusan'])->orderBy('unit_name', 'asc')->get() as $unit)
                            <option value="{{ $unit->id }}">{{ $unit->unit_name }} ({{ $unit->level }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Alur Persetujuan (SOP)</label>
                    <select name="workflow_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none">
                        <option value="">-- Tidak Ada Alur (Ruangan Tidak Dapat Dipinjam) --</option>
                        @foreach(App\Models\Workflow::with('unit')->get() as $workflow)
                            <option value="{{ $workflow->id }}">{{ $workflow->unit->unit_name ?? 'Global' }} - {{ $workflow->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Deskripsi Ruangan</label>
                <input type="text" name="description" placeholder="Contoh: Ruang kelas khusus pascasarjana" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors">
            </div>

            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Gambar Ruangan</label>
                <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-2.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors">
            </div>

            <div class="pt-2">
                <div class="flex justify-between items-center mb-2">
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Detail Fasilitas (Inventaris)</label>
                    <button type="button" onclick="addFacilityRow('tambah')" class="text-xs font-bold text-teal-600 dark:text-teal-400 hover:underline flex items-center gap-1">
                        <i class="ph-bold ph-plus"></i> Tambah Item
                    </button>
                </div>
                
                <div id="container-fasilitas-tambah" class="space-y-3 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                    </div>
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