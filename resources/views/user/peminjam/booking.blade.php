<x-app-layout title="Booking">
    <style>
        /* Sembunyikan default dropdown arrow bawaan OS/browser */
        select {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
        }
        select::-ms-expand {
            display: none !important;
        }

        /* Sembunyikan native calendar & time picker icons bawaan browser agar tidak double */
        input[type="date"]::-webkit-calendar-picker-indicator,
        input[type="time"]::-webkit-calendar-picker-indicator {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            opacity: 0 !important;
            cursor: pointer !important;
            z-index: 20 !important;
        }

        /* Perbaiki transform scale dot untuk custom radio button */
        input[name="rental_type"]:checked ~ div .bg-kinetic-primary {
            transform: scale(1) !important;
        }
    </style>
    <div class="relative px-8 pt-4 pb-8 space-y-10 z-10 flex flex-col min-h-full">
        
        <div>
            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 dark:bg-kinetic-primary/10 border border-teal-200 dark:border-kinetic-primary/20 rounded-full text-[10px] font-bold text-teal-700 dark:text-kinetic-secondary tracking-widest uppercase mb-4 transition-colors">
                <span class="w-1.5 h-1.5 bg-kinetic-primary rounded-full"></span> Sistem Reservasi Terpadu
            </span>
            <h2 class="font-heading text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 transition-colors">
                Reservasi <span class="text-kinetic-primary">Tanpa Batas</span>
            </h2>
            <p class="text-slate-500 dark:text-gray-400 text-sm max-w-2xl leading-relaxed transition-colors">
                Wujudkan kegiatan akademik dan organisasi Anda dengan akses mudah ke seluruh ruang pertemuan dan auditorium universitas.
            </p>
        </div>

        <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="workflow_id" id="workflowIdInput" value="">

            <div class="bg-white dark:bg-[#151515] border border-slate-100 dark:border-kinetic-border shadow-xl dark:shadow-none rounded-3xl p-8 lg:p-10 transition-all duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    
                    <div class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Nama Kegiatan <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <i class="ph ph-text-t absolute left-4 top-1/2 -translate-y-1/4 text-slate-400 dark:text-gray-500 text-lg group-focus-within:text-kinetic-primary transition-colors"></i>
                                <input type="text" name="event_name" id="eventName" required placeholder="Cth: Rapat Evaluasi Bulanan"
                                    class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary focus:ring-2 focus:ring-kinetic-primary/20 hover:border-slate-300 dark:hover:border-[#3A3A3A] transition-all duration-300">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Deskripsi Kegiatan <span class="text-slate-400 font-normal">(Maks. 250 karakter)</span></label>
                            <div class="relative group">
                                <i class="ph ph-text-align-left absolute left-4 top-4 text-slate-400 dark:text-gray-500 text-lg group-focus-within:text-kinetic-primary transition-colors"></i>
                                <textarea name="event_description" id="eventDescription" maxlength="250" placeholder="Jelaskan detail agenda dan tujuan kegiatan..." rows="3"
                                    class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary focus:ring-2 focus:ring-kinetic-primary/20 hover:border-slate-300 dark:hover:border-[#3A3A3A] transition-all duration-300 resize-none"></textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Pilih Ruangan <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <i class="ph ph-buildings absolute left-4 top-1/2 -translate-y-1/4 text-slate-400 dark:text-gray-500 text-lg group-focus-within:text-kinetic-primary transition-colors"></i>
                                <select id="roomSelect" name="room_id" required style="appearance: none !important; -webkit-appearance: none !important; -moz-appearance: none !important;"
                                        class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-10 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary focus:ring-2 focus:ring-kinetic-primary/20 hover:border-slate-300 dark:hover:border-[#3A3A3A] transition-all duration-300 appearance-none cursor-pointer">
                                    <option value="">-- Pilih Ruangan --</option>
                                    @foreach($buildings as $building)
                                        <optgroup label="{{ $building->building_name }}" class="bg-white dark:bg-[#151515] font-semibold text-xs text-slate-400 dark:text-gray-500">
                                            @foreach($building->rooms as $room)
                                                <option value="{{ $room->id }}" data-unit-id="{{ $room->unit_id }}" data-capacity="{{ $room->capacity }}" class="bg-white dark:bg-[#1A1A1A] text-sm text-slate-900 dark:text-white py-2">
                                                    {{ $room->room_name }} (Kapasitas: {{ $room->capacity }} orang)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <i class="ph-caret-down absolute right-4 top-1/2 -translate-y-1/4 text-slate-400 dark:text-gray-500 pointer-events-none text-sm"></i>
                            </div>
                        </div>

                        <!-- Container Detail Ruangan & Fasilitas -->
                        <div id="roomDetailsContainer" class="hidden bg-slate-50/50 dark:bg-[#1A1A1A]/50 border border-dashed border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 space-y-3 transition-all duration-300">
                            <div class="flex items-center justify-between">
                                <span class="text-[9px] font-bold text-kinetic-primary tracking-widest uppercase">Spesifikasi Ruang</span>
                                <span class="text-[10px] bg-slate-200 dark:bg-[#2A2A2A] text-slate-700 dark:text-gray-300 px-2 py-0.5 rounded font-bold" id="roomCapacityBadge">Kapasitas: -</span>
                            </div>
                            <p class="text-xs text-slate-600 dark:text-gray-400 leading-relaxed" id="roomDescText">-</p>
                            <div class="pt-2 border-t border-slate-100 dark:border-[#2A2A2A]">
                                <span class="block text-[9px] font-bold text-slate-400 dark:text-gray-500 tracking-widest uppercase mb-1.5">Fasilitas Tersedia:</span>
                                <div class="flex flex-wrap gap-1.5" id="roomFacilitiesContainer">
                                    <!-- Badges fasilitas di-render via JS -->
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="scopeSelect" name="event_scope" value="Internal">

                        <!-- Durasi Sewa Toggle -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Durasi Sewa <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-4">
                                <label class="relative flex items-center gap-4 bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 cursor-pointer hover:border-kinetic-primary/40 hover:bg-slate-100/50 dark:hover:bg-[#222] transition-all group duration-300 select-none">
                                    <input type="radio" name="rental_type" value="single" checked class="peer sr-only">
                                    <div class="absolute inset-0 rounded-2xl border border-transparent peer-checked:border-kinetic-primary peer-checked:bg-kinetic-primary/[0.02] dark:peer-checked:bg-kinetic-primary/[0.04] transition-all duration-300"></div>
                                    <div class="w-5 h-5 rounded-full border border-slate-300 dark:border-[#333] peer-checked:border-kinetic-primary flex items-center justify-center shrink-0 transition-colors z-10 duration-300">
                                        <div class="w-2.5 h-2.5 rounded-full bg-kinetic-primary scale-0 peer-checked:scale-100 transition-transform duration-300"></div>
                                    </div>
                                    <div class="z-10">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors group-hover:text-kinetic-primary duration-300">Satu Hari</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed mt-0.5">Peminjaman selesai di hari yang sama</p>
                                    </div>
                                </label>
                                <label class="relative flex items-center gap-4 bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 cursor-pointer hover:border-kinetic-primary/40 hover:bg-slate-100/50 dark:hover:bg-[#222] transition-all group duration-300 select-none">
                                    <input type="radio" name="rental_type" value="range" class="peer sr-only">
                                    <div class="absolute inset-0 rounded-2xl border border-transparent peer-checked:border-kinetic-primary peer-checked:bg-kinetic-primary/[0.02] dark:peer-checked:bg-kinetic-primary/[0.04] transition-all duration-300"></div>
                                    <div class="w-5 h-5 rounded-full border border-slate-300 dark:border-[#333] peer-checked:border-kinetic-primary flex items-center justify-center shrink-0 transition-colors z-10 duration-300">
                                        <div class="w-2.5 h-2.5 rounded-full bg-kinetic-primary scale-0 peer-checked:scale-100 transition-transform duration-300"></div>
                                    </div>
                                    <div class="z-10">
                                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors group-hover:text-kinetic-primary duration-300">Rentang Hari</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed mt-0.5">Peminjaman berlangsung multi-hari berturut-turut</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="dateAndTimeGrid">
                            <!-- Block Tanggal (Kiri) -->
                            <div class="flex flex-col gap-4">
                                <div id="startDateCol" class="transition-all duration-300">
                                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3" id="startDateLabel">Tanggal Kegiatan <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/4 text-slate-400 dark:text-gray-500 text-lg group-focus-within:text-kinetic-primary transition-colors"></i>
                                        <input type="date" name="booking_date" id="bookingDate" required class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary focus:ring-2 focus:ring-kinetic-primary/20 hover:border-slate-300 dark:hover:border-[#3A3A3A] transition-all duration-300 [color-scheme:light] dark:[color-scheme:dark]">
                                    </div>
                                </div>
                                <div id="endDateCol" class="hidden transition-all duration-300">
                                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Tanggal Selesai <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/4 text-slate-400 dark:text-gray-500 text-lg group-focus-within:text-kinetic-primary transition-colors"></i>
                                        <input type="date" name="booking_end_date" id="bookingEndDate" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary focus:ring-2 focus:ring-kinetic-primary/20 hover:border-slate-300 dark:hover:border-[#3A3A3A] transition-all duration-300 [color-scheme:light] dark:[color-scheme:dark]">
                                    </div>
                                </div>
                            </div>

                            <!-- Block Waktu (Kanan) -->
                            <div class="flex flex-col gap-4">
                                <div class="transition-all duration-300">
                                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Waktu Mulai <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/4 text-slate-400 dark:text-gray-500 text-lg group-focus-within:text-kinetic-primary transition-colors"></i>
                                        <input type="time" name="start_time" id="startTime" required class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary focus:ring-2 focus:ring-kinetic-primary/20 hover:border-slate-300 dark:hover:border-[#3A3A3A] transition-all duration-300 [color-scheme:light] dark:[color-scheme:dark]">
                                    </div>
                                </div>
                                <div class="transition-all duration-300">
                                    <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Waktu Selesai <span class="text-red-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/4 text-slate-400 dark:text-gray-500 text-lg group-focus-within:text-kinetic-primary transition-colors"></i>
                                        <input type="time" name="end_time" id="endTime" required class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary focus:ring-2 focus:ring-kinetic-primary/20 hover:border-slate-300 dark:hover:border-[#3A3A3A] transition-all duration-300 [color-scheme:light] dark:[color-scheme:dark]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8 flex flex-col h-full justify-between">
                        <div class="space-y-8">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Alur Prosedur (SOP)</label>
                                <div id="workflowContainer" class="grid grid-cols-2 gap-4">
                                    <p class="col-span-2 text-sm text-slate-500 dark:text-gray-400">Pilih ruangan terlebih dahulu untuk melihat alur persetujuan</p>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Lampiran Dokumen</label>
                                <div id="documentsContainer" class="space-y-3">
                                    <p class="text-sm text-slate-500 dark:text-gray-400">Pilih ruangan dan alur kerja untuk melihat dokumen yang diperlukan</p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-kinetic-primary to-kinetic-secondary hover:from-teal-400 hover:to-cyan-400 text-slate-900 dark:text-white font-bold py-4 rounded-xl shadow-[0_0_20px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-1 flex items-center justify-center gap-2">
                                <span>Pesan Sekarang</span>
                                <i class="ph-bold ph-paper-plane-right hidden" id="loadingIcon"></i>
                            </button>
                            <p class="text-[10px] text-center text-red-500 dark:text-red-400 mt-4 font-bold uppercase tracking-wider">* Reservasi wajib dilakukan minimal H-7 dari tanggal pelaksanaan.</p>
                        </div>
                    </div>

                </div>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-4">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-500/10 text-kinetic-primary flex items-center justify-center shrink-0 border border-teal-100 dark:border-transparent">
                    <i class="ph-bold ph-shield-check text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Persetujuan Cepat</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Sistem otomatis mendeteksi ketersediaan ruang secara langsung.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-cyan-50 dark:bg-cyan-500/10 text-kinetic-tertiary flex items-center justify-center shrink-0 border border-cyan-100 dark:border-transparent">
                    <i class="ph-bold ph-git-branch text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Pelacakan Alur</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Pantau status pengajuan Anda dari tahap admin hingga persetujuan.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-transparent">
                    <i class="ph-bold ph-chart-bar text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Riwayat Digital</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Seluruh riwayat penggunaan dan lampiran tersimpan aman.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 transition-colors duration-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-kinetic-primary rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-kinetic-primary tracking-widest uppercase">Siaran Langsung</span>
                </div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Sistem Aktif</h4>
                <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Siap menerima permohonan reservasi Anda saat ini.</p>
            </div>
        </div>

    </div>

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
</x-app-layout>

<script>
    // Data workflows dari server (Laravel)
    const workflowsData = @json($workflows);
    const buildingsData = @json($buildings);
    const unitsData = @json($units);
    const currentUserUnit = @json(Auth::user()->unit);

    const roomSelect = document.getElementById('roomSelect');
    const scopeSelect = document.getElementById('scopeSelect');
    const workflowContainer = document.getElementById('workflowContainer');
    const documentsContainer = document.getElementById('documentsContainer');
    const workflowIdInput = document.getElementById('workflowIdInput');

    // Helper: cari alur umum unit
    function findUnitWorkflow(unitId) {
        return workflowsData.find(w => w.unit_id == unitId && w.room_id == null);
    }

    // Helper: cari unit
    function findUnit(unitId) {
        return unitsData.find(u => u.id == unitId);
    }

    // Helper: cari BEM Polinema
    function findBemUnit() {
        return unitsData.find(u => u.level === 'Organisasi' && (u.unit_name.toLowerCase().includes('bem') || u.parent_id === null));
    }

    // Helper: cari Pusat
    function findPusatUnit() {
        return unitsData.find(u => u.level === 'Pusat');
    }

    // Algoritma 3-Tier Dynamic Bridging versi Client-Side (JS)
    function resolveWorkflowChain(selectedRoom, eventScope) {
        let chain = [];
        const missingConfigurations = [];

        const borrowerUnit = currentUserUnit;
        const roomOwnerUnit = findUnit(selectedRoom.unit_id);

        if (!roomOwnerUnit) {
            return { chain, missingConfigurations: ['Unit pemilik ruangan tidak ditemukan.'] };
        }

        // ─── Tier 1: Internal Ormawa ──────────────────────────────────
        if (borrowerUnit.level === 'Organisasi') {
            const wf = findUnitWorkflow(borrowerUnit.id);
            if (!wf) {
                missingConfigurations.push(`Unit Anda (${borrowerUnit.unit_name}) belum mengonfigurasi alur persetujuan umum.`);
            } else {
                (wf.steps || []).forEach(step => {
                    chain.push({
                        position_id: step.position_id,
                        position_name: step.position ? step.position.name : 'Posisi Tidak Diketahui',
                        tier: `Internal (${borrowerUnit.unit_name})`
                    });
                });
            }
        }

        // Jika memiliki unit induk yang juga bertipe Organisasi (e.g. HMTI sebagai induk WRI)
        if (borrowerUnit.parent_id) {
            const parentOrg = findUnit(borrowerUnit.parent_id);
            if (parentOrg && parentOrg.level === 'Organisasi') {
                const wf = findUnitWorkflow(parentOrg.id);
                if (wf && wf.steps && wf.steps.length > 0) {
                    const lastStep = wf.steps[wf.steps.length - 1];
                    chain.push({
                        position_id: lastStep.position_id,
                        position_name: lastStep.position ? lastStep.position.name : 'Ketua Unit Induk',
                        tier: `Induk (${parentOrg.unit_name})`
                    });
                }
            }
        }

        // ─── Tier 2a: BEM Polinema ─────────────────────────────────
        if (
            borrowerUnit.level === 'Organisasi'
            && !borrowerUnit.unit_name.toLowerCase().includes('bem')
            && !borrowerUnit.unit_name.toLowerCase().includes('perwakilan')
        ) {
            const bem = findBemUnit();
            if (bem && bem.id !== borrowerUnit.id) {
                const wf = findUnitWorkflow(bem.id);
                if (!wf) {
                    missingConfigurations.push(`Unit BEM Polinema (${bem.unit_name}) belum mengonfigurasi alur persetujuan umum.`);
                } else {
                    (wf.steps || []).forEach(step => {
                        const posName = step.position ? step.position.name : '';
                        if (posName.toLowerCase().includes('pembina') || posName.toLowerCase().includes('dpk')) {
                            return; // Skip BEM DPK for other units
                        }
                        chain.push({
                            position_id: step.position_id,
                            position_name: step.position ? step.position.name : 'Posisi Tidak Diketahui',
                            tier: `BEM (${bem.unit_name})`
                        });
                    });
                }
            }
        }

        // ─── Tier 2a (Part 2): Pembina (DPK) ─────────────────────────────────
        if (borrowerUnit.level === 'Organisasi') {
            // Cari Pembina (DPK)
            let departmentUnit = null;
            let currentUnit = borrowerUnit;
            while (currentUnit) {
                if (currentUnit.level === 'Jurusan') {
                    departmentUnit = currentUnit;
                    break;
                }
                currentUnit = currentUnit.parent_id ? findUnit(currentUnit.parent_id) : null;
            }

            let pembina = null;
            let labelSuffix = borrowerUnit.unit_name;

            if (departmentUnit) {
                const fullDept = findUnit(departmentUnit.id);
                if (fullDept && fullDept.positions) {
                    pembina = fullDept.positions.find(p => {
                        const isPembinaOrDpk = p.name.toLowerCase().includes('pembina') || p.name.toLowerCase().includes('dpk');
                        const hasUser = p.users && p.users.length > 0;
                        return isPembinaOrDpk && hasUser;
                    });
                }
                labelSuffix = departmentUnit.unit_name;
                if (!pembina) {
                    missingConfigurations.push(`Jabatan DPK (Pembina) untuk ${departmentUnit.unit_name} belum dikonfigurasi atau tidak memiliki user aktif.`);
                }
            } else {
                const fullBorrower = findUnit(borrowerUnit.id);
                if (fullBorrower && fullBorrower.positions) {
                    pembina = fullBorrower.positions.find(p => {
                        const isPembinaOrDpk = p.name.toLowerCase().includes('pembina') || p.name.toLowerCase().includes('dpk');
                        const hasUser = p.users && p.users.length > 0;
                        if (!isPembinaOrDpk || !hasUser) {
                            return false;
                        }
                        const wf = findUnitWorkflow(borrowerUnit.id);
                        if (!wf || !wf.steps) {
                            return false;
                        }
                        return wf.steps.some(step => step.position_id == p.id);
                    });
                }
                labelSuffix = borrowerUnit.unit_name;
                if (!pembina) {
                    missingConfigurations.push(`Jabatan DPK (Pembina) untuk ${borrowerUnit.unit_name} belum dikonfigurasi, tidak memiliki user aktif, atau belum terdaftar di SOP alur persetujuan umum.`);
                }
            }

            if (pembina) {
                chain.push({
                    position_id: pembina.id,
                    position_name: pembina.name,
                    tier: `DPK (${labelSuffix})`
                });
            }
        }

        // ─── Tier 2b: Pemilik Ruangan ────────────────────────────────────────
        if (roomOwnerUnit.id !== borrowerUnit.id) {
            const wf = findUnitWorkflow(roomOwnerUnit.id);
            if (!wf) {
                missingConfigurations.push(`Unit pemilik ruangan (${roomOwnerUnit.unit_name}) belum mengonfigurasi alur persetujuan umum.`);
            } else {
                (wf.steps || []).forEach(step => {
                    chain.push({
                        position_id: step.position_id,
                        position_name: step.position ? step.position.name : 'Posisi Tidak Diketahui',
                        tier: `Pemilik Ruangan (${roomOwnerUnit.unit_name})`
                    });
                });
            }
        }

        // De-duplicate chain by position_id to prevent redundant consecutive approvals in preview
        const uniqueChain = [];
        const seenPositions = new Set();
        for (const item of chain) {
            if (item.position_id && !seenPositions.has(item.position_id)) {
                uniqueChain.push(item);
                seenPositions.add(item.position_id);
            } else if (!item.position_id) {
                if (!seenPositions.has(item.position_name)) {
                    uniqueChain.push(item);
                    seenPositions.add(item.position_name);
                }
            }
        }

        return { chain: uniqueChain, missingConfigurations };
    }

    // Fungsi Update SOP dinamis saat form berubah
    function updateSOP() {
        const selectedRoomId = roomSelect.value;
        const eventScope = scopeSelect.value;
        
        const roomDetailsContainer = document.getElementById('roomDetailsContainer');
        const roomCapacityBadge = document.getElementById('roomCapacityBadge');
        const roomDescText = document.getElementById('roomDescText');
        const roomFacilitiesContainer = document.getElementById('roomFacilitiesContainer');
        
        if (!selectedRoomId) {
            workflowContainer.innerHTML = '<p class="col-span-2 text-sm text-slate-500 dark:text-gray-400">Pilih ruangan terlebih dahulu untuk melihat alur persetujuan</p>';
            documentsContainer.innerHTML = '<p class="text-sm text-slate-500 dark:text-gray-400">Pilih ruangan terlebih dahulu untuk melihat dokumen yang diperlukan</p>';
            workflowIdInput.value = '';
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').classList.add('opacity-50', 'cursor-not-allowed');
            if (roomDetailsContainer) roomDetailsContainer.classList.add('hidden');
            return;
        }

        let selectedRoom = null;
        buildingsData.forEach(building => {
            const room = building.rooms.find(r => r.id == selectedRoomId);
            if (room) selectedRoom = room;
        });

        if (!selectedRoom) return;

        // Render detail & fasilitas ruangan
        if (roomDetailsContainer) {
            roomDetailsContainer.classList.remove('hidden');
            roomCapacityBadge.textContent = `Kapasitas: ${selectedRoom.capacity || 0} orang`;
            roomDescText.textContent = selectedRoom.description || 'Tidak ada deskripsi.';
            
            roomFacilitiesContainer.innerHTML = '';
            if (selectedRoom.facilities) {
                const facilitiesList = selectedRoom.facilities.split(',').map(f => f.trim()).filter(Boolean);
                if (facilitiesList.length > 0) {
                    facilitiesList.forEach(fac => {
                        const badge = document.createElement('span');
                        badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-1 bg-teal-50 dark:bg-kinetic-primary/10 border border-teal-200/60 dark:border-kinetic-primary/20 rounded-lg text-[10px] font-bold text-teal-800 dark:text-kinetic-secondary transition-all';
                        badge.innerHTML = `<span class="w-1.5 h-1.5 bg-kinetic-primary rounded-full animate-pulse"></span> ${fac}`;
                        roomFacilitiesContainer.appendChild(badge);
                    });
                } else {
                    roomFacilitiesContainer.innerHTML = '<span class="text-[10px] text-slate-400 dark:text-gray-600">Tidak ada informasi fasilitas.</span>';
                }
            } else {
                roomFacilitiesContainer.innerHTML = '<span class="text-[10px] text-slate-400 dark:text-gray-600">Tidak ada informasi fasilitas.</span>';
            }
        }

        // Hitung dynamic chain
        const result = resolveWorkflowChain(selectedRoom, eventScope);

        // Jika ada unit di chain yang belum dikonfigurasi, tampilkan detail pesan spesifik
        if (result.missingConfigurations.length > 0) {
            let errorsHTML = `
                <div class="col-span-2 bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 rounded-xl p-4 flex items-start gap-3">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-rose-800 dark:text-rose-300">Konfigurasi Alur Belum Lengkap</h4>
                        <ul class="text-[11px] text-rose-600 dark:text-rose-400 mt-2 list-disc list-inside space-y-1">
                            ${result.missingConfigurations.map(err => `<li>${err}</li>`).join('')}
                        </ul>
                        <p class="text-[10px] text-rose-500 dark:text-rose-400 mt-2">Silakan hubungi admin unit terkait untuk menyelesaikan konfigurasi agar ruangan dapat dipinjam.</p>
                    </div>
                </div>`;
            workflowContainer.innerHTML = errorsHTML;
            documentsContainer.innerHTML = '';
            workflowIdInput.value = '';
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').classList.add('opacity-50', 'cursor-not-allowed');
            return;
        }

        document.getElementById('submitBtn').disabled = false;
        document.getElementById('submitBtn').classList.remove('opacity-50', 'cursor-not-allowed');

        // Gunakan alur unit pemilik sebagai workflow_id utama form
        const ownerWorkflow = findUnitWorkflow(selectedRoom.unit_id);
        workflowIdInput.value = ownerWorkflow.id;

        // Render alur langkah visual
        let stepsHTML = '<div class="col-span-2 relative pl-2 pt-2">';
        stepsHTML += '<div class="absolute left-[19px] top-6 bottom-4 w-px bg-slate-200 dark:bg-[#2A2A2A]"></div>';
        stepsHTML += '<div class="space-y-4">';
        
        if (result.chain.length > 0) {
            result.chain.forEach((step, index) => {
                stepsHTML += `
                    <div class="relative flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 border-2 border-kinetic-primary text-kinetic-primary flex items-center justify-center text-[11px] font-bold z-10 shrink-0 shadow-[0_0_10px_rgba(20,184,166,0.3)] bg-white dark:bg-[#151515]">
                            ${index + 1}
                        </div>
                        <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 flex-1 group hover:border-kinetic-primary/50 transition-colors">
                            <div class="flex justify-between items-center mb-0.5">
                                <p class="text-[9px] text-slate-400 dark:text-gray-500 font-bold uppercase tracking-widest">Langkah ${index + 1}</p>
                                <span class="text-[8px] bg-teal-500/10 text-kinetic-primary px-1.5 py-0.5 rounded-full font-bold uppercase">${step.tier}</span>
                            </div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">${step.position_name}</p>
                        </div>
                    </div>
                `;
            });
        } else {
            stepsHTML += `
                <div class="relative flex items-center gap-4">
                    <div class="w-8 h-8 rounded-full bg-green-50 dark:bg-green-500/10 border-2 border-green-500 text-green-500 flex items-center justify-center text-[11px] font-bold z-10 shrink-0 bg-white dark:bg-[#151515]">
                        <i class="ph-bold ph-check"></i>
                    </div>
                    <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3 flex-1">
                        <p class="text-[9px] text-slate-400 dark:text-gray-500 font-bold uppercase tracking-widest mb-0.5">Otomatis</p>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">Tanpa Persetujuan Khusus</p>
                    </div>
                </div>
            `;
        }
        
        stepsHTML += '</div></div>';
        workflowContainer.innerHTML = stepsHTML;

        // Render dokumen syarat dari unit pemilik ruangan
        displayDocumentsForRoomOwner(selectedRoom.unit_id);
    }

    function displayDocumentsForRoomOwner(roomOwnerUnitId) {
        const workflow = findUnitWorkflow(roomOwnerUnitId);
        if (!workflow || !workflow.requirements) {
            documentsContainer.innerHTML = '<p class="text-sm text-slate-500 dark:text-gray-400">Tidak ada dokumen yang diperlukan</p>';
            return;
        }

        let documentsHTML = '';
        if (workflow.requirements.length === 0) {
            documentsHTML = '<p class="text-sm text-slate-500 dark:text-gray-400">Tidak ada dokumen yang diperlukan untuk alur ini</p>';
        } else {
            workflow.requirements.forEach(req => {
                const mandatoryText = '<span class="text-red-500">(Wajib)</span>';
                
                documentsHTML += `
                    <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer relative overflow-hidden" 
                         onclick="document.getElementById('req_${req.id}').click()">
                        
                        <input type="file" name="requirement_${req.id}" id="req_${req.id}" class="hidden" 
                               onchange="updateFileName(this, 'filename_${req.id}', 'icon_${req.id}')" 
                               data-mandatory="true" data-name="${req.document_name}">

                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-slate-400 dark:text-gray-400 transition-colors" id="iconBox_${req.id}">
                                <i class="ph ph-file-text text-xl" id="icon_${req.id}"></i>
                            </div>
                            <div class="flex-1 pr-4">
                                <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5 truncate">${req.document_name}</p>
                                <p class="text-[10px] text-slate-500 dark:text-gray-500 truncate" id="filename_${req.id}">Silakan upload file PDF/Docx ${mandatoryText}</p>
                            </div>
                        </div>
                        <i class="ph ph-cloud-arrow-up text-xl text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                    </div>
                `;
            });
        }
        documentsContainer.innerHTML = documentsHTML;
    }

    roomSelect.addEventListener('change', updateSOP);
    scopeSelect.addEventListener('change', updateSOP);

    function updateFileName(input, textId, iconId) {
        const textElement = document.getElementById(textId);
        const iconElement = document.getElementById(iconId);
        
        if (input.files && input.files.length > 0) {
            textElement.textContent = input.files[0].name;
            textElement.classList.add('text-kinetic-primary');
            iconElement.classList.replace('ph-file-text', 'ph-check-circle');
            iconElement.classList.add('text-kinetic-primary');
        } else {
            textElement.innerHTML = 'Silakan upload file PDF/Docx';
            textElement.classList.remove('text-kinetic-primary');
            iconElement.classList.replace('ph-check-circle', 'ph-file-text');
            iconElement.classList.remove('text-kinetic-primary');
        }
    }

    // LOGIKA RENTANG HARI / SINGLE DAY & BLOKIR 7 HARI & AUTO-FILL
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('bookingDate');
        const bookingEndDateInput = document.getElementById('bookingEndDate');
        const endDateCol = document.getElementById('endDateCol');
        const startDateCol = document.getElementById('startDateCol');
        const rentalTypeRadios = document.querySelectorAll('input[name="rental_type"]');
        const startDateLabel = document.getElementById('startDateLabel');

        rentalTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'range') {
                    endDateCol.classList.remove('hidden');
                    bookingEndDateInput.required = true;
                    startDateLabel.innerHTML = 'Tanggal Mulai <span class="text-red-500">*</span>';
                } else {
                    endDateCol.classList.add('hidden');
                    bookingEndDateInput.required = false;
                    bookingEndDateInput.value = '';
                    startDateLabel.innerHTML = 'Tanggal Kegiatan <span class="text-red-500">*</span>';
                }
            });
        });

        // 1. Hitung tanggal Minimum (Hari ini + 7 Hari)
        const today = new Date();
        const minDate = new Date();
        minDate.setDate(today.getDate() + 7);

        const yyyy = minDate.getFullYear();
        const mm = String(minDate.getMonth() + 1).padStart(2, '0');
        const dd = String(minDate.getDate()).padStart(2, '0');
        const minDateString = `${yyyy}-${mm}-${dd}`;

        // 2. Terapkan batas minimum ke input kalender
        if(dateInput) {
            dateInput.setAttribute('min', minDateString);
            dateInput.addEventListener('change', function() {
                if (bookingEndDateInput) {
                    bookingEndDateInput.setAttribute('min', this.value);
                    if (bookingEndDateInput.value && bookingEndDateInput.value < this.value) {
                        bookingEndDateInput.value = this.value;
                    }
                }
            });
        }

        if (bookingEndDateInput) {
            bookingEndDateInput.setAttribute('min', minDateString);
        }

        // 3. Handle Auto-fill dari URL (Misal dari Rekomendasi)
        const urlParams = new URLSearchParams(window.location.search);
        const roomId = urlParams.get('room_id');
        const date = urlParams.get('date');
        const startTime = urlParams.get('start_time');

        if (roomId) {
            const select = document.getElementById('roomSelect');
            if(select) {
                select.value = roomId;
                select.dispatchEvent(new Event('change'));
            }
        }
        
        if (date) {
            // Jika tanggal dari rekomendasi < batas minimal, kita set ke batas minimal saja
            if (dateInput && date < minDateString) {
                dateInput.value = minDateString;
            } else if (dateInput) {
                dateInput.value = date;
            }
        }
        
        if (startTime) {
            const timeInput = document.getElementById('startTime');
            if(timeInput) timeInput.value = startTime;
        }
    });

    document.getElementById('bookingForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Manual validation for required documents
        const fileInputs = this.querySelectorAll('input[type="file"][data-mandatory="true"]');
        let missingDocs = [];
        fileInputs.forEach(input => {
            if (!input.files || input.files.length === 0) {
                const docName = input.getAttribute('data-name');
                missingDocs.push(docName);
            }
        });

        if (missingDocs.length > 0) {
            Alpine.$data(document.getElementById('global-modal-container')).showAlert(
                'Dokumen Belum Lengkap',
                'Harap unggah dokumen wajib berikut: ' + missingDocs.join(', '),
                'warning'
            );
            return;
        }
        
        const form = this;
        const roomSelectEl = document.getElementById('roomSelect');
        const roomName = roomSelectEl.options[roomSelectEl.selectedIndex]?.text || 'Ruangan';
        const eventName = document.getElementById('eventName').value;
        const rentalType = document.querySelector('input[name="rental_type"]:checked')?.value;
        const bookingDate = document.getElementById('bookingDate').value;
        let dateText = bookingDate;
        if (rentalType === 'range') {
            const bookingEndDate = document.getElementById('bookingEndDate').value;
            if (bookingEndDate) {
                dateText += ' s/d ' + bookingEndDate;
            }
        }
        const startTime = document.getElementById('startTime').value;
        const endTime = document.getElementById('endTime').value;

        const modalContainer = Alpine.$data(document.getElementById('global-modal-container'));
        modalContainer.showConfirm(
            'Konfirmasi Pemesanan',
            `Apakah Anda yakin ingin memesan "${roomName}" untuk kegiatan "${eventName}" pada tanggal ${dateText} pukul ${startTime} - ${endTime} WIB?`,
            async () => {
                const submitBtn = document.getElementById('submitBtn');
                const btnText = submitBtn.querySelector('span');
                const loadingIcon = document.getElementById('loadingIcon');

                submitBtn.disabled = true;
                btnText.textContent = 'Memproses...';
                loadingIcon.classList.remove('hidden');
                loadingIcon.classList.add('animate-pulse');

                try {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });

                    let data = {};
                    try {
                        data = await response.json();
                    } catch (e) {
                        console.error('Failed to parse JSON response', e);
                    }

                    if (response.ok) {
                        modalContainer.showAlert('Berhasil', 'Booking Anda telah diajukan.', 'success', () => {
                            window.location.href = "{{ route('riwayat') }}";
                        });
                    } else {
                        if (response.status === 419) {
                            modalContainer.showAlert('Sesi Berakhir', 'Sesi Anda telah berakhir. Silakan segarkan halaman (Refresh) dan coba lagi.', 'warning');
                            return;
                        }
                        
                        if (data.errors) {
                            const errorMessages = Object.values(data.errors).flat().join('\n');
                            modalContainer.showAlert('Gagal Validasi', errorMessages, 'warning');
                        } else {
                            modalContainer.showAlert('Peringatan', data.error || data.message || 'Terjadi kesalahan, pastikan semua form dan dokumen terisi.', 'warning');
                        }
                    }
                } catch (error) {
                    console.error('Fetch error:', error);
                    modalContainer.showAlert('Gagal', 'Gagal terhubung ke server. Silakan coba lagi.', 'danger');
                } finally {
                    submitBtn.disabled = false;
                    btnText.textContent = 'Pesan Sekarang';
                    loadingIcon.classList.add('hidden');
                    loadingIcon.classList.remove('animate-pulse');
                }
            },
            'info',
            'Pesan Sekarang'
        );
    });
</script>