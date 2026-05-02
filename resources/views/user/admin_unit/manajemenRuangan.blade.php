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

                @include('user.admin_unit.modal-tambah-ruang')
                @include('user.admin_unit.modal-edit-ruang')
                @include('user.admin_unit.modal-delete-ruang')
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
                        </div>

                        <div class="flex gap-2 justify-end pt-2">
                            <button type="button" data-room-id="{{ $room->id }}" data-room-name="{{ $room->room_name }}" class="delete-btn inline-flex items-center justify-center rounded-full border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-semibold text-red-300 transition hover:bg-red-500/20">
                                <i class="ph-bold ph-trash text-lg"></i>
                            </button>
                            <button type="button" data-room-id="{{ $room->id }}" data-room-name="{{ $room->room_name }}" data-room-capacity="{{ $room->capacity }}" data-room-building="{{ $room->building->building_name }}" class="edit-btn inline-flex items-center justify-center rounded-full border border-teal-500/20 bg-teal-500/10 px-4 py-2 text-sm font-semibold text-teal-200 transition hover:bg-teal-500/20">Edit</button>
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

    @push('scripts')
    <script>
        let roomToDelete = null;
        let roomNameToDelete = null;

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing room management buttons...');

            // Delete button listeners
            const deleteButtons = document.querySelectorAll('.delete-btn');
            console.log('Found delete buttons:', deleteButtons.length);

            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    console.log('Delete button clicked');
                    const roomId = this.getAttribute('data-room-id');
                    const roomName = this.getAttribute('data-room-name');
                    console.log('Room ID:', roomId, 'Room Name:', roomName);
                    confirmDelete(roomId, roomName);
                });
            });

            // Edit button listeners
            const editButtons = document.querySelectorAll('.edit-btn');
            console.log('Found edit buttons:', editButtons.length);

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    console.log('Edit button clicked');
                    const roomId = this.getAttribute('data-room-id');
                    const roomName = this.getAttribute('data-room-name');
                    const capacity = this.getAttribute('data-room-capacity');
                    const building = this.getAttribute('data-room-building');
                    console.log('Edit data:', {roomId, roomName, capacity, building});
                    openEditModal(roomId, roomName, capacity, building);
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

        function openEditModal(roomId, roomName, capacity, building) {
            const modal = document.getElementById('modalEditRuang');
            
            // Mengubah URL action form agar mengarah ke endpoint update yang benar
            const formEdit = document.getElementById('formEditRuangan');
            if (formEdit) {
                // Sesuaikan URL ini dengan route yang Anda buat di web.php
                formEdit.action = `/admin_unit/rooms/${roomId}`;
            }

            // Store room ID for update
            document.getElementById('editRoomId').value = roomId;
            
            // Populate form fields
            document.getElementById('editNamaRuangan').value = roomName;
            document.getElementById('editKapasitas').value = capacity;
            
            const gedungSelect = document.getElementById('editLokasiGedung');
            if(gedungSelect) {
                for(let i = 0; i < gedungSelect.options.length; i++) {
                    // Pastikan teks atau value cocok dengan data dari tombol
                    if(gedungSelect.options[i].text === building) {
                        gedungSelect.selectedIndex = i;
                        break;
                    }
                }
            }

            // Show modal
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
            if (!roomToDelete) {
                console.error('No room to delete');
                return;
            }

            console.log('Deleting room:', roomToDelete);

            try {
                // Mengambil CSRF token dari meta tag
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                console.log('CSRF Token:', csrfToken ? 'Found' : 'Not found');

                // Pastikan URL fetch sesuai dengan route delete Anda di web.php
                const response = await fetch(`/admin_unit/api/rooms/${roomToDelete}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken || '',
                        'Accept': 'application/json'
                    }
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (response.ok) {
                    // Success - reload page
                    console.log('Delete successful, reloading page...');
                    closeDeleteModal();
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else {
                    // Error response
                    alert(data.message || 'Gagal menghapus ruangan');
                    console.error('Delete error:', data);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Terjadi kesalahan: ' + error.message);
            }
        }
    </script>
    @endpush
</x-app-layout>