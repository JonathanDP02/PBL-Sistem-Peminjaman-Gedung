<x-app-layout title="Daftar Workflow">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div x-data="workflowManager()" x-init="fetchWorkflows()"
        class="bg-slate-50 dark:bg-kinetic-bg min-h-screen py-8 text-slate-800 dark:text-gray-200 font-sans transition-colors duration-300">
        
        <div class="max-w-full px-8">
            
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Manajemen Alur Kerja</h1>
                        <p class="text-slate-600 dark:text-gray-400 text-sm">Kelola alur persetujuan umum dan syarat dokumen untuk unit Anda.</p>
                    </div>
                </div>
            </div>

            <template x-if="loading">
                <div class="py-12 flex justify-center items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-500"></div>
                </div>
            </template>

            <div x-show="!loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="workflow in filteredWorkflows" :key="workflow.id">
                    
                    <div @click="window.location.href = '/admin_unit/workflows-index?id=' + workflow.id" class="group block h-full relative cursor-pointer">
                        
                        <div class="bg-white dark:bg-kinetic-card rounded-xl border border-slate-200 dark:border-kinetic-border p-6 hover:shadow-lg hover:border-teal-500 dark:hover:border-teal-400 transition-all h-full flex flex-col overflow-hidden relative">
                            
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-teal-50 dark:bg-teal-900/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>

                            <div class="flex items-start justify-between mb-4 relative z-10">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-kinetic-surface text-teal-600 dark:text-teal-400 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                </div>
                                <div class="flex items-center justify-center bg-slate-100 dark:bg-kinetic-surface px-2 py-1 rounded-md gap-1 font-semibold text-slate-700 dark:text-gray-300 text-xs">
                                    <span x-text="workflow.steps_count ?? (workflow.steps ? workflow.steps.length : 0)"></span>
                                    <span>Tahap Persetujuan</span>
                                </div>
                                
                            </div>

                            <div class="flex-1 relative z-10">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors" x-text="workflow.name"></h3>
                                <p class="text-sm font-medium text-slate-500 dark:text-gray-400 mb-4" x-text="workflow.description || 'Tidak ada deskripsi'"></p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-kinetic-border flex items-center justify-end relative z-10">
                                <a @click.stop :href="'/admin_unit/workflows-index?id=' + workflow.id" class="w-full text-center text-teal-600 dark:text-white hover:text-white bg-slate-100 dark:bg-teal-900/20  hover:bg-teal-600 border dark:hover:bg-teal-600 border-white/20 font-semibold rounded-lg text-sm px-5 py-3.5 transition-all backdrop-blur-sm flex items-center justify-center gap-2">
                                    <span>Atur Alur Unit Saya</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </a>
                            </div>
                             <div class="flex items-center   gap-2 mt-4 text-xs text-slate-500 dark:text-gray-400 relative z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <span class="text-xs font-medium" x-text="'Diperbarui pada ' + formatDate(workflow.updated_at)"></span>
                            </div>
                        </div>
                    </div>
                </template>

                <div x-show="filteredWorkflows.length === 0 && !loading" class="col-span-full py-12 flex flex-col items-center justify-center text-slate-500 dark:text-gray-400">
                    <svg class="w-16 h-16 mb-4 text-slate-300 dark:text-kinetic-border" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p class="text-lg font-medium">Alur unit belum terkonfigurasi otomatis.</p>
                </div>
            </div>

            <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" x-cloak>
                <div @click.away="closeModal()" class="bg-white dark:bg-kinetic-card w-full max-w-md rounded-2xl shadow-2xl border border-slate-200 dark:border-kinetic-border overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-2xl font-bold mb-1 dark:text-white" x-text="isEditMode ? 'Update Data Workflow' : 'Buat Workflow Baru'"></h3>
                        <p class="text-sm text-slate-500 dark:text-gray-400 mb-6" x-text="isEditMode ? 'Ubah nama atau deskripsi dari workflow ini.' : 'Tentukan nama dan deskripsi alur persetujuan.'"></p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-300">Nama Workflow <span class="text-red-500">*</span></label>
                                <input x-model="formData.name" type="text" class="w-full px-4 py-2 border rounded-xl dark:bg-kinetic-surface dark:border-kinetic-border dark:text-white focus:ring-2 focus:ring-teal-500 outline-none transition-all" placeholder="Contoh: Alur Peminjaman Auditorium">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1 dark:text-gray-300">Deskripsi</label>
                                <textarea x-model="formData.description" class="w-full px-4 py-2 border rounded-xl dark:bg-kinetic-surface dark:border-kinetic-border dark:text-white focus:ring-2 focus:ring-teal-500 outline-none transition-all" rows="3" placeholder="Jelaskan kegunaan alur ini..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-slate-50 dark:bg-kinetic-surface flex justify-end gap-3 rounded-b-2xl border-t dark:border-kinetic-border">
                        <button @click="closeModal()" class="px-4 py-2 font-medium text-slate-600 hover:text-slate-800 dark:text-gray-400 transition-colors">Batal</button>
                        <button @click="submitForm()" :disabled="isSubmitting" class="px-6 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-xl disabled:opacity-50 font-bold transition-all shadow-sm">
                            <span x-text="isSubmitting ? 'Menyimpan...' : (isEditMode ? 'Simpan Perubahan' : 'Simpan & Lanjut')"></span>
                        </button>
                    </div>
                </div>
            <!-- Modern Custom Modal -->
            <x-modal-confirm />

        </div>
    </div>

    <script>
        function workflowManager() {
            return {
                search: '',
                workflows: [],
                loading: true,
                showModal: false,
                isSubmitting: false,
                
                isEditMode: false,
                editId: null,
                formData: { name: '', description: '' },
                
                // Modal state
                modal: {
                    show: false,
                    title: '',
                    description: '',
                    type: 'warning',
                    confirmText: 'Konfirmasi',
                    cancelText: 'Batal',
                    isConfirm: false,
                    onConfirm: null
                },

                showAlert(title, description, type = 'warning', onConfirm = null) {
                    this.modal = {
                        show: true,
                        title: title,
                        description: description,
                        type: type,
                        confirmText: 'Oke',
                        cancelText: 'Batal',
                        isConfirm: false,
                        onConfirm: onConfirm
                    };
                },

                showConfirm(title, description, onConfirm, type = 'danger', confirmText = 'Hapus', cancelText = 'Batal') {
                    this.modal = {
                        show: true,
                        title: title,
                        description: description,
                        type: type,
                        confirmText: confirmText,
                        cancelText: cancelText,
                        isConfirm: true,
                        onConfirm: onConfirm
                    };
                },

                closeModal() {
                    this.modal.show = false;
                },

                getHeaders() {
                    return {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    };
                },

                // Fungsi baru untuk mengubah format waktu Laravel ke format manusia
                formatDate(dateString) {
                    if (!dateString) return '-';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                },

                async fetchWorkflows() {
                    try {
                        this.loading = true;
                        const res = await fetch('/admin_unit/api/workflows', { headers: this.getHeaders() });
                        if(res.ok) {
                            this.workflows = await res.json();
                        }
                    } finally {
                        this.loading = false;
                    }
                },

                get filteredWorkflows() {
                    return this.workflows.filter(w => {
                        return w.name.toLowerCase().includes(this.search.toLowerCase());
                    });
                },

                openCreateModal() {
                    this.isEditMode = false;
                    this.editId = null;
                    this.formData = { name: '', description: '' };
                    this.showModal = true;
                },

                openEditModal(workflow) {
                    this.isEditMode = true;
                    this.editId = workflow.id;
                    this.formData = { name: workflow.name, description: workflow.description || '' };
                    this.showModal = true;
                },

                closeModalForm() {
                    this.showModal = false;
                },

                async submitForm() {
                    if (!this.formData.name) {
                        this.showAlert('Nama Alur Kosong', 'Nama alur workflow wajib diisi!', 'warning');
                        return;
                    }
                    
                    try {
                        this.isSubmitting = true;
                        const url = this.isEditMode ? `/admin_unit/api/workflows/${this.editId}` : '/admin_unit/api/workflows';
                        const method = this.isEditMode ? 'PUT' : 'POST';

                        const res = await fetch(url, {
                            method: method,
                            headers: this.getHeaders(),
                            body: JSON.stringify(this.formData)
                        });

                        if (res.ok) {
                            if (!this.isEditMode) {
                                const newWorkflow = await res.json();
                                window.location.href = '/admin_unit/workflows-index?id=' + newWorkflow.id;
                            } else {
                                await this.fetchWorkflows();
                                this.closeModalForm();
                            }
                        } else {
                            const errorData = await res.json();
                            this.showAlert('Gagal Menyimpan', errorData.message || 'Gagal menyimpan workflow. Periksa koneksi atau hak akses.', 'danger');
                        }
                    } catch (error) {
                        this.showAlert('Kesalahan Sistem', 'Terjadi kesalahan pada sistem.', 'danger');
                    } finally {
                        this.isSubmitting = false;
                    }
                },

                deleteWorkflow(id) {
                    this.showConfirm(
                        'Hapus Workflow?',
                        'PERINGATAN KERAS: Apakah Anda yakin ingin menghapus workflow ini? Seluruh rantai persetujuan dan syarat dokumen di dalamnya akan hangus permanen.',
                        async () => {
                            try {
                                const res = await fetch(`/admin_unit/api/workflows/${id}`, {
                                    method: 'DELETE',
                                    headers: this.getHeaders()
                                });

                                if (res.ok) {
                                    await this.fetchWorkflows();
                                } else {
                                    const errorData = await res.json();
                                    this.showAlert('Gagal Menghapus', errorData.message || 'Gagal menghapus workflow. Mungkin sedang digunakan oleh transaksi aktif.', 'danger');
                                }
                            } catch (error) {
                                this.showAlert('Kesalahan Sistem', 'Terjadi kesalahan pada sistem saat mencoba menghapus.', 'danger');
                            }
                        },
                        'danger',
                        'Hapus Alur'
                    );
                }
            }
        }
    </script>
</x-app-layout> 