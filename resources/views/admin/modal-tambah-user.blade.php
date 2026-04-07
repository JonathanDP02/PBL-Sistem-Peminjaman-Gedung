<div id="modalTambahUser" class="hidden fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">

    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-lg p-8 relative shadow-2xl transform scale-95 transition-transform duration-300">

        <button onclick="closeUserModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
            <i class="ph-bold ph-x text-xl"></i>
        </button>

        <div class="mb-6">
            <h3 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-1">Tambah Pengguna Baru</h3>
            <p class="text-xs text-slate-500 dark:text-gray-400">Daftarkan pengguna baru ke sistem.</p>
        </div>

        <form action="{{ route('tambah-user.store') }}" method="POST" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors @error('name') border-red-500 @enderror" required>
                @error('name')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Email</label>
                <input type="email" name="email" placeholder="email@example.com" value="{{ old('email') }}" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors @error('email') border-red-500 @enderror" required>
                @error('email')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Password</label>
                <input type="password" name="password" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors @error('password') border-red-500 @enderror" required>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Unit -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Unit</label>
                <select name="unit_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none @error('unit_id') border-red-500 @enderror" required>
                    <option value="" disabled selected>Pilih Unit</option>
                    @foreach(\App\Models\Unit::all() as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                    @endforeach
                </select>
                @error('unit_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Position -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Jabatan</label>
                <select name="position_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none @error('position_id') border-red-500 @enderror" required>
                    <option value="" disabled selected>Pilih Jabatan</option>
                    @foreach(\App\Models\Position::all() as $position)
                        <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                    @endforeach
                </select>
                @error('position_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Role -->
            <div>
                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Role</label>
                <select name="role_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none @error('role_id') border-red-500 @enderror" required>
                    <option value="" disabled selected>Pilih Role</option>
                    @foreach(\App\Models\Role::all() as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-[#2A2A2A] mt-6">
                <button type="button" onclick="closeUserModal()" class="w-1/3 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3.5 rounded-xl transition-colors text-sm">
                    Batal
                </button>
                <button type="submit" class="w-2/3 bg-kinetic-primary hover:bg-teal-400 text-slate-900 font-bold py-3.5 rounded-xl transition-colors shadow-[0_0_15px_rgba(20,184,166,0.2)] text-sm">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openUserModal() {
    const modal = document.getElementById('modalTambahUser');
    modal.classList.remove('hidden');
    setTimeout(() => modal.classList.remove('opacity-0'), 10);
}

function closeUserModal() {
    const modal = document.getElementById('modalTambahUser');
    modal.classList.add('opacity-0');
    setTimeout(() => modal.classList.add('hidden'), 300);
}
</script>
