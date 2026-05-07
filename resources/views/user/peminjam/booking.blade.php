<x-app-layout title="Booking">
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

        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none rounded-3xl p-8 lg:p-10 transition-colors duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Pilih Ruangan</label>
                        <div class="relative">
                            <i class="ph ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                            <select id="roomSelect" 
                                    class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors">
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($buildings as $building)
                                    <optgroup label="{{ $building->building_name }}">
                                        @foreach($building->rooms as $room)
                                            <option value="{{ $room->id }}" data-unit-id="{{ $room->unit_id }}" data-capacity="{{ $room->capacity }}">
                                                {{ $room->room_name }} (Kapasitas: {{ $room->capacity }} orang)
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Tanggal Penggunaan</label>
                            <div class="relative">
                                <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                <input type="date" id="bookingDate" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Waktu Pelaksanaan</label>
                            <div class="relative">
                                <i class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                <input type="time" id="startTime" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Alur Prosedur (SOP)</label>
                        <div id="workflowContainer" class="grid grid-cols-2 gap-4">
                            <p class="col-span-2 text-sm text-slate-500 dark:text-gray-400">Pilih ruangan terlebih dahulu untuk melihat alur persetujuan</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col h-full justify-between">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Lampiran Dokumen</label>
                        <div id="documentsContainer" class="space-y-3">
                            <p class="text-sm text-slate-500 dark:text-gray-400">Pilih ruangan dan workflow untuk melihat dokumen yang diperlukan</p>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button class="w-full bg-gradient-to-r from-kinetic-primary to-kinetic-secondary hover:from-teal-400 hover:to-cyan-400 text-slate-900 dark:text-white font-bold py-4 rounded-xl shadow-[0_0_20px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-1">
                            Pesan Sekarang
                        </button>
                        <p class="text-[10px] text-center text-slate-500 dark:text-gray-500 mt-4">*Proses verifikasi dokumen memakan waktu ±24 jam hari kerja.</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-4">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-500/10 text-kinetic-primary flex items-center justify-center shrink-0 border border-teal-100 dark:border-transparent">
                    <i class="ph-bold ph-shield-check text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Approval Cepat</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Sistem otomatis mendeteksi ketersediaan ruang secara real-time untuk mempercepat birokrasi.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-cyan-50 dark:bg-cyan-500/10 text-kinetic-tertiary flex items-center justify-center shrink-0 border border-cyan-100 dark:border-transparent">
                    <i class="ph-bold ph-git-branch text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Pelacakan Alur</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Pantau status pengajuan Anda dari tahap admin hingga persetujuan rektorat dalam satu dashboard.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-transparent">
                    <i class="ph-bold ph-chart-bar text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Log Digital</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Seluruh riwayat penggunaan dan lampiran tersimpan aman untuk kebutuhan laporan pertanggungjawaban.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 transition-colors duration-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-kinetic-primary rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-kinetic-primary tracking-widest uppercase">Live Insight</span>
                </div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">12 Ruangan Tersedia</h4>
                <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Gedung Fakultas Teknik memiliki slot terbanyak saat ini.</p>
            </div>
        </div>

    </div>
</x-app-layout>

