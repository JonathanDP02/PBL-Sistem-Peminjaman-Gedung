<x-app-layout>
    <div class="relative px-8 py-8 space-y-8 min-h-full">
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-100/30 dark:bg-kinetic-primary/10 rounded-full blur-[100px] pointer-events-none"></div>

        <section class="relative rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none overflow-hidden p-8">
            <div class="absolute inset-y-0 right-0 w-1/2 bg-gradient-to-l from-teal-50 dark:from-kinetic-primary/10 to-transparent pointer-events-none"></div>
            <div class="relative z-10 flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-secondary text-[11px] font-bold uppercase tracking-[0.3em] border border-teal-200 dark:border-kinetic-primary/20">Daftar Ruangan</span>
                    <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">Manajemen Ruangan</h1>
                    <p class="mt-3 text-sm text-slate-500 dark:text-gray-400 max-w-2xl">Definisikan peran pemberi persetujuan dalam struktur organisasi unit untuk mengotomatisasi alur kerja pemesanan ruang.</p>
                </div>

                <button onclick="openModal()" class="bg-teal-600 dark:bg-[#5EEAD4] hover:bg-teal-700 dark:hover:bg-teal-400 text-white dark:text-teal-950 font-bold px-5 py-3 rounded-full transition flex items-center gap-2 shadow-sm font-heading">
                    <i class="ph-bold ph-plus-circle text-lg"></i> Tambah Ruangan
                </button>

            </div>
        </section>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($rooms as $room)
                <article class="group relative overflow-hidden rounded-[2rem] border border-slate-200 dark:border-kinetic-border bg-white dark:bg-kinetic-card shadow-sm p-6 transition hover:border-teal-400 dark:hover:border-kinetic-primary/50">
                    <div class="absolute inset-x-0 top-0 h-36 bg-cover bg-center opacity-0 sm:opacity-100" style="background-image: url('https://images.unsplash.com/photo-1494526585095-c41746248156?auto=format&fit=crop&w=1200&q=80');"></div>
                    <div class="relative pt-36 space-y-5">
                        <div class="space-y-2">
                            <p class="text-xs uppercase tracking-[0.28em] text-teal-600 dark:text-kinetic-primary font-bold">{{ $room->building->building_name }} • {{ $room->room_name }}</p>
                            <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $room->room_name }}</h2>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Kapasitas: {{ $room->capacity }} orang</p>
                        </div>

                        <div class="grid gap-3 text-sm">
                            <div class="flex items-center justify-between rounded-2xl bg-slate-900/80 border border-slate-800/80 px-4 py-3">
                                <div>
                                    <p class="text-xs text-slate-500 uppercase tracking-[0.24em]">Kapasitas</p>
                                    <p class="font-semibold text-white">{{ $room->capacity }} Orang</p>
                                </div>
                                <span class="text-[11px] font-semibold uppercase tracking-[0.24em] text-teal-500">{{ $room->building->building_name }}</span>
                            </div>

                            <div class="space-y-2">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Deskripsi</p>
                                <div class="rounded-2xl bg-slate-900/80 px-3 py-2 text-sm text-slate-300">
                                    {{ $room->description ?? 'Tidak ada deskripsi' }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <p class="text-xs uppercase tracking-[0.24em] text-slate-500">Fasilitas Ruang</p>
                                <div class="flex flex-wrap gap-1.5 max-h-28 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-slate-700">
                                    @forelse($room->facilities ?? [] as $fac)
                                        <div class="inline-flex items-center gap-1.5 rounded-xl bg-teal-500/10 border border-teal-500/20 px-2.5 py-1 text-xs text-teal-300">
                                            <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
                                            <span>{{ $fac->name }} <strong class="text-white">x{{ $fac->quantity }}</strong></span>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-500 italic pl-1">Belum ada inventaris fasilitas.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-2 justify-end pt-2">
                            <button type="button" data-room-id="{{ $room->id }}" data-room-name="{{ $room->room_name }}" class="delete-btn inline-flex items-center justify-center rounded-full border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-semibold text-red-300 transition hover:bg-red-500/20">
                                <i class="ph-bold ph-trash text-lg"></i>
                            </button>
                            <button type="button" 
                                data-room-id="{{ $room->id }}" 
                                data-room-name="{{ $room->room_name }}" 
                                data-room-capacity="{{ $room->capacity }}" 
                                data-room-building="{{ $room->building->building_name }}" 
                                data-room-description="{{ $room->description }}" 
                                data-room-workflow="{{ $room->workflow_id }}"
                                data-room-facilities="{{ json_encode($room->facilities ?? []) }}"
                                class="edit-btn inline-flex items-center justify-center rounded-full border border-teal-500/20 bg-teal-500/10 px-4 py-2 text-sm font-semibold text-teal-200 transition hover:bg-teal-500/20">
                                Edit
                            </button>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-slate-500 dark:text-gray-400">Belum ada ruangan. Silakan tambahkan ruangan baru.</p>
                </div>
            @endforelse

            <button type="button" onclick="openModal()" class="flex min-h-[350px] flex-col items-center justify-center gap-3 rounded-[2rem] border-2 border-dashed border-slate-200/70 bg-white/30 dark:border-kinetic-border/70 dark:bg-slate-900/40 text-slate-500 transition hover:border-teal-400 hover:text-teal-600 dark:hover:border-kinetic-primary/60 dark:hover:text-kinetic-primary">
                <span class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-teal-50 text-3xl text-teal-600 dark:bg-kinetic-primary/10 dark:text-kinetic-primary">
                    <i class="ph-bold ph-plus"></i>
                </span>
                <span class="text-sm font-semibold">Tambah Ruangan</span>
            </button>
        </div>
    </div>

    @push('modals')
        @include('user.admin_unit.modal-tambah-ruang')
        @include('user.admin_unit.modal-edit-ruang')
        @include('user.admin_unit.modal-delete-ruang')
    @endpush

    @push('scripts')
    <script>
        let roomToDelete = null;
        let roomNameToDelete = null;
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
                <div class="flex gap-2 items-center facility-row">
                    <div class="relative w-2/3">
                        <select name="facilities[${facIndex}][name]" required class="w-full bg-slate-50 dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-4 pr-8 py-3 text-sm text-slate-900 dark:text-white appearance-none outline-none focus:border-teal-500">
                            ${facilityOptions}
                        </select>
                        <i class="ph ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                    <div class="w-1/3 flex items-center gap-2">
                        <input type="number" name="facilities[${facIndex}][quantity]" value="${qty}" min="1" required placeholder="Jml" class="w-full bg-slate-50 dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-3 py-3 text-sm text-slate-900 dark:text-white outline-none focus:border-teal-500">
                        <button type="button" onclick="this.closest('.facility-row').remove()" class="text-red-500 hover:text-red-600 p-2 transition-colors">
                            <i class="ph-bold ph-trash text-lg"></i>
                        </button>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', html);
            
            if(name) {
                const selects = container.querySelectorAll('select');
                selects[selects.length - 1].value = name;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            addFacilityRow('tambah');

            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const roomId = this.getAttribute('data-room-id');
                    const roomName = this.getAttribute('data-room-name');
                    confirmDelete(roomId, roomName);
                });
            });

            const editButtons = document.querySelectorAll('.edit-btn');
            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const roomId = this.getAttribute('data-room-id');
                    const roomName = this.getAttribute('data-room-name');
                    const capacity = this.getAttribute('data-room-capacity');
                    const building = this.getAttribute('data-room-building');
                    const description = this.getAttribute('data-room-description');
                    const workflowId = this.getAttribute('data-room-workflow');
                    
                    const facilitiesRaw = this.getAttribute('data-room-facilities');
                    let facilities = [];
                    try {
                        facilities = JSON.parse(facilitiesRaw);
                    } catch(e) {
                        console.error("Gagal parsing fasilitas", e);
                    }
                    
                    openEditModal(roomId, roomName, capacity, building, description, facilities, workflowId);
                });
            });
        });

        function openModal() {
            const modal = document.getElementById('modalTambahRuang');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('modalTambahRuang');
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function openEditModal(roomId, roomName, capacity, building, description, facilities = [], workflowId = '') {
            const modal = document.getElementById('modalEditRuang');
            
            const formEdit = document.getElementById('formEditRuangan');
            if (formEdit) {
                formEdit.action = `/admin_unit/rooms/${roomId}`;
            }

            document.getElementById('editRoomId').value = roomId;
            document.getElementById('editNamaRuangan').value = roomName;
            document.getElementById('editKapasitas').value = capacity;
            
            const inputDeskripsi = document.getElementById('editDeskripsi');
            if(inputDeskripsi) {
                inputDeskripsi.value = description || '';
            }
            
            const gedungSelect = document.getElementById('editLokasiGedung');
            if(gedungSelect) {
                for(let i = 0; i < gedungSelect.options.length; i++) {
                    if(gedungSelect.options[i].text === building) {
                        gedungSelect.selectedIndex = i;
                        break;
                    }
                }
            }
            
            const workflowSelect = document.getElementById('editWorkflowId');
            if(workflowSelect) {
                workflowSelect.value = workflowId || '';
            }

            const containerEdit = document.getElementById('container-fasilitas-edit');
            if(containerEdit) {
                containerEdit.innerHTML = '';
                
                if (facilities && facilities.length > 0) {
                    facilities.forEach(fac => {
                        addFacilityRow('edit', fac.name, fac.quantity);
                    });
                } else {
                    addFacilityRow('edit');
                }
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('modalEditRuang');
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        function confirmDelete(roomId, roomName) {
            roomToDelete = roomId;
            roomNameToDelete = roomName;
            const modal = document.getElementById('modalDeleteRuang');
            document.getElementById('deleteRoomName').textContent = roomName;
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('div').classList.remove('scale-95');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('modalDeleteRuang');
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
            roomToDelete = null;
            roomNameToDelete = null;
        }

        async function deleteRoom() {
            if (!roomToDelete) return;

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                const response = await fetch(`/admin_unit/api/rooms/${roomToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    closeDeleteModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else {
                    Alpine.$data(document.getElementById('global-modal-container')).showAlert('Gagal', data.message || 'Gagal menghapus ruangan', 'danger');
                }
            } catch (error) {
                Alpine.$data(document.getElementById('global-modal-container')).showAlert('Kesalahan Sistem', 'Terjadi kesalahan: ' + error.message, 'danger');
            }
        }
    </script>
    
    <div x-data="{
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
        showConfirm(title, description, onConfirm, type = 'danger', confirmText = 'Ya', cancelText = 'Batal') {
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
    }" id="global-modal-container">
        <x-modal-confirm />
    </div>
    @endpush
</x-app-layout>