<x-app-layout title="Workflow Builder">
    <div x-data="{
            activeTab: 'dokumen',
            jenisWorkflow: 'auditorium',
            availableApprovers: [
                'Ketua Himpunan', 
                'Dosen Pembimbing', 
                'Kepala Bagian Sarpras', 
                'Admin Kemahasiswaan', 
                'Wakil Dekan Bidang II', 
                'Rektorat', 
                'Ketua BEM', 
                'Pihak Keamanan / Satpam'
            ],
            steps: [
                { id: 1, role: 'Ketua Himpunan', description: 'Verifikasi internal organisasi mahasiswa.', type: 'wajib_lampiran_balik' },
                { id: 2, role: 'Dosen Pembimbing', description: 'Persetujuan akademis dan supervisi kegiatan.', type: 'wajib_lampiran_balik' },
                { id: 3, role: 'Kepala Bagian Sarpras', description: 'Konfirmasi jadwal dan ketersediaan fasilitas fisik.', type: 'wajib_lampiran_balik' }
            ],
            documents: [
                { id: 1, name: 'Kartu Tanda Mahasiswa (KTM)', description: 'Identitas pemohon yang masih berlaku. File harus dalam format PDF atau JPG dengan ukuran maksimal 2MB.', required: true },
                { id: 2, name: 'Proposal Kegiatan', description: 'Rincian rencana kegiatan, usunan acara, dan estimasi jumlah peserta. Dokumen wajib bertanda tangan penganggung jawab.', required: true },
                { id: 3, name: 'Surat Pengantar Instansi', description: 'Surat resmi dari instansi atau organisasi yang menaungi pemohon. Harus mencantumkan kop surat resmi.', required: true }
            ],
            addStep() {
                this.steps.push({ id: Date.now(), role: '', description: '', type: 'wajib_lampiran_balik' });
            },
            removeStep(index) {
                if (this.steps.length > 1) this.steps.splice(index, 1);
            },
            addDocument() {
                this.documents.push({ id: Date.now(), name: '', description: '', required: false });
            },
            removeDocument(index) {
                this.documents.splice(index, 1);
            }
        }" 
        class="bg-slate-50 dark:bg-kinetic-bg min-h-screen py-8 text-slate-800 dark:text-gray-200 font-sans transition-colors duration-300">
        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <div class="mb-6 flex items-center gap-2 text-sm">
                <a href="{{ route('workflowsBuilder') }}" class="text-slate-500 dark:text-gray-400 hover:text-slate-700 dark:hover:text-gray-300 transition-colors">Manajemen Workflow</a>
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 dark:text-gray-600"><polyline points="9 18 15 12 9 6"/></svg>
                <span class="text-teal-600 dark:text-teal-400 font-semibold">Alur Peminjaman Auditorium</span>
            </div>

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Alur Peminjaman Auditorium</h1>
                        <p class="text-slate-600 dark:text-gray-400 text-sm">Konfigurasi syarat dan urutan persetujuan untuk peminjaman fasilitas gedung pertemuan utama.</p>
                    </div>
                    <button class="px-4 py-2 bg-teal-500 dark:bg-teal-500 text-white rounded-lg font-semibold hover:bg-teal-600 dark:hover:bg-teal-400 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Tambah Syarat
                    </button>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="flex border-b border-slate-200 dark:border-kinetic-border mb-8 bg-white dark:bg-kinetic-card rounded-t-xl">
                <button @click="activeTab = 'dokumen'" :class="activeTab === 'dokumen' ? 'border-b-2 border-teal-500 text-teal-600 dark:text-teal-400' : 'text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-gray-300'" class="px-6 py-4 font-semibold text-sm transition-colors">
                    Syarat Dokumen
                </button>
                <button @click="activeTab = 'alur'" :class="activeTab === 'alur' ? 'border-b-2 border-teal-500 text-teal-600 dark:text-teal-400' : 'text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-gray-300'" class="px-6 py-4 font-semibold text-sm transition-colors">
                    Alur Persetujuan
                </button>
            </div>

            <!-- Tab Content: Dokumen -->
            <div x-show="activeTab === 'dokumen'" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <template x-for="(doc, index) in documents" :key="doc.id">
                        <div class="bg-white dark:bg-kinetic-card border border-slate-200 dark:border-kinetic-border rounded-xl p-6 hover:shadow-lg transition-all group">
                            <!-- Header dengan delete button -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-teal-400 to-teal-600 dark:from-teal-500 dark:to-teal-600 rounded-lg flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/><polyline points="13 2 13 9 20 9"/></svg>
                                </div>
                                <button type="button" @click="removeDocument(index)" class="text-slate-400 hover:text-red-500 dark:text-gray-600 dark:hover:text-red-400 transition-colors p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-kinetic-surface">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                </button>
                            </div>

                            <!-- Nama Dokumen -->
                            <input type="text" x-model="doc.name" :name="'documents['+index+'][name]'" placeholder="Nama dokumen" class="w-full text-xl font-bold text-slate-900 dark:text-white mb-3 bg-transparent border-none focus:ring-0 p-0 placeholder-slate-400">

                            <!-- Deskripsi -->
                            <textarea :name="'documents['+index+'][description]'" placeholder="Deskripsi dokumen..." class="w-full text-sm text-slate-600 dark:text-gray-400 bg-transparent border-none focus:ring-0 p-0 mb-4 placeholder-slate-400 resize-none" rows="2"></textarea>

                            <!-- Status Badge -->
                            <div class="mt-6 pt-4 border-t border-slate-200 dark:border-kinetic-border flex items-center justify-between">
                                <span x-show="doc.required" class="text-xs font-bold text-teal-600 dark:text-teal-400 uppercase tracking-widest">Wajib Dilampirkan</span>
                                <span x-show="!doc.required" class="text-xs font-bold text-slate-500 dark:text-gray-500 uppercase tracking-widest">Tambahan (Opsional)</span>
                                
                                <button type="button" @click="doc.required = !doc.required" class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors" :class="doc.required ? 'bg-teal-500 dark:bg-teal-600' : 'bg-slate-300 dark:bg-gray-600'">
                                    <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white transition duration-200" :class="doc.required ? 'translate-x-2' : 'translate-x-0'"></span>
                                </button>
                                <input type="hidden" :name="'documents['+index+'][required]'" :value="doc.required ? 1 : 0">
                            </div>
                        </div>
                    </template>

                    <!-- Add New Document Card -->
                    <div @click="addDocument()" class="bg-white dark:bg-kinetic-card border-2 border-dashed border-slate-300 dark:border-kinetic-border rounded-xl p-6 hover:border-teal-500 dark:hover:border-teal-500 hover:bg-teal-50 dark:hover:bg-kinetic-surface transition-all cursor-pointer flex flex-col items-center justify-center min-h-64">
                        <div class="w-14 h-14 bg-slate-100 dark:bg-kinetic-surface rounded-full flex items-center justify-center mb-3 group-hover:bg-teal-100 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600 dark:text-gray-400"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        </div>
                        <p class="text-sm font-semibold text-slate-600 dark:text-gray-400 text-center">Tambah Dokumen Baru</p>
                        <p class="text-xs text-slate-500 dark:text-gray-500 text-center mt-1">Seret komponen ke sini atau klik</p>
                    </div>
                </div>
            </div>

            <!-- Tab Content: Alur Persetujuan -->
            <div x-show="activeTab === 'alur'" class="space-y-8">
                <!-- Timeline Steps -->
                <div class="relative">
                    <!-- Connecting Line -->
                    <div class="absolute left-7 top-12 bottom-0 w-0.5 bg-gradient-to-b from-teal-500 to-teal-500/30"></div>

                    <div class="space-y-6">
                        <template x-for="(step, index) in steps" :key="step.id">
                            <div class="flex gap-6 relative group">
                                <!-- Timeline Dot -->
                                <div class="flex flex-col items-center pt-2">
                                    <div class="w-4 h-4 bg-teal-500 dark:bg-teal-400 rounded-full border-4 border-slate-50 dark:border-kinetic-bg shadow-lg shrink-0"></div>
                                </div>

                                <!-- Step Content -->
                                <div class="flex-1 bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border rounded-lg p-5 hover:border-teal-500 dark:hover:border-teal-500 transition-colors">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-start gap-3 flex-1">
                                            <!-- Drag Handle -->
                                            <div class="cursor-move text-slate-400 dark:text-gray-600 pt-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="5" r="1"/><circle cx="9" cy="12" r="1"/><circle cx="9" cy="19" r="1"/><circle cx="16" cy="5" r="1"/><circle cx="16" cy="12" r="1"/><circle cx="16" cy="19" r="1"/></svg>
                                            </div>

                                            <!-- Step Info -->
                                            <div class="flex-1">
                                                <p class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tahap <span x-text="String(index + 1).padStart(2, '0')"></span></p>
                                                <select x-model="step.role" :name="'steps['+index+'][role]'" class="text-lg font-bold text-slate-900 dark:text-white bg-transparent border-b border-dashed border-slate-300 dark:border-kinetic-border focus:border-teal-500 focus:ring-0 p-0 pb-1 mb-3 w-full cursor-pointer appearance-none transition-colors">
                                                    <option value="" disabled class="text-slate-500 bg-white dark:bg-kinetic-bg font-normal">-- Pilih Approver --</option>
                                                    <template x-for="approver in availableApprovers" :key="approver">
                                                        <option :value="approver" x-text="approver" class="text-slate-700 bg-white dark:bg-kinetic-surface dark:text-gray-200 font-medium"></option>
                                                    </template>
                                                    <!-- Option fallback for the ones already inserted implicitly -->
                                                    <template x-if="!availableApprovers.includes(step.role) && step.role !== ''">
                                                        <option :value="step.role" x-text="step.role" class="text-slate-700 bg-white dark:bg-kinetic-surface dark:text-gray-200 font-medium"></option>
                                                    </template>
                                                </select>
                                                <textarea :name="'steps['+index+'][description]'" x-model="step.description" placeholder="Deskripsi tahapan" class="w-full text-sm text-slate-600 dark:text-gray-400 bg-transparent border-none focus:ring-0 p-0 resize-none"></textarea>
                                            </div>
                                        </div>

                                        <!-- Delete Button -->
                                        <button type="button" @click="removeStep(index)" x-show="steps.length > 1" class="text-slate-400 hover:text-red-500 dark:text-gray-600 dark:hover:text-red-400 p-2 rounded transition-colors opacity-0 group-hover:opacity-100 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                        </button>
                                    </div>

                                    <!-- Status Section -->
                                    <div class="flex items-center justify-between pt-3 border-t border-slate-200 dark:border-kinetic-border">
                                        <span class="text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-widest">Wajib Lampiran Balik</span>
                                        <button type="button" @click="step.type = step.type === 'wajib_lampiran_balik' ? 'opsional' : 'wajib_lampiran_balik'" class="relative inline-flex h-5 w-9 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors" :class="step.type === 'wajib_lampiran_balik' ? 'bg-teal-500 dark:bg-teal-500' : 'bg-slate-300 dark:bg-gray-600'">
                                            <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white transition duration-200" :class="step.type === 'wajib_lampiran_balik' ? 'translate-x-2' : 'translate-x-0'"></span>
                                        </button>
                                        <input type="hidden" :name="'steps['+index+'][type]'" :value="step.type">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Add Step Button -->
                <button type="button" @click="addStep()" class="flex items-center gap-2 px-4 py-2.5 bg-slate-900 dark:bg-kinetic-surface text-white dark:text-gray-300 rounded-lg font-semibold hover:bg-slate-800 dark:hover:bg-kinetic-border transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    Tambah Tahapan
                </button>

                <!-- Summary Cards -->
                <div class="grid grid-cols-3 gap-4 mt-8">
                    <div class="bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border rounded-lg p-5 text-center">
                        <div class="flex justify-center mb-3">
                            <div class="w-10 h-10 bg-amber-100 dark:bg-amber-500/20 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-600 dark:text-amber-400"><circle cx="12" cy="12" r="9"/><polyline points="12 6 12 12 16 14"/></svg>
                            </div>
                        </div>
                        <p class="text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-widest mb-2\">Estimasi</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mb-1\">3-5 Hari</p>
                        <p class="text-xs text-slate-600 dark:text-gray-400">Waktu rata-rata persetujuan alur</p>
                    </div>

                    <div class="bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border rounded-lg p-5 text-center">
                        <div class="flex justify-center mb-3">
                            <div class="w-10 h-10 bg-blue-100 dark:bg-blue-500/20 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 dark:text-blue-400"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="M12 6v6l4 2"/></svg>
                            </div>
                        </div>
                        <p class="text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-widest mb-2\">Validasi</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mb-1\">Full Digital</p>
                        <p class="text-xs text-slate-600 dark:text-gray-400">Semua tahap dalam bentuk online</p>
                    </div>

                    <div class="bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border rounded-lg p-5 text-center">
                        <div class="flex justify-center mb-3">
                            <div class="w-10 h-10 bg-green-100 dark:bg-green-500/20 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-green-600 dark:text-green-400"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"/></svg>
                            </div>
                        </div>
                        <p class="text-xs font-bold text-slate-600 dark:text-gray-400 uppercase tracking-widest mb-2\">Integrasi</p>
                        <p class="text-2xl font-bold text-slate-900 dark:text-white mb-1\">Terhubung</p>
                        <p class="text-xs text-slate-600 dark:text-gray-400">Semua Sarpras universitas terhubung</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-between items-center mt-8 pt-6 border-t border-slate-200 dark:border-kinetic-border">
                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <span>Terakhir diperbarui oleh Admin Utama pada 24 Okt 2023</span>
                </div>
                <div class="flex gap-3">
                    <button type="reset" class="px-6 py-2.5 rounded-lg font-semibold text-slate-700 dark:text-gray-300 bg-white dark:bg-kinetic-card border border-slate-200 dark:border-kinetic-border hover:bg-slate-50 dark:hover:bg-kinetic-surface transition-colors">
                        Batalkan Perubahan
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg font-semibold text-white dark:text-black bg-black hover:bg-slate-500 dark:bg-white dark:hover:bg-slate-300 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Draft Alur
                    </button>
                    <button type="submit" class="px-6 py-2.5 rounded-lg font-semibold text-white bg-teal-500 hover:bg-teal-600 dark:bg-teal-500 dark:hover:bg-teal-400 transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Alur
                    </button>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>