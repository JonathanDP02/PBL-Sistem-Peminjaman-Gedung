<x-app-layout>
    <div id="fasilitasMain" class="px-8 py-8 space-y-8 min-h-full" x-data="{ 
        activeTab: 'ruangan',
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
        }
    }">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Master Data Gedung & Ruangan</h1>
                <p class="mt-2 text-sm text-slate-500 dark:text-gray-400">Kelola keseluruhan data gedung dan ruangan yang terdaftar di dalam sistem.</p>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center gap-3">
                <button x-show="activeTab === 'gedung'" onclick="openModalGedung()" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2 shadow-sm">
                    <i class="ph-bold ph-plus"></i> Tambah Gedung
                </button>
                <button x-show="activeTab === 'ruangan'" onclick="openModal()" class="bg-teal-600 hover:bg-teal-700 text-white font-semibold px-4 py-2 rounded-lg transition text-sm flex items-center gap-2 shadow-sm">
                    <i class="ph-bold ph-plus"></i> Tambah Ruangan
                </button>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="border-b border-slate-200 dark:border-slate-700">
            <nav class="flex space-x-8" aria-label="Tabs">
                <button @click="activeTab = 'ruangan'"
                    :class="activeTab === 'ruangan' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:hover:text-slate-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition focus:outline-none">
                    Data Ruangan
                </button>
                <button @click="activeTab = 'gedung'"
                    :class="activeTab === 'gedung' ? 'border-teal-500 text-teal-600 dark:text-teal-400' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 dark:hover:text-slate-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition focus:outline-none">
                    Data Gedung
                </button>
            </nav>
        </div>

        <!-- Tab Content: Ruangan -->
        <div x-show="activeTab === 'ruangan'" x-transition.opacity.duration.300ms>
            <div class="bg-white dark:bg-[#1a1a1a] shadow-sm rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700/50">
                        <thead class="bg-slate-50 dark:bg-[#151515]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nama Ruangan</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Gedung</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Kapasitas</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Pemilik (Unit)</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50 bg-white dark:bg-[#1a1a1a]">
                            @forelse ($rooms as $room)
                            <tr class="hover:bg-slate-50 dark:hover:bg-[#222] transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $room->room_name }}</div>
                                    <div class="text-xs text-slate-500 max-w-[200px] truncate" title="{{ $room->description }}">{{ $room->description ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                        {{ $room->building->building_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-700 dark:text-slate-300">
                                    {{ $room->capacity }} orang
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-teal-600 dark:text-teal-400 font-medium">{{ $room->unit->unit_name }}</span>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" 
                                            data-room-id="{{ $room->id }}" 
                                            data-room-name="{{ $room->room_name }}" 
                                            data-room-capacity="{{ $room->capacity }}" 
                                            data-room-building="{{ $room->building->building_name ?? '' }}" 
                                            data-room-description="{{ $room->description }}" 
                                            data-room-unit="{{ $room->unit_id }}"
                                            data-room-workflow="{{ $room->workflow_id }}"
                                            class="edit-btn p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded transition">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </button>
                                        <button type="button" 
                                            data-room-id="{{ $room->id }}" 
                                            data-room-name="{{ $room->room_name }}"
                                            class="delete-btn p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/30 rounded transition">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">Belum ada data ruangan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Content: Gedung -->
        <div x-cloak x-show="activeTab === 'gedung'" x-transition.opacity.duration.300ms>
            <div class="bg-white dark:bg-[#1a1a1a] shadow-sm rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700/50">
                        <thead class="bg-slate-50 dark:bg-[#151515]">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Nama Gedung</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Lokasi / Detail</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Ruangan</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700/50 bg-white dark:bg-[#1a1a1a]">
                            @forelse ($buildings as $b)
                            <tr class="hover:bg-slate-50 dark:hover:bg-[#222] transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold text-slate-900 dark:text-white">{{ $b->building_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-600 dark:text-slate-300">{{ $b->location ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-700 dark:text-slate-300">
                                    {{ $b->rooms_count ?? 0 }} Ruangan
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button type="button" 
                                            onclick="openEditModalGedung('{{ $b->id }}', '{{ addslashes($b->building_name) }}', '{{ addslashes($b->location) }}')"
                                            class="p-1.5 text-blue-600 hover:text-blue-800 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded transition">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </button>
                                        <button type="button" 
                                            onclick="confirmDeleteGedung('{{ $b->id }}', '{{ addslashes($b->building_name) }}')"
                                            class="p-1.5 text-red-600 hover:text-red-800 hover:bg-red-50 dark:hover:bg-red-900/30 rounded transition">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-500 dark:text-slate-400">Belum ada data gedung.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Custom Confirm Modal -->
        <x-modal-confirm />
    </div>

    @push('modals')
        <!-- Includes Modals untuk Ruangan -->
        @include('user.superadmin.modal-tambah-ruang')
        @include('user.superadmin.modal-edit-ruang')
        @include('user.superadmin.modal-delete-ruang')

        <!-- Modals untuk Gedung -->
        <!-- Modal Tambah Gedung -->
        <div id="modalTambahGedung" class="hidden fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl w-full max-w-md p-6 relative shadow-2xl transform scale-95 transition-transform duration-300">
                <button onclick="closeModalGedung()" class="absolute top-4 right-4 text-slate-400 hover:text-red-500"><i class="ph-bold ph-x text-xl"></i></button>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Tambah Gedung Baru</h3>
                <form action="{{ route('superadmin.buildings.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Nama Gedung</label>
                        <input type="text" name="building_name" placeholder="Contoh: Gedung Sipil Terpadu" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-lg px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-teal-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Lokasi / Keterangan</label>
                        <input type="text" name="location" placeholder="Contoh: Area Barat Kampus" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-lg px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-teal-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Gambar Gedung</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-lg px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-teal-500 transition-colors">
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeModalGedung()" class="w-1/2 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white hover:bg-slate-200 py-3 rounded-xl font-bold transition text-sm">Batal</button>
                        <button type="submit" class="w-1/2 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-xl transition text-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Edit Gedung -->
        <div id="modalEditGedung" class="hidden fixed inset-0 z-[99] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl w-full max-w-md p-6 relative shadow-2xl transform scale-95 transition-transform duration-300">
                <button onclick="closeEditModalGedung()" class="absolute top-4 right-4 text-slate-400 hover:text-red-500"><i class="ph-bold ph-x text-xl"></i></button>
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Edit Gedung</h3>
                <form id="formEditGedung" action="#" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Nama Gedung</label>
                        <input type="text" id="editBuildingName" name="building_name" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-lg px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-teal-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Lokasi / Keterangan</label>
                        <input type="text" id="editBuildingLocation" name="location" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-lg px-4 py-3 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-teal-500 transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1.5">Ubah Gambar Gedung</label>
                        <input type="file" name="image" accept="image/*" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-lg px-4 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-teal-500 transition-colors">
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" onclick="closeEditModalGedung()" class="w-1/2 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white hover:bg-slate-200 py-3 rounded-xl font-bold transition text-sm">Batal</button>
                        <button type="submit" class="w-1/2 bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 rounded-xl transition text-sm">Simpan</button>
                    </div>
                </form>
        </div>
    @endpush

    @push('scripts')
    <script>
        // === RUANGAN SCRIPT ===
        let roomToDelete = null;
        let facIndex = 0;

        const facilityOptions = `
            <option value="">-- Pilih Fasilitas --</option>
            <option value="AC">AC (Air Conditioner)</option>
            <option value="Proyektor Laser">Proyektor Laser</option>
            <option value="Layar Proyektor">Layar Proyektor</option>
            <option value="Kursi Lipat">Kursi Lipat</option>
            <option value="Meja">Meja</option>
            <option value="Papan Tulis">Papan Tulis (Whiteboard)</option>
            <option value="Microphone Wireless">Microphone Wireless</option>
            <option value="Speaker Portable">Speaker Portable</option>
            <option value="PC Lab">PC / Komputer Lab</option>
            <option value="Laptop Presenter">Laptop Presenter</option>
        `;

        function addFacilityRow(modalType, name = '', qty = 1) {
            const container = document.getElementById(`container-fasilitas-${modalType}`);
            if (!container) return;
            
            facIndex++;
            const html = `
                <div class="flex gap-2 items-center facility-row mb-2">
                    <div class="relative w-2/3">
                        <select name="facilities[${facIndex}][name]" required class="w-full bg-slate-50 dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-lg pl-3 pr-8 py-2 text-sm text-slate-900 dark:text-white appearance-none outline-none focus:border-teal-500">
                            ${facilityOptions}
                        </select>
                    </div>
                    <div class="w-1/3 flex items-center gap-1">
                        <input type="number" name="facilities[${facIndex}][quantity]" value="${qty}" min="1" required class="w-full bg-slate-50 dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-lg px-2 py-2 text-sm text-slate-900 dark:text-white outline-none focus:border-teal-500">
                        <button type="button" onclick="this.closest('.facility-row').remove()" class="text-red-500 hover:text-red-600 p-1 transition-colors"><i class="ph-bold ph-trash"></i></button>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            
            if(name) {
                const selects = container.querySelectorAll('select');
                selects[selects.length - 1].value = name;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            addFacilityRow('tambah');

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    confirmDelete(this.dataset.roomId, this.dataset.roomName);
                });
            });

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    let facilities = [];
                    try { facilities = JSON.parse(this.dataset.roomFacilities); } catch(e) {}
                    openEditModal(
                        this.dataset.roomId, this.dataset.roomName, this.dataset.roomCapacity, 
                        this.dataset.roomBuilding, this.dataset.roomDescription, 
                        this.dataset.roomUnit, facilities, this.dataset.roomWorkflow
                    );
                });
            });
        });

        function openModal() {
            const modal = document.getElementById('modalTambahRuang');
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.remove('opacity-0'); modal.querySelector('div').classList.remove('scale-95'); }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('modalTambahRuang');
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        function openEditModal(roomId, roomName, capacity, building, description, unitId, facilities = [], workflowId = '') {
            const modal = document.getElementById('modalEditRuang');
            document.getElementById('formEditRuangan').action = `/superadmin/rooms/${roomId}`;
            document.getElementById('editRoomId').value = roomId;
            document.getElementById('editNamaRuangan').value = roomName;
            document.getElementById('editKapasitas').value = capacity;
            if(document.getElementById('editDeskripsi')) document.getElementById('editDeskripsi').value = description || '';
            
            const gedungSelect = document.getElementById('editLokasiGedung');
            if(gedungSelect) {
                for(let i=0; i<gedungSelect.options.length; i++) {
                    if(gedungSelect.options[i].text === building) { gedungSelect.selectedIndex = i; break; }
                }
            }
            if(document.getElementById('editUnitPemilik')) document.getElementById('editUnitPemilik').value = unitId || '';
            if(document.getElementById('editWorkflowId')) document.getElementById('editWorkflowId').value = workflowId || '';

            const container = document.getElementById('container-fasilitas-edit');
            if(container) {
                container.innerHTML = '';
                if(facilities.length > 0) facilities.forEach(f => addFacilityRow('edit', f.name, f.quantity));
                else addFacilityRow('edit');
            }

            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.remove('opacity-0'); modal.querySelector('div').classList.remove('scale-95'); }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('modalEditRuang');
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        function confirmDelete(roomId, roomName) {
            roomToDelete = roomId;
            document.getElementById('deleteRoomName').textContent = roomName;
            const modal = document.getElementById('modalDeleteRuang');
            modal.classList.remove('hidden');
            setTimeout(() => { modal.classList.remove('opacity-0'); modal.querySelector('div').classList.remove('scale-95'); }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('modalDeleteRuang');
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        function getFasilitasAlpine() {
            return Alpine.$data(document.getElementById('fasilitasMain'));
        }

        async function deleteRoom() {
            if(!roomToDelete) return;
            try {
                const response = await fetch(`/superadmin/api/rooms/${roomToDelete}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                });
                if(response.ok) { window.location.reload(); } 
                else { 
                    const d = await response.json(); 
                    getFasilitasAlpine().showAlert('Gagal Hapus Ruangan', d.message || 'Gagal hapus ruangan', 'danger');
                }
            } catch(e) { 
                getFasilitasAlpine().showAlert('Kesalahan Sistem', 'Error: ' + e.message, 'danger');
            }
        }

        // === GEDUNG SCRIPT ===
        function openModalGedung() {
            const m = document.getElementById('modalTambahGedung');
            m.classList.remove('hidden');
            setTimeout(() => { m.classList.remove('opacity-0'); m.querySelector('div').classList.remove('scale-95'); }, 10);
        }
        function closeModalGedung() {
            const m = document.getElementById('modalTambahGedung');
            m.classList.add('opacity-0'); m.querySelector('div').classList.add('scale-95');
            setTimeout(() => { m.classList.add('hidden'); }, 300);
        }

        function openEditModalGedung(id, name, loc) {
            const m = document.getElementById('modalEditGedung');
            document.getElementById('formEditGedung').action = `/superadmin/buildings/${id}`;
            document.getElementById('editBuildingName').value = name;
            document.getElementById('editBuildingLocation').value = loc;
            m.classList.remove('hidden');
            setTimeout(() => { m.classList.remove('opacity-0'); m.querySelector('div').classList.remove('scale-95'); }, 10);
        }
        function closeEditModalGedung() {
            const m = document.getElementById('modalEditGedung');
            m.classList.add('opacity-0'); m.querySelector('div').classList.add('scale-95');
            setTimeout(() => { m.classList.add('hidden'); }, 300);
        }

        function confirmDeleteGedung(id, name) {
            getFasilitasAlpine().showConfirm(
                'Hapus Gedung?',
                `Yakin ingin menghapus gedung ${name}? Gedung tidak dapat dihapus jika masih ada ruangan yang terhubung.`,
                async () => {
                    try {
                        const response = await fetch(`/superadmin/api/buildings/${id}`, {
                            method: 'DELETE',
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                        });
                        const d = await response.json();
                        if(response.ok) { window.location.reload(); } 
                        else { 
                            getFasilitasAlpine().showAlert('Gagal Hapus Gedung', d.message || 'Gagal hapus gedung', 'danger');
                        }
                    } catch(e) { 
                        getFasilitasAlpine().showAlert('Kesalahan Sistem', 'Error: ' + e.message, 'danger');
                    }
                },
                'danger',
                'Hapus Gedung'
            );
        }
    </script>
    @endpush
</x-app-layout>