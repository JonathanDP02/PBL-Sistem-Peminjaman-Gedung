    <div id="modalDeleteConfirm" class="hidden fixed inset-0 z-[98] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-sm p-8 relative shadow-2xl transform scale-95 transition-transform duration-300">

            <button onclick="closeDeleteConfirm()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
                <i class="ph-bold ph-x text-xl"></i>
            </button>

            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-[#2A1515] flex items-center justify-center mx-auto mb-4">
                    <i class="ph-bold ph-warning text-3xl text-red-500"></i>
                </div>
                <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-2">Hapus User?</h3>
                <p class="text-sm text-slate-500 dark:text-gray-400">
                    Anda yakin ingin menghapus user <span id="deleteUserName" class="font-bold text-slate-900 dark:text-white"></span>? Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteConfirm()" class="w-1/2 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3 rounded-xl transition-colors text-sm">
                    Batal
                </button>
                <button type="button" onclick="confirmDelete()" id="confirmDeleteBtn" class="w-1/2 bg-red-500 hover:bg-red-600 text-white font-bold py-3 rounded-xl transition-colors text-sm">
                    Hapus User
                </button>
            </div>
        </div>
    </div>