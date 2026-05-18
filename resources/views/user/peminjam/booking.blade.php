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

        <form id="bookingForm" action="{{ route('booking.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="workflow_id" id="workflowIdInput" value="">

            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none rounded-3xl p-8 lg:p-10 transition-colors duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    
                    <div class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Nama Kegiatan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="ph ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                <input type="text" name="event_name" id="eventName" required placeholder="Cth: Rapat Evaluasi Bulanan"
                                    class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Pilih Ruangan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <i class="ph ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                <select id="roomSelect" name="room_id" required
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Tanggal <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                    <input type="date" name="booking_date" id="bookingDate" required class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Mulai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                    <input type="time" name="start_time" id="startTime" required class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Selesai <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <i class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                    <input type="time" name="end_time" id="endTime" required class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
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
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Approval Cepat</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Sistem otomatis mendeteksi ketersediaan ruang secara real-time.</p>
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
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Log Digital</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Seluruh riwayat penggunaan dan lampiran tersimpan aman.</p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] shadow-sm dark:shadow-none rounded-2xl p-6 transition-colors duration-300">
                <div class="flex items-center gap-2 mb-4">
                    <span class="w-2 h-2 bg-kinetic-primary rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-bold text-kinetic-primary tracking-widest uppercase">Live Insight</span>
                </div>
                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Sistem Aktif</h4>
                <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Siap menerima permohonan reservasi Anda saat ini.</p>
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
    const workflowIdInput = document.getElementById('workflowIdInput');

    // Handle room selection
    roomSelect.addEventListener('change', function() {
        const selectedRoomId = this.value;
        
        if (!selectedRoomId) {
            workflowContainer.innerHTML = '<p class="col-span-2 text-sm text-slate-500 dark:text-gray-400">Pilih ruangan terlebih dahulu untuk melihat alur persetujuan</p>';
            documentsContainer.innerHTML = '<p class="text-sm text-slate-500 dark:text-gray-400">Pilih ruangan dan workflow untuk melihat dokumen yang diperlukan</p>';
            workflowIdInput.value = '';
            return;
        }

        let selectedRoom = null;
        buildingsData.forEach(building => {
            const room = building.rooms.find(r => r.id == selectedRoomId);
            if (room) selectedRoom = room;
        });

        if (!selectedRoom) return;

        const applicableWorkflows = workflowsData.filter(w => w.unit_id == selectedRoom.unit_id);

        if (applicableWorkflows.length === 0) {
            workflowContainer.innerHTML = '<p class="col-span-2 text-sm text-red-500 dark:text-red-400">Tidak ada alur persetujuan untuk ruangan ini. Hubungi Admin.</p>';
            documentsContainer.innerHTML = '';
            workflowIdInput.value = '';
            return;
        }

        let workflowHTML = '';
        applicableWorkflows.forEach((workflow, index) => {
            const isSelected = index === 0;
            workflowHTML += `
                <button type="button" 
                        class="workflow-btn bg-slate-50 dark:bg-[#1A1A1A] border rounded-xl p-5 text-left transition-colors relative overflow-hidden ${isSelected ? 'border-kinetic-primary bg-teal-50 dark:bg-kinetic-primary/10' : 'border-slate-200 dark:border-[#2A2A2A] hover:border-slate-400 dark:hover:border-gray-500'}"
                        data-workflow-id="${workflow.id}"
                        onclick="selectWorkflow(${workflow.id})">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="font-bold ${isSelected ? 'text-slate-900 dark:text-white' : 'text-slate-600 dark:text-gray-300'} text-sm mb-1">${workflow.name}</p>
                            <p class="text-[10px] text-slate-500 dark:text-gray-500">${workflow.description || 'Tidak ada deskripsi'}</p>
                        </div>
                        ${isSelected ? '<i class="ph-fill ph-check-circle text-kinetic-primary text-xl relative z-10"></i>' : ''}
                    </div>
                </button>
            `;
        });
        workflowContainer.innerHTML = workflowHTML;

        if (applicableWorkflows.length > 0) {
            selectWorkflow(applicableWorkflows[0].id);
        }
    });

    function selectWorkflow(workflowId) {
        workflowIdInput.value = workflowId;

        document.querySelectorAll('.workflow-btn').forEach(btn => {
            if (btn.dataset.workflowId == workflowId) {
                btn.classList.remove('border-slate-200', 'dark:border-[#2A2A2A]');
                btn.classList.add('border-kinetic-primary', 'bg-teal-50', 'dark:bg-kinetic-primary/10');
                if(!btn.innerHTML.includes('ph-check-circle')) {
                    btn.innerHTML = btn.innerHTML.replace('</div>\n                </button>', '<i class="ph-fill ph-check-circle text-kinetic-primary text-xl relative z-10"></i>\n                    </div>\n                </button>');
                }
            } else {
                btn.classList.remove('border-kinetic-primary', 'bg-teal-50', 'dark:bg-kinetic-primary/10');
                btn.classList.add('border-slate-200', 'dark:border-[#2A2A2A]');
                btn.innerHTML = btn.innerHTML.replace(/<i class="ph-fill ph-check-circle.*?<\/i>/g, '');
            }
        });

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
                const mandatoryText = req.is_mandatory ? '<span class="text-red-500">(Wajib)</span>' : '<span class="text-slate-400">(Opsional)</span>';
                
                documentsHTML += `
                    <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer relative overflow-hidden" 
                         onclick="document.getElementById('req_${req.id}').click()">
                        
                        <input type="file" name="requirement_${req.id}" id="req_${req.id}" class="hidden" 
                               onchange="updateFileName(this, 'filename_${req.id}', 'icon_${req.id}')" 
                               ${req.is_mandatory ? 'required' : ''}>

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

    // LOGIKA BLOKIR 7 HARI & AUTO-FILL
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('bookingDate');
        
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
        
        const form = this;
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
                alert('Berhasil! Booking Anda telah diajukan.');
                window.location.href = "{{ route('riwayat') }}";
            } else {
                if (response.status === 419) {
                    alert('Sesi Anda telah berakhir. Silakan segarkan halaman (Refresh) dan coba lagi.');
                    return;
                }
                
                if (data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    alert('Gagal: \n' + errorMessages);
                } else {
                    alert(data.error || data.message || 'Terjadi kesalahan, pastikan semua form dan dokumen terisi.');
                }
            }
        } catch (error) {
            console.error('Fetch error:', error);
            alert('Gagal terhubung ke server. Silakan coba lagi.');
        } finally {
            submitBtn.disabled = false;
            btnText.textContent = 'Pesan Sekarang';
            loadingIcon.classList.add('hidden');
            loadingIcon.classList.remove('animate-pulse');
        }
    });
</script>