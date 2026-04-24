    <div id="modalViewUser" class="hidden fixed inset-0 z-[98] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-md p-8 relative shadow-2xl transform scale-95 transition-transform duration-300">

            <button onclick="closeViewUserModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
                <i class="ph-bold ph-x text-xl"></i>
            </button>

            <div class="text-center mb-6">
                <div id="viewUserInitials" class="w-20 h-20 mx-auto rounded-full bg-teal-50 dark:bg-[#0D2A27] text-teal-600 dark:text-kinetic-primary flex items-center justify-center text-3xl font-bold border border-teal-100 dark:border-teal-900/50 mb-4">
                    -
                </div>
                <h3 id="viewUserName" class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-1">-</h3>
                <p id="viewUserEmail" class="text-sm text-slate-500 dark:text-gray-400">-</p>
                <div class="mt-3 flex justify-center gap-2">
                    <span id="viewUserRole" class="px-3 py-1 bg-slate-100 dark:bg-[#222] text-slate-600 dark:text-gray-300 rounded-full text-xs font-medium border border-slate-200 dark:border-[#333]">-</span>
                </div>
            </div>

            <div class="space-y-4 bg-slate-50 dark:bg-[#111] p-5 rounded-2xl border border-slate-100 dark:border-[#222]">
                <div class="flex flex-col gap-1">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Unit</p>
                    <p id="viewUserUnit" class="text-sm font-medium text-slate-800 dark:text-gray-200">-</p>
                </div>
                <div class="flex flex-col gap-1">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Level Unit</p>
                    <p id="viewUserLevel" class="text-sm font-medium text-slate-800 dark:text-gray-200">-</p>
                </div>
                <div class="flex flex-col gap-1">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Jabatan</p>
                    <p id="viewUserPosition" class="text-sm font-medium text-slate-800 dark:text-gray-200">-</p>
                </div>
            </div>
            
            <div class="mt-6 flex justify-center">
                <button onclick="closeViewUserModal()" class="w-full bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3 rounded-xl transition-colors text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>