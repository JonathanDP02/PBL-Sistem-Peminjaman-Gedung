<x-app-layout title="Reservasi Ruangan">
    <div class="relative px-8 pt-6 pb-20 space-y-10 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="max-w-4xl mx-auto w-full">
            <div class="flex items-center gap-2 mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-kinetic-primary shadow-[0_0_5px_rgba(45,212,191,0.5)]"></span>
                <span class="text-[10px] font-bold tracking-widest text-kinetic-primary uppercase">Sistem Reservasi Terpadu</span>
            </div>
            <h1 class="font-heading text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 transition-colors">
                Reservasi <span class="text-kinetic-primary">Tanpa Batas</span>
            </h1>
            <p class="text-sm text-slate-600 dark:text-slate-400 max-w-2xl transition-colors">
                Wujudkan kegiatan akademik dan organisasi Anda dengan akses mudah ke seluruh ruang pertemuan dan auditorium universitas.
            </p>
        </div>

        <div class="max-w-4xl mx-auto w-full">
            
            <div class="flex items-center justify-between mb-8 relative">
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-0.5 bg-slate-200 dark:bg-[#2A2A2A] z-0 transition-colors"></div>
                <div id="progressBar" class="absolute left-0 top-1/2 -translate-y-1/2 h-0.5 bg-kinetic-primary z-0 transition-all duration-500" style="width: 0%;"></div>
                
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div id="dot-1" class="w-8 h-8 rounded-full bg-kinetic-primary text-white dark:text-[#151515] flex items-center justify-center font-bold text-sm shadow-[0_0_15px_rgba(45,212,191,0.3)] transition-all duration-300">1</div>
                    <span class="text-[10px] font-bold text-kinetic-primary uppercase tracking-widest hidden sm:block">Jadwal & Lokasi</span>
                </div>
                
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div id="dot-2" class="w-8 h-8 rounded-full bg-white dark:bg-[#151515] border-2 border-slate-200 dark:border-[#2A2A2A] text-slate-400 dark:text-slate-500 flex items-center justify-center font-bold text-sm transition-all duration-300">2</div>
                    <span id="text-2" class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest hidden sm:block transition-colors">Alur Prosedur</span>
                </div>
                
                <div class="relative z-10 flex flex-col items-center gap-2">
                    <div id="dot-3" class="w-8 h-8 rounded-full bg-white dark:bg-[#151515] border-2 border-slate-200 dark:border-[#2A2A2A] text-slate-400 dark:text-slate-500 flex items-center justify-center font-bold text-sm transition-all duration-300">3</div>
                    <span id="text-3" class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest hidden sm:block transition-colors">Lampiran</span>
                </div>
            </div>

            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 md:p-10 shadow-xl dark:shadow-none overflow-hidden relative min-h-[400px] transition-colors">
                
                <div id="step-1" class="transition-all duration-500 ease-in-out transform translate-x-0 opacity-100 absolute inset-0 p-6 md:p-10 flex flex-col">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 transition-colors">Pilih Jadwal & Ruangan</h2>
                    
                    <div class="space-y-5 flex-1">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Pilih Ruangan</label>
                            <div class="relative">
                                <i class="ph ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                                <input type="text" id="inputRuangan" placeholder="Cari nama ruangan atau nomor gedung..." class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-kinetic-primary focus:border-kinetic-primary transition-colors placeholder:text-slate-400 dark:placeholder:text-slate-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Tanggal Penggunaan</label>
                                <div class="relative">
                                    <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                                    <input type="date" id="inputTanggal" class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-kinetic-primary focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Waktu Pelaksanaan</label>
                                <div class="relative">
                                    <i class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                                    <input type="time" id="inputWaktu" class="w-full pl-12 pr-4 py-3.5 bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm text-slate-900 dark:text-white focus:ring-kinetic-primary focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-[#2A2A2A] flex justify-end transition-colors">
                        <button onclick="nextStep(2)" class="px-8 py-3 rounded-xl bg-kinetic-primary text-white dark:text-[#151515] font-bold text-sm hover:bg-teal-400 transition-colors shadow-[0_0_15px_rgba(45,212,191,0.2)] flex items-center gap-2">
                            Lanjutkan <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div id="step-2" class="transition-all duration-500 ease-in-out transform translate-x-full opacity-0 pointer-events-none absolute inset-0 p-6 md:p-10 flex flex-col">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 transition-colors">Pilih Alur Prosedur (SOP)</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 flex-1">
                        <div onclick="selectSOP('A')" id="sop-card-A" class="bg-slate-50 dark:bg-[#1A1A1A] border-2 border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 cursor-pointer hover:border-slate-400 dark:hover:border-slate-500 transition-colors flex justify-between items-start group">
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white mb-1 transition-colors">SOP A</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 transition-colors">Kegiatan Internal Fakultas & Lab</p>
                            </div>
                            <div id="sop-check-A" class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-[#2A2A2A] flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-check text-white dark:text-[#1A1A1A] text-xs opacity-0"></i>
                            </div>
                        </div>

                        <div onclick="selectSOP('B')" id="sop-card-B" class="bg-slate-50 dark:bg-[#1A1A1A] border-2 border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 cursor-pointer hover:border-slate-400 dark:hover:border-slate-500 transition-colors flex justify-between items-start group">
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white mb-1 transition-colors">SOP B</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 transition-colors">Kegiatan Organisasi & Eksternal</p>
                            </div>
                            <div id="sop-check-B" class="w-6 h-6 rounded-full border-2 border-slate-300 dark:border-[#2A2A2A] flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-check text-white dark:text-[#1A1A1A] text-xs opacity-0"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-200 dark:border-[#2A2A2A] flex justify-between items-center transition-colors">
                        <button onclick="prevStep(1)" class="px-6 py-3 rounded-xl border border-slate-200 dark:border-[#2A2A2A] text-slate-700 dark:text-white font-bold text-sm hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <button onclick="nextStep(3)" class="px-8 py-3 rounded-xl bg-kinetic-primary text-white dark:text-[#151515] font-bold text-sm hover:bg-teal-400 transition-colors shadow-[0_0_15px_rgba(45,212,191,0.2)] flex items-center gap-2">
                            Lanjutkan <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <div id="step-3" class="transition-all duration-500 ease-in-out transform translate-x-full opacity-0 pointer-events-none absolute inset-0 p-6 md:p-10 flex flex-col">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 transition-colors">Unggah Lampiran Dokumen</h2>
                    
                    <div class="space-y-4 flex-1 overflow-y-auto custom-scrollbar pr-2">
                        
                        <label id="container-izin" class="relative block bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 hover:border-slate-400 dark:hover:border-slate-500 transition-colors cursor-pointer group">
                            <input type="file" id="file-izin" accept=".pdf" class="hidden" onchange="handleFileUpload(this, 'izin', ['pdf'])">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 overflow-hidden w-full">
                                    <div id="icon-bg-izin" class="w-10 h-10 shrink-0 rounded-lg bg-slate-200 dark:bg-[#2A2A2A] flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                                        <i id="icon-izin" class="ph-fill ph-file-pdf text-xl"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h4 id="title-izin" class="text-sm font-bold text-slate-900 dark:text-white transition-colors truncate">Izin Dekanat</h4>
                                        <p id="desc-izin" class="text-[10px] text-slate-500 truncate">Format PDF, Maks 5MB</p>
                                    </div>
                                </div>
                                <i id="action-icon-izin" class="ph-bold ph-cloud-arrow-up text-slate-400 text-lg group-hover:text-kinetic-primary transition-colors shrink-0 ml-2"></i>
                            </div>
                        </label>

                        <label id="container-proposal" class="relative block bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 hover:border-slate-400 dark:hover:border-slate-500 transition-colors cursor-pointer group">
                            <input type="file" id="file-proposal" accept=".pdf" class="hidden" onchange="handleFileUpload(this, 'proposal', ['pdf'])">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 overflow-hidden w-full">
                                    <div id="icon-bg-proposal" class="w-10 h-10 shrink-0 rounded-lg bg-slate-200 dark:bg-[#2A2A2A] flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                                        <i id="icon-proposal" class="ph-fill ph-file-text text-xl"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h4 id="title-proposal" class="text-sm font-bold text-slate-900 dark:text-white transition-colors truncate">Proposal Kegiatan</h4>
                                        <p id="desc-proposal" class="text-[10px] text-kinetic-primary font-bold truncate">Wajib untuk SOP B (PDF)</p>
                                    </div>
                                </div>
                                <i id="action-icon-proposal" class="ph-bold ph-cloud-arrow-up text-slate-400 text-lg group-hover:text-kinetic-primary transition-colors shrink-0 ml-2"></i>
                            </div>
                        </label>

                        <label id="container-ktm" class="relative block bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 hover:border-slate-400 dark:hover:border-slate-500 transition-colors cursor-pointer group">
                            <input type="file" id="file-ktm" accept=".jpg,.jpeg,.png" class="hidden" onchange="handleFileUpload(this, 'ktm', ['jpg', 'jpeg', 'png'])">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4 overflow-hidden w-full">
                                    <div id="icon-bg-ktm" class="w-10 h-10 shrink-0 rounded-lg bg-slate-200 dark:bg-[#2A2A2A] flex items-center justify-center text-slate-500 dark:text-slate-400 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                                        <i id="icon-ktm" class="ph-fill ph-identification-card text-xl"></i>
                                    </div>
                                    <div class="overflow-hidden">
                                        <h4 id="title-ktm" class="text-sm font-bold text-slate-900 dark:text-white transition-colors truncate">KTM Ketua Pelaksana</h4>
                                        <p id="desc-ktm" class="text-[10px] text-slate-500 truncate">Scan berwarna (JPG/PNG)</p>
                                    </div>
                                </div>
                                <i id="action-icon-ktm" class="ph-bold ph-cloud-arrow-up text-slate-400 text-lg group-hover:text-kinetic-primary transition-colors shrink-0 ml-2"></i>
                            </div>
                        </label>

                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-200 dark:border-[#2A2A2A] flex justify-between items-center transition-colors">
                        <button onclick="prevStep(2)" class="px-6 py-3 rounded-xl border border-slate-200 dark:border-[#2A2A2A] text-slate-700 dark:text-white font-bold text-sm hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <button onclick="submitForm()" class="px-8 py-3 rounded-xl bg-kinetic-primary text-white dark:text-[#151515] font-bold text-sm hover:bg-teal-400 transition-colors shadow-[0_0_20px_rgba(45,212,191,0.3)] flex items-center gap-2">
                            <i class="ph-bold ph-paper-plane-tilt"></i> Pesan Sekarang
                        </button>
                    </div>
                </div>

            </div>
            
            <p class="text-center text-[10px] text-slate-500 mt-4">*Proses verifikasi dokumen memakan waktu +-24 jam hari kerja.</p>
        </div>

    </div>

    <script>
        // ==========================================
        // 1. PEMBATASAN TANGGAL INPUT (H+7)
        // ==========================================
        document.addEventListener("DOMContentLoaded", function() {
            const inputTanggal = document.getElementById('inputTanggal');
            const hariIni = new Date();
            
            function formatTanggal(date) {
                let d = date.getDate().toString().padStart(2, '0');
                let m = (date.getMonth() + 1).toString().padStart(2, '0');
                let y = date.getFullYear();
                return `${y}-${m}-${d}`;
            }

            // Atur agar minimal pesan adalah 7 hari dari sekarang
            const minDate = new Date(hariIni);
            minDate.setDate(minDate.getDate() + 7);
            inputTanggal.setAttribute('min', formatTanggal(minDate));
        });

        // ==========================================
        // 2. LOGIKA FORM WIZARD
        // ==========================================
        let selectedSOP = null;

        function nextStep(targetStep) {
            // Validasi Sederhana
            if(targetStep === 2) {
                const r = document.getElementById('inputRuangan').value;
                const t = document.getElementById('inputTanggal').value;
                const w = document.getElementById('inputWaktu').value;
                if(!r || !t || !w) {
                    alert("Harap isi Ruangan, Tanggal, dan Waktu terlebih dahulu!");
                    return;
                }
            } else if(targetStep === 3) {
                if(!selectedSOP) {
                    alert("Harap pilih salah satu SOP (A atau B) terlebih dahulu!");
                    return;
                }
            }
            moveTo(targetStep, 'next');
        }

        function prevStep(targetStep) {
            moveTo(targetStep, 'prev');
        }

        function moveTo(target, direction) {
            const steps = [1, 2, 3];
            
            steps.forEach(step => {
                const el = document.getElementById(`step-${step}`);
                const dot = document.getElementById(`dot-${step}`);
                const text = document.getElementById(`text-${step}`);

                if(step === target) {
                    // Tampilkan target
                    el.classList.remove('translate-x-full', '-translate-x-full', 'opacity-0', 'pointer-events-none');
                    el.classList.add('translate-x-0', 'opacity-100');
                    
                    // Update Dot Indicator Aktif
                    dot.className = "w-8 h-8 rounded-full bg-kinetic-primary text-white dark:text-[#151515] flex items-center justify-center font-bold text-sm shadow-[0_0_15px_rgba(45,212,191,0.3)] transition-all duration-300";
                    if(text) { text.classList.remove('text-slate-400', 'dark:text-slate-500'); text.classList.add('text-kinetic-primary'); }
                } else {
                    // Sembunyikan yang lain
                    el.classList.remove('translate-x-0', 'opacity-100');
                    el.classList.add('opacity-0', 'pointer-events-none');
                    
                    if(step < target) {
                        el.classList.add('-translate-x-full');
                        el.classList.remove('translate-x-full');
                        
                        // Update Dot Indicator Selesai (Past)
                        dot.className = "w-8 h-8 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 border border-teal-200 dark:border-kinetic-primary/30 text-kinetic-primary flex items-center justify-center font-bold text-sm transition-all duration-300";
                        if(text) { text.classList.remove('text-slate-400', 'dark:text-slate-500'); text.classList.add('text-kinetic-primary'); }
                    } else {
                        el.classList.add('translate-x-full');
                        el.classList.remove('-translate-x-full');
                        
                        // Update Dot Indicator Belum (Future)
                        dot.className = "w-8 h-8 rounded-full bg-white dark:bg-[#151515] border-2 border-slate-200 dark:border-[#2A2A2A] text-slate-400 dark:text-slate-500 flex items-center justify-center font-bold text-sm transition-all duration-300";
                        if(text) { text.classList.remove('text-kinetic-primary'); text.classList.add('text-slate-400', 'dark:text-slate-500'); }
                    }
                }
            });

            // Update Progress Bar Line
            const progressBar = document.getElementById('progressBar');
            if(target === 1) progressBar.style.width = "0%";
            if(target === 2) progressBar.style.width = "50%";
            if(target === 3) progressBar.style.width = "100%";
        }

        // ==========================================
        // 3. LOGIKA PILIH SOP
        // ==========================================
        function selectSOP(type) {
            selectedSOP = type;
            
            // Reset Semua Style (Mendukung Light/Dark Mode)
            ['A', 'B'].forEach(t => {
                const card = document.getElementById(`sop-card-${t}`);
                const checkWrap = document.getElementById(`sop-check-${t}`);
                const checkIcon = checkWrap.querySelector('i');

                card.className = "bg-slate-50 dark:bg-[#1A1A1A] border-2 border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 cursor-pointer hover:border-slate-400 dark:hover:border-slate-500 transition-colors flex justify-between items-start group";
                checkWrap.className = "w-6 h-6 rounded-full border-2 border-slate-300 dark:border-[#2A2A2A] flex items-center justify-center transition-colors";
                checkIcon.classList.remove('opacity-100');
                checkIcon.classList.add('opacity-0');
            });

            // Aktifkan yang dipilih
            const activeCard = document.getElementById(`sop-card-${type}`);
            const activeCheckWrap = document.getElementById(`sop-check-${type}`);
            const activeCheckIcon = activeCheckWrap.querySelector('i');

            activeCard.className = "bg-teal-50 dark:bg-kinetic-primary/5 border-2 border-kinetic-primary rounded-2xl p-5 cursor-pointer transition-colors flex justify-between items-start group shadow-[0_0_15px_rgba(45,212,191,0.1)]";
            activeCheckWrap.className = "w-6 h-6 rounded-full bg-kinetic-primary flex items-center justify-center transition-colors shadow-[0_0_8px_rgba(45,212,191,0.4)]";
            activeCheckIcon.classList.remove('opacity-0');
            activeCheckIcon.classList.add('opacity-100');
        }
        // ==========================================
        // 4. LOGIKA UPLOAD & VALIDASI FILE
        // ==========================================
        function handleFileUpload(inputElement, idSuffix, allowedExtensions) {
            const file = inputElement.files[0];
            
            // Jika user batal memilih file, keluar dari fungsi
            if (!file) return;

            // Ambil ekstensi file (contoh: 'pdf', 'png')
            const fileExtension = file.name.split('.').pop().toLowerCase();

            // Cek apakah ekstensi sesuai dengan aturan
            if (!allowedExtensions.includes(fileExtension)) {
                alert(`Format file tidak valid! Harap unggah file dengan format: ${allowedExtensions.join(', ').toUpperCase()}`);
                inputElement.value = ''; // Reset input agar kosong lagi
                return;
            }

            // Jika file valid, perbarui tampilan antarmuka (UI)
            const container = document.getElementById(`container-${idSuffix}`);
            const title = document.getElementById(`title-${idSuffix}`);
            const desc = document.getElementById(`desc-${idSuffix}`);
            const iconBg = document.getElementById(`icon-bg-${idSuffix}`);
            const icon = document.getElementById(`icon-${idSuffix}`);
            const actionIcon = document.getElementById(`action-icon-${idSuffix}`);

            // 1. Ubah teks menjadi nama file yang diunggah
            title.innerText = file.name;
            title.classList.add('text-kinetic-primary');
            
            // 2. Beri indikator sukses
            desc.innerText = "File berhasil dilampirkan ✓";
            desc.classList.remove('text-slate-500');
            desc.classList.add('text-teal-600', 'dark:text-kinetic-primary', 'font-bold');

            // 3. Ubah warna kotak & ikon
            container.classList.add('border-kinetic-primary', 'bg-teal-50', 'dark:bg-kinetic-primary/5');
            iconBg.classList.replace('bg-slate-200', 'bg-kinetic-primary');
            iconBg.classList.replace('dark:bg-[#2A2A2A]', 'dark:bg-kinetic-primary/20');
            
            icon.classList.remove('text-slate-500', 'dark:text-slate-400');
            icon.classList.add('text-white', 'dark:text-kinetic-primary');

            actionIcon.classList.replace('ph-cloud-arrow-up', 'ph-check-circle');
            actionIcon.classList.remove('text-slate-400');
            actionIcon.classList.add('text-kinetic-primary');
        }

        // Fungsi terakhir saat tombol "Pesan Sekarang" diklik
        function submitForm() {
            const izin = document.getElementById('file-izin').files.length;
            const ktm = document.getElementById('file-ktm').files.length;
            
            // Proposal wajib jika SOP B
            const proposal = document.getElementById('file-proposal').files.length;
            
            if (izin === 0 || ktm === 0 || (selectedSOP === 'B' && proposal === 0)) {
                alert('Harap unggah dokumen yang diwajibkan sebelum mengirim!');
                return;
            }

            alert('Luar Biasa! Reservasi berhasil diajukan dan sedang diverifikasi.');
            // Di sini Anda bisa memanggil form.submit() atau fungsi fetch API untuk mengirim data ke Laravel
        }
    </script>
</x-app-layout>