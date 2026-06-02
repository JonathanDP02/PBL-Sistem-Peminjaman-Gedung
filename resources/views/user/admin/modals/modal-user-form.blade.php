    <div id="modalUserForm" class="hidden fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-lg relative shadow-2xl transform scale-95 transition-transform duration-300 max-h-[85vh] flex flex-col overflow-hidden">

            <button onclick="closeUserModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors z-50">
                <i class="ph-bold ph-x text-xl"></i>
            </button>

            <form id="userForm" class="flex flex-col max-h-[85vh]">
                @csrf
                <input type="hidden" id="userId" name="user_id" value="">

                <!-- Header (Fixed) -->
                <div class="p-8 pb-4">
                    <h3 id="modalTitle" class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-1">Tambah User Baru</h3>
                    <p id="modalSubtitle" class="text-xs text-slate-500 dark:text-gray-400">Isi form di bawah untuk menambah user baru.</p>
                </div>

                <!-- Body (Scrollable with custom thin scrollbar) -->
                <div class="flex-1 overflow-y-auto px-8 py-2 space-y-5 max-h-[50vh] [scrollbar-width:thin] [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-track]:bg-transparent [&::-webkit-scrollbar-thumb]:bg-slate-200 dark:[&::-webkit-scrollbar-thumb]:bg-[#2A2A2A] [&::-webkit-scrollbar-thumb]:rounded-full">
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
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Jabatan <span class="text-slate-400 text-[8px]">(Opsional)</span></label>
                            <button type="button" id="btnTogglePositionMode" onclick="togglePositionMode()" class="text-[9px] font-bold text-teal-600 dark:text-kinetic-primary hover:underline focus:outline-none">+ Buat Baru</button>
                        </div>
                        <div id="containerPositionSelect">
                            <select id="inputPosition" name="position_id" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none">
                                <option value="">Pilih Jabatan</option>
                            </select>
                        </div>
                        <div id="containerPositionInput" class="hidden">
                            <input type="text" id="inputPositionCustom" placeholder="Ketik nama jabatan baru..." style="text-transform: capitalize;" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors">
                        </div>
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
                </div>

                <!-- Footer (Fixed) -->
                <div class="p-8 pt-4 border-t border-slate-200 dark:border-[#2A2A2A] bg-white dark:bg-[#151515] flex gap-3">
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