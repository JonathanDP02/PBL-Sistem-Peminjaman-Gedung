<div id="modalDeleteRuang" class="hidden fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
    <div class="bg-white dark:bg-[#151515] border border-red-200 dark:border-red-900/30 rounded-3xl w-full max-w-sm p-8 relative shadow-2xl transform scale-95 transition-transform duration-300">
        
        <button onclick="closeDeleteModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
            <i class="ph-bold ph-x text-xl"></i>
        </button>

        <div class="mb-6 text-center">
            <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-red-50 dark:bg-red-900/20 mb-4">
                <i class="ph-bold ph-warning text-3xl text-red-600 dark:text-red-400"></i>
            </div>
            <h3 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-2">Hapus Ruangan?</h3>
            <!-- ID ini akan diisi oleh JS -->
            <p class="text-sm text-slate-500 dark:text-gray-400">Anda yakin ingin menghapus ruangan <span class="font-semibold text-red-600 dark:text-red-400" id="deleteRoomName"></span>?</p>
            <p class="text-xs text-slate-400 dark:text-gray-500 mt-3">Tindakan ini tidak dapat dibatalkan. Pastikan tidak ada peminjaman aktif untuk ruangan ini.</p>
        </div>

        <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-[#2A2A2A]">
            <button type="button" onclick="closeDeleteModal()" class="w-1/2 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3.5 rounded-xl transition-colors text-sm">
                Batal
            </button>
            <button type="button" onclick="deleteRoom()" class="w-1/2 bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-[0_0_15px_rgba(220,38,38,0.2)] text-sm">
                Hapus Ruangan
            </button>
        </div>
    </div>
</div>