<script>
    // Data workflows dari server (Laravel)
    const workflowsData = @json($workflows);
    const buildingsData = @json($buildings);

    const roomSelect = document.getElementById('roomSelect');
    const workflowContainer = document.getElementById('workflowContainer');
    const documentsContainer = document.getElementById('documentsContainer');

    // Handle room selection
    roomSelect.addEventListener('change', function() {
        const selectedRoomId = this.value;
        
        if (!selectedRoomId) {
            workflowContainer.innerHTML = '<p class="col-span-2 text-sm text-slate-500 dark:text-gray-400">Pilih ruangan terlebih dahulu untuk melihat alur persetujuan</p>';
            documentsContainer.innerHTML = '<p class="text-sm text-slate-500 dark:text-gray-400">Pilih ruangan dan workflow untuk melihat dokumen yang diperlukan</p>';
            return;
        }

        // Find the selected room and its unit
        let selectedRoom = null;
        buildingsData.forEach(building => {
            const room = building.rooms.find(r => r.id == selectedRoomId);
            if (room) selectedRoom = room;
        });

        if (!selectedRoom) return;

        // Get workflows for this room's unit
        const applicableWorkflows = workflowsData.filter(w => w.unit_id == selectedRoom.unit_id);

        if (applicableWorkflows.length === 0) {
            workflowContainer.innerHTML = '<p class="col-span-2 text-sm text-slate-500 dark:text-gray-400">Tidak ada alur persetujuan untuk ruangan ini</p>';
            documentsContainer.innerHTML = '';
            return;
        }

        // Display workflows as buttons
        let workflowHTML = '';
        applicableWorkflows.forEach((workflow, index) => {
            const isSelected = index === 0;
            workflowHTML += `
                <button type="button" 
                        class="workflow-btn bg-slate-50 dark:bg-[#1A1A1A] border rounded-xl p-5 text-left transition-colors ${isSelected ? 'border-kinetic-primary bg-teal-50 dark:bg-kinetic-primary/10' : 'border-slate-200 dark:border-[#2A2A2A] hover:border-slate-400 dark:hover:border-gray-500'}"
                        data-workflow-id="${workflow.id}"
                        onclick="selectWorkflow(${workflow.id})">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-bold ${isSelected ? 'text-slate-900 dark:text-white' : 'text-slate-600 dark:text-gray-300'} text-sm mb-1">${workflow.name}</p>
                            <p class="text-[10px] text-slate-500 dark:text-gray-500">${workflow.description || 'Tidak ada deskripsi'}</p>
                        </div>
                        ${isSelected ? '<i class="ph-fill ph-check-circle text-kinetic-primary text-xl"></i>' : ''}
                    </div>
                </button>
            `;
        });
        workflowContainer.innerHTML = workflowHTML;

        // Display documents for the first workflow
        if (applicableWorkflows.length > 0) {
            displayDocuments(applicableWorkflows[0].id);
        }
    });

    function selectWorkflow(workflowId) {
        // Update button styles
        document.querySelectorAll('.workflow-btn').forEach(btn => {
            if (btn.dataset.workflowId == workflowId) {
                btn.classList.remove('border-slate-200', 'dark:border-[#2A2A2A]');
                btn.classList.add('border-kinetic-primary', 'bg-teal-50', 'dark:bg-kinetic-primary/10');
                btn.innerHTML = btn.innerHTML.replace('</button>', '<i class="ph-fill ph-check-circle text-kinetic-primary text-xl absolute top-4 right-4"></i></button>');
            } else {
                btn.classList.remove('border-kinetic-primary', 'bg-teal-50', 'dark:bg-kinetic-primary/10');
                btn.classList.add('border-slate-200', 'dark:border-[#2A2A2A]');
                btn.innerHTML = btn.innerHTML.replace(/<i class="ph-fill ph-check-circle.*?<\/i>/g, '');
            }
        });

        // Display documents for selected workflow
        displayDocuments(workflowId);
    }

    function displayDocuments(workflowId) {
        const workflow = workflowsData.find(w => w.id == workflowId);
        if (!workflow || !workflow.requirements) {
            documentsContainer.innerHTML = '<p class="text-sm text-slate-500 dark:text-gray-400">Tidak ada dokumen yang diperlukan</p>';
            return;
        }

        let documentsHTML = '';
        if (workflow.requirements.length === 0) {
            documentsHTML = '<p class="text-sm text-slate-500 dark:text-gray-400">Tidak ada dokumen yang diperlukan untuk alur ini</p>';
        } else {
            workflow.requirements.forEach(req => {
                const mandatoryText = req.is_mandatory ? '(Wajib)' : '(Opsional)';
                documentsHTML += `
                    <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer">
                        <div class="flex items-center gap-4 flex-1">
                            <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-slate-400 dark:text-gray-400">
                                <i class="ph ph-file-text text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">${req.document_name}</p>
                                <p class="text-[10px] text-slate-500 dark:text-gray-500">${req.description || 'Silakan upload file'} ${mandatoryText}</p>
                            </div>
                        </div>
                        <i class="ph ph-cloud-arrow-up text-xl text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                    </div>
                `;
            });
        }
        documentsContainer.innerHTML = documentsHTML;
    }

    // Auto-fill form berdasarkan Parameter URL (Dari Rekomendasi Jadwal)
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const roomId = urlParams.get('room_id');
        const date = urlParams.get('date');
        const startTime = urlParams.get('start_time');

        // Isi otomatis Ruangan
        if (roomId) {
            const roomSelect = document.getElementById('roomSelect');
            if(roomSelect) {
                roomSelect.value = roomId;
                // Penting: Panggil event 'change' manual agar workflow SOP otomatis muncul
                roomSelect.dispatchEvent(new Event('change'));
            }
        }
        
        // Isi otomatis Tanggal
        if (date) {
            const dateInput = document.getElementById('bookingDate');
            if(dateInput) dateInput.value = date;
        }
        
        // Isi otomatis Waktu Pelaksanaan
        if (startTime) {
            const timeInput = document.getElementById('startTime');
            if(timeInput) timeInput.value = startTime;
        }
    });
</script>