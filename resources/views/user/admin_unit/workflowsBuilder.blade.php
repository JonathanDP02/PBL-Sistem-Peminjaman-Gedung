<x-app-layout title="Daftar Workflow">
    <div x-data="{
            search: '',
            filterKategori: 'semua',
            workflows: [
                { id: 1, nama: 'Alur Peminjaman Auditorium', kategori: 'Gedung Pertemuan', tahap: 3, status: 'Aktif', updateTerakhir: '24 Okt 2023', icon: 'building' },
                { id: 2, nama: 'Alur Peminjaman Ruang Kelas', kategori: 'Fasilitas Akademik', tahap: 2, status: 'Aktif', updateTerakhir: '12 Sep 2023', icon: 'graduation-cap' },
                { id: 3, nama: 'Alur Peminjaman Kendaraan Dinas', kategori: 'Transportasi', tahap: 4, status: 'Draft', updateTerakhir: '01 Nov 2023', icon: 'car' },
                { id: 4, nama: 'Alur Peminjaman Lapangan Olahraga', kategori: 'Fasilitas Olahraga', tahap: 2, status: 'Aktif', updateTerakhir: '15 Agu 2023', icon: 'activity' }
            ],
            get filteredWorkflows() {
                return this.workflows.filter(w => {
                    const matchSearch = w.nama.toLowerCase().includes(this.search.toLowerCase()) || w.kategori.toLowerCase().includes(this.search.toLowerCase());
                    const matchKategori = this.filterKategori === 'semua' || w.kategori === this.filterKategori;
                    return matchSearch && matchKategori;
                });
            }
        }" 
        class="bg-slate-50 dark:bg-kinetic-bg min-h-screen py-8 text-slate-800 dark:text-gray-200 font-sans transition-colors duration-300">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="mb-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Manajemen Workflow</h1>
                        <p class="text-slate-600 dark:text-gray-400 text-sm">Kelola alur persetujuan dan syarat dokumen untuk setiap jenis peminjaman.</p>
                    </div>
                    <button class="px-5 py-2.5 bg-teal-500 dark:bg-teal-500 text-white rounded-lg font-semibold hover:bg-teal-600 dark:hover:bg-teal-400 transition-colors flex items-center gap-2 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Buat Workflow Baru
                    </button>
                </div>

                <!-- Filters & Search -->
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between bg-white dark:bg-kinetic-card p-4 rounded-xl border border-slate-200 dark:border-kinetic-border shadow-sm">
                    <div class="relative w-full sm:w-96">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                        </div>
                        <input x-model="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-kinetic-border rounded-lg leading-5 bg-slate-50 dark:bg-kinetic-surface text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all sm:text-sm" placeholder="Cari workflow atau kategori...">
                    </div>
                    <div class="flex items-center gap-2 w-full sm:w-auto">
                        <span class="text-sm text-slate-500 dark:text-gray-400 font-medium">Kategori:</span>
                        <select x-model="filterKategori" class="block w-full sm:w-48 pl-3 pr-10 py-2 text-base border-slate-200 dark:border-kinetic-border focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm rounded-lg bg-slate-50 dark:bg-kinetic-surface text-slate-900 dark:text-white">
                            <option value="semua">Semua Kategori</option>
                            <option value="Gedung Pertemuan">Gedung Pertemuan</option>
                            <option value="Fasilitas Akademik">Fasilitas Akademik</option>
                            <option value="Fasilitas Olahraga">Fasilitas Olahraga</option>
                            <option value="Transportasi">Transportasi</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Workflow Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="workflow in filteredWorkflows" :key="workflow.id">
                    <a href="{{ route('workflows-index') }}" class="group block h-full">
                        <div class="bg-white dark:bg-kinetic-card rounded-xl border border-slate-200 dark:border-kinetic-border p-6 hover:shadow-lg hover:border-teal-500 dark:hover:border-teal-400 transition-all h-full flex flex-col relative overflow-hidden">
                            <!-- Decorative bg pattern -->
                            <div class="absolute -right-6 -top-6 w-24 h-24 bg-teal-50 dark:bg-teal-900/20 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-500"></div>

                            <div class="flex items-start justify-between mb-4 relative z-10">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-slate-100 dark:bg-kinetic-surface text-teal-600 dark:text-teal-400 group-hover:bg-teal-500 group-hover:text-white transition-colors">
                                    <template x-if="workflow.icon === 'building'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="2" width="16" height="20" rx="2" ry="2"/><path d="M9 22v-4h6v4"/><path d="M8 6h.01"/><path d="M16 6h.01"/><path d="M12 6h.01"/><path d="M12 10h.01"/><path d="M12 14h.01"/><path d="M16 10h.01"/><path d="M16 14h.01"/><path d="M8 10h.01"/><path d="M8 14h.01"/></svg>
                                    </template>
                                    <template x-if="workflow.icon === 'graduation-cap'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                    </template>
                                    <template x-if="workflow.icon === 'car'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 16H9m10 0h3v-3.15a1 1 0 0 0-.84-.99L16 11l-2.7-3.6a2 2 0 0 0-1.6-.8H8.3a2 2 0 0 0-1.6.8L4 11l-5.16.86a1 1 0 0 0-.84.99V16h3m10 0a2 2 0 1 1-4 0m-6 0a2 2 0 1 1-4 0"/></svg>
                                    </template>
                                    <template x-if="workflow.icon === 'activity'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                    </template>
                                </div>
                                <span :class="workflow.status === 'Aktif' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400'" class="px-2.5 py-1 rounded-full text-xs font-bold tracking-wide uppercase">
                                    <span x-text="workflow.status"></span>
                                </span>
                            </div>

                            <div class="flex-1 relative z-10">
                                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-1 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition-colors" x-text="workflow.nama"></h3>
                                <p class="text-sm font-medium text-teal-600 dark:text-teal-400 mb-4" x-text="workflow.kategori"></p>
                            </div>

                            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-kinetic-border flex items-center justify-between text-xs text-slate-500 dark:text-gray-400 relative z-10">
                                <div class="flex items-center gap-1.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                    <span x-text="'Diperbarui ' + workflow.updateTerakhir"></span>
                                </div>
                                <div class="flex items-center justify-center bg-slate-100 dark:bg-kinetic-surface px-2 py-1 rounded-md gap-1 font-semibold text-slate-700 dark:text-gray-300">
                                    <span x-text="workflow.tahap"></span>
                                    <span>Tahap</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </template>

                <!-- Empty State -->
                <div x-show="filteredWorkflows.length === 0" class="col-span-full py-12 flex flex-col items-center justify-center text-slate-500 dark:text-gray-400">
                    <svg class="w-16 h-16 mb-4 text-slate-300 dark:text-kinetic-border" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p class="text-lg font-medium">Tidak ada workflow ditemukan.</p>
                    <p class="text-sm">Cobalah mencari dengan kata kunci lain.</p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>