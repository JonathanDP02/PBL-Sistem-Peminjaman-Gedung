    <div id="modalUserForm" class="hidden fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-lg p-8 relative shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] overflow-y-auto">

            <button onclick="closeUserModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors z-50">
                <i class="ph-bold ph-x text-xl"></i>
            </button>

            <div class="mb-6">
                <h3 id="modalTitle" class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-1">Tambah User Baru</h3>
                <p id="modalSubtitle" class="text-xs text-slate-500 dark:text-gray-400">Isi form di bawah untuk menambah user baru.</p>
            </div>

            <form id="userForm" class="space-y-5">
                @csrf
                <input type="hidden" id="userId" name="user_id" value="">

                <!-- Name -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" id="inputName" name="name" placeholder="Nama Lengkap" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors" required>
                    <p id="errorName" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Email</label>
                    <input type="email" id="inputEmail" name="email" placeholder="email@example.com" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors" required>
                    <p id="errorEmail" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Password <span id="passwordNote" class="text-slate-400 text-[8px]">(Isi jika ingin mengubah)</span></label>
                    <input type="password" id="inputPassword" name="password" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors">
                    <p id="errorPassword" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Unit</label>
                    <select id="inputUnit" name="unit_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none" required>
                        <option value="" disabled selected>Pilih Unit</option>
                    </select>
                    <p id="errorUnit" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Position -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Jabatan <span class="text-slate-400 text-[8px]">(Opsional)</span></label>
                    <select id="inputPosition" name="position_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none">
                        <option value="">Pilih Jabatan</option>
                    </select>
                    <p id="errorPosition" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Role</label>
                    <select id="inputRole" name="role_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none" required>
                        <option value="" disabled selected>Pilih Role</option>
                    </select>
                    <p id="errorRole" class="text-xs text-red-500 mt-1 hidden"></p>
                </div>

                <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-[#2A2A2A] mt-6">
                    <button type="button" onclick="closeUserModal()" class="w-1/3 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3.5 rounded-xl transition-colors text-sm">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn" class="w-2/3 bg-kinetic-primary hover:bg-teal-400 text-slate-900 font-bold py-3.5 rounded-xl transition-colors shadow-[0_0_15px_rgba(20,184,166,0.2)] text-sm">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>