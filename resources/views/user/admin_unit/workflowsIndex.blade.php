<x-app-layout title="Workflow Builder">
    @php
        $workflowId = request()->input('id');
    @endphp
    
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div x-data="workflowBuilder()" x-init="initBuilder()"
        class="bg-slate-50 dark:bg-kinetic-bg min-h-screen py-8 text-slate-800 dark:text-gray-200 font-sans transition-colors duration-300">
        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('workflowsBuilder') }}" class="text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-300 transition-colors">Manajemen Workflow</a>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 dark:text-gray-600"><polyline points="9 18 15 12 9 6"/></svg>
                <span class="text-teal-600 dark:text-teal-400 font-semibold" x-text="workflow ? workflow.name : 'Memuat...'"></span>
            </div>

            <template x-if="loading">
                <div class="py-20 flex flex-col justify-center items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-teal-500 mb-4"></div>
                    <p class="text-slate-500">Memuat konfigurasi alur...</p>
                </div>
            </template>

            <template x-if="!loading && workflow">
                <div>
                    <div class="mb-8">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2" x-text="workflow.name"></h1>
                                <p class="text-slate-600 dark:text-gray-400 text-sm" x-text="workflow.description || 'Konfigurasi syarat dan urutan persetujuan.'"></p>
                            </div>
                            <button @click="activeTab === 'dokumen' ? addDocument() : addStep()" class="px-4 py-2 bg-teal-500 dark:bg-teal-500 text-white rounded-lg font-semibold hover:bg-teal-600 dark:hover:bg-teal-400 transition-colors flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                <span x-text="activeTab === 'dokumen' ? 'Tambah Syarat' : 'Tambah Tahapan'"></span>
                            </button>
                        </div>
                    </div>

                    <div class="flex border-b border-slate-200 dark:border-kinetic-border mb-8 bg-white dark:bg-kinetic-card rounded-t-xl overflow-hidden">
                        <button @click="activeTab = 'dokumen'" :class="activeTab === 'dokumen' ? 'border-b-2 border-teal-500 text-teal-600 bg-slate-50 dark:bg-kinetic-surface' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="flex-1 px-6 py-4 font-semibold text-sm transition-colors">
                            Syarat Dokumen (<span x-text="documents.length"></span>)
                        </button>
                        <button @click="activeTab = 'alur'" :class="activeTab === 'alur' ? 'border-b-2 border-teal-500 text-teal-600 bg-slate-50 dark:bg-kinetic-surface' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'" class="flex-1 px-6 py-4 font-semibold text-sm transition-colors">
                            Alur Persetujuan (<span x-text="steps.length"></span>)
                        </button>
                    </div>

                    <div x-show="activeTab === 'dokumen'" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <template x-for="(doc, index) in documents" :key="doc.tempId || doc.id">
                                <div class="bg-white dark:bg-kinetic-card border border-slate-200 dark:border-kinetic-border rounded-xl p-6 hover:shadow-lg transition-all group relative">
                                    
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-teal-400 to-teal-600 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                        </div>
                                        <button type="button" @click="removeDocument(index)" class="text-slate-400 hover:text-red-500 p-2 rounded-lg hover:bg-slate-100 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </div>

                                    <input type="text" x-model="doc.document_name" placeholder="Nama Dokumen Wajib" class="w-full text-xl font-bold text-slate-900 dark:text-white mb-3 bg-transparent border-b border-dashed border-slate-300 focus:border-teal-500 focus:ring-0 p-0 pb-1">
                                    
                                    <textarea x-model="doc.description" placeholder="Deskripsi atau instruksi untuk mahasiswa..." class="w-full text-sm text-slate-600 dark:text-gray-400 bg-transparent border border-slate-100 rounded focus:ring-1 focus:ring-teal-500 p-2 mb-4 resize-none h-24"></textarea>

                                    <div class="mt-2 pt-4 border-t border-slate-200 dark:border-kinetic-border flex items-center justify-between">
                                        <span x-text="doc.is_mandatory ? 'Wajib Dilampirkan' : 'Opsional (Bila Ada)'" 
                                              :class="doc.is_mandatory ? 'text-teal-600' : 'text-slate-500'" 
                                              class="text-xs font-bold uppercase tracking-widest"></span>
                                        
                                        <button type="button" @click="doc.is_mandatory = !doc.is_mandatory" 
                                                :class="doc.is_mandatory ? 'bg-teal-500' : 'bg-slate-300'" 
                                                class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors">
                                            <span :class="doc.is_mandatory ? 'translate-x-2' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white transition duration-200"></span>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div @click="addDocument()" class="bg-white dark:bg-kinetic-card border-2 border-dashed border-slate-300 rounded-xl p-6 hover:border-teal-500 hover:bg-teal-50 transition-all cursor-pointer flex flex-col items-center justify-center min-h-[300px]">
                                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mb-3 group-hover:bg-teal-100 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="text-slate-600"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                                </div>
                                <p class="text-sm font-semibold text-slate-600 text-center">Tambah Syarat Dokumen</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="activeTab === 'alur'" class="space-y-8">
                        <div class="relative max-w-4xl">
                            <div class="absolute left-7 top-12 bottom-0 w-0.5 bg-gradient-to-b from-teal-500 to-teal-500/30"></div>

                            <div class="space-y-6">
                                <template x-for="(step, index) in steps" :key="step.tempId || step.id">
                                    <div class="flex gap-6 relative group">
                                        <div class="flex flex-col items-center pt-2">
                                            <div class="w-4 h-4 bg-teal-500 rounded-full border-4 border-slate-50 shadow-lg shrink-0"></div>
                                        </div>

                                        <div class="flex-1 bg-white border border-slate-200 rounded-lg p-5 hover:border-teal-500 transition-colors shadow-sm">
                                            <div class="flex items-start justify-between mb-3">
                                                <div class="flex items-start gap-3 flex-1">
                                                    
                                                    <div class="flex-1">
                                                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Urutan <span x-text="index + 1"></span></p>
                                                        
                                                        <select x-model="step.position_id" class="text-lg font-bold text-slate-900 bg-transparent border-b border-dashed border-slate-300 focus:border-teal-500 focus:ring-0 p-0 pb-1 mb-3 w-full cursor-pointer">
                                                            <option value="" disabled>-- Pilih Jabatan / Pejabat --</option>
                                                            <template x-for="pos in availablePositions" :key="pos.id">
                                                                <option :value="pos.id" x-text="pos.name" :selected="step.position_id == pos.id"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div>

                                                <button type="button" @click="removeStep(index)" class="text-slate-400 hover:text-red-500 p-2 rounded transition-colors shrink-0" title="Hapus Tahap">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                                </button>
                                            </div>

                                            <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                                                <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">Wajib Upload Surat Balasan (Disposisi)</span>
                                                <button type="button" @click="step.requires_attachment = !step.requires_attachment" 
                                                        :class="step.requires_attachment ? 'bg-teal-500' : 'bg-slate-300'" 
                                                        class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors">
                                                    <span :class="step.requires_attachment ? 'translate-x-2' : 'translate-x-0'" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white transition duration-200"></span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <button type="button" @click="addStep()" class="flex items-center gap-2 px-4 py-2.5 bg-slate-900 text-white rounded-lg font-semibold hover:bg-slate-800 transition-colors ml-12">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                            Tambah Pejabat Persetujuan
                        </button>
                    </div>

                    <div class="flex justify-between items-center mt-12 pt-6 border-t border-slate-200">
                        <div class="text-sm text-slate-500">
                            Pastikan data sudah benar sebelum menyimpan.
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('workflowsBuilder') }}" class="px-6 py-2.5 rounded-lg font-semibold text-slate-700 border border-slate-200 hover:bg-slate-50 transition-colors">
                                Batal
                            </a>
                            <button @click="saveAll()" :disabled="saving" class="px-8 py-2.5 rounded-lg font-semibold text-white bg-teal-500 hover:bg-teal-600 disabled:opacity-50 transition-colors flex items-center gap-2">
                                <svg x-show="!saving" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                <svg x-show="saving" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="saving ? 'Menyimpan...' : 'Simpan Semua Pengaturan'"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </template>

        </div>
    </div>

    <script>
        function workflowBuilder() {
            return {
                workflowId: @js($workflowId ? (int) $workflowId : null),
                workflowsBuilderUrl: @json(route('workflowsBuilder')),
                workflow: null,
                activeTab: 'dokumen',
                loading: true,
                saving: false,
                
                // Data Array
                documents: [],
                steps: [],
                availablePositions: [],

                getHeaders() {
                    return {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                    };
                },

                async initBuilder() {
                    if (!this.workflowId) {
                        alert('ID Workflow tidak ditemukan. Anda akan dikembalikan ke daftar.');
                        window.location.href = this.workflowsBuilderUrl;
                        return;
                    }

                    try {
                        // Tarik Data Induk Workflow & Master Positions
                        const [wfRes, posRes] = await Promise.all([
                            fetch(`/admin_unit/api/workflows/${this.workflowId}`, { headers: this.getHeaders() }),
                            // Asumsi Anda punya API untuk menarik jabatan
                            fetch('/admin_unit/api/positions', { headers: this.getHeaders() }) 
                        ]);

                        if (wfRes.ok) {
                            const data = await wfRes.json();
                            this.workflow = data;
                            // Extract relations
                            this.documents = (data.requirements || []).map((requirement) => ({
                                id: requirement.id,
                                document_name: requirement.document_name || '',
                                is_mandatory: !!requirement.is_mandatory,
                            }));
                            this.steps = data.steps || [];
                            
                            // Pastikan boolean tertangkap dengan benar dari database
                            this.steps.forEach(s => s.requires_attachment = !!s.requires_attachment);
                        }

                        if (posRes.ok) {
                            const posData = await posRes.json();
                            // Sesuaikan dengan format response API positions Anda
                            this.availablePositions = posData.data || posData; 
                        }

                    } catch (error) {
                        console.error('Error memuat data:', error);
                        alert('Terjadi kesalahan koneksi saat memuat alur.');
                    } finally {
                        this.loading = false;
                    }
                },

                addDocument() {
                    this.documents.push({ 
                        tempId: Date.now(), 
                        document_name: '',
                        is_mandatory: true
                    });
                },

                removeDocument(index) {
                    if(confirm('Hapus syarat dokumen ini?')) {
                        this.documents.splice(index, 1);
                    }
                },

                addStep() {
                    this.steps.push({ 
                        tempId: Date.now(), 
                        position_id: '', 
                        step_order: this.steps.length + 1,
                        requires_attachment: false // Default tidak wajib disposisi
                    });
                },

                removeStep(index) {
                    if(confirm('Hapus pejabat ini dari alur?')) {
                        this.steps.splice(index, 1);
                        // Re-order the remaining steps
                        this.steps.forEach((step, i) => {
                            step.step_order = i + 1;
                        });
                    }
                },

                async saveAll() {
                    const incompleteDocs = this.documents.filter((d) => !d.document_name.trim());
                    if (incompleteDocs.length > 0) return alert('Terdapat nama dokumen yang masih kosong. Harap isi atau hapus.');
                    
                    const incompleteSteps = this.steps.filter((s) => !s.position_id);
                    if (incompleteSteps.length > 0) return alert('Terdapat tahapan alur yang belum memilih Jabatan Pejabat.');

                    if (this.steps.length === 0) {
                        const proceed = confirm('Workflow ini belum memiliki tahapan persetujuan. Syarat dokumen tetap bisa disimpan, tetapi workflow belum dapat dipakai untuk pengajuan sampai minimal 1 tahapan ditambahkan. Lanjut simpan?');

                        if (!proceed) {
                            return;
                        }
                    }

                    try {
                        this.saving = true;

                        // Perbarui step_order sebelum kirim
                        this.steps.forEach((step, index) => {
                            step.step_order = index + 1;
                        });

                        const payload = {
                            requirements: this.documents.map((document) => ({
                                document_name: document.document_name,
                                is_mandatory: !!document.is_mandatory,
                            })),
                            steps: this.steps
                        };

                        // Panggil API Update (Anda perlu membuat controller method untuk menerima array ini)
                        const res = await fetch(`/admin_unit/api/workflows/${this.workflowId}/sync-details`, {
                            method: 'POST',
                            headers: this.getHeaders(),
                            body: JSON.stringify(payload)
                        });

                        if (res.ok) {
                            alert('Konfigurasi Workflow berhasil disimpan!');
                            window.location.href = this.workflowsBuilderUrl;
                        } else {
                            const err = await res.json();
                            alert(err.message || 'Gagal menyimpan konfigurasi.');
                        }
                    } catch (error) {
                        console.error('Save error:', error);
                        alert('Terjadi kesalahan pada sistem saat menyimpan.');
                    } finally {
                        this.saving = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>