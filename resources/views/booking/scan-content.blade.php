    <div class="relative px-8 pt-4 pb-8 space-y-8 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <!-- Header -->
        <div class="text-center md:text-left max-w-xl mx-auto md:mx-0">
            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 dark:bg-kinetic-primary/10 border border-teal-200 dark:border-kinetic-primary/20 rounded-full text-[10px] font-bold text-teal-700 dark:text-kinetic-secondary tracking-widest uppercase mb-4 transition-colors">
                <span class="w-1.5 h-1.5 bg-kinetic-primary rounded-full animate-ping"></span> On-Site Verification Guard
            </span>
            <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-3 transition-colors">
                Validasi <span class="text-kinetic-primary">Surat Izin</span>
            </h2>
            <p class="text-slate-500 dark:text-gray-400 text-sm leading-relaxed transition-colors">
                Pindai QR code pada dokumen fisik atau digital Surat Izin Peminjaman untuk memverifikasi keasliannya secara langsung di lapangan.
            </p>
        </div>

        <div class="max-w-xl mx-auto w-full">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border shadow-md dark:shadow-none rounded-3xl overflow-hidden transition-colors duration-300">
                
                <!-- Tab Headers -->
                <div class="flex border-b border-slate-200 dark:border-[#2A2A2A]">
                    <button onclick="switchTab('camera')" id="cameraTabBtn" class="w-1/2 py-4 text-sm font-bold text-slate-900 dark:text-white border-b-2 border-kinetic-primary transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-camera text-base text-kinetic-primary"></i> Gunakan Kamera
                    </button>
                    <button onclick="switchTab('file')" id="fileTabBtn" class="w-1/2 py-4 text-sm font-bold text-slate-400 dark:text-gray-500 border-b-2 border-transparent hover:text-slate-600 dark:hover:text-gray-300 transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-upload-simple text-base"></i> Unggah Gambar
                    </button>
                </div>

                <div class="p-6 md:p-8 space-y-6">
                    
                    <!-- Error / Warning Alert inside container -->
                    <div id="scannerAlert" class="hidden flex items-start gap-3 p-4 rounded-xl border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-900 transition-all">
                        <i class="ph-bold ph-warning-circle text-xl text-red-700 dark:text-red-400 flex-shrink-0"></i>
                        <p id="scannerAlertMsg" class="text-xs text-red-700 dark:text-red-400"></p>
                    </div>

                    <!-- CAMERA SCANNER VIEW -->
                    <div id="cameraScannerView" class="space-y-4">
                        <div class="relative w-full aspect-square md:aspect-[4/3] rounded-2xl overflow-hidden bg-slate-100 dark:bg-[#101010] border border-slate-200 dark:border-[#222] flex items-center justify-center transition-colors">
                            
                            <!-- QR Scanner Video Render Container -->
                            <div id="qrReader" class="absolute inset-0 w-full h-full object-cover [&_video]:object-cover [&_video]:w-full [&_video]:h-full"></div>
                            
                            <!-- Scanner Overlay (Target Box) -->
                            <div id="scannerOverlay" class="absolute inset-0 pointer-events-none flex items-center justify-center border-4 border-transparent z-20">
                                <div class="w-48 h-48 md:w-64 md:h-64 border-4 border-kinetic-primary/60 rounded-3xl relative animate-pulse flex items-center justify-center">
                                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-kinetic-primary rounded-tl-lg"></div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-kinetic-primary rounded-tr-lg"></div>
                                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-kinetic-primary rounded-bl-lg"></div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-kinetic-primary rounded-br-lg"></div>
                                    
                                    <!-- Scanning Red Line -->
                                    <div class="w-[90%] h-0.5 bg-red-500/70 absolute shadow-[0_0_10px_#ef4444] animate-[scan_2s_ease-in-out_infinite]"></div>
                                </div>
                            </div>

                            <!-- Stop Scanner / Retry UI -->
                            <div id="scannerPlaceholder" class="absolute inset-0 flex flex-col items-center justify-center p-6 text-center z-30 bg-slate-100 dark:bg-[#101010] space-y-4">
                                <div class="w-16 h-16 rounded-full bg-teal-50 dark:bg-slate-800 flex items-center justify-center text-kinetic-primary text-2xl">
                                    <i class="ph-bold ph-qr-code "></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white">Kamera Belum Aktif</h4>
                                <div>
                                    <p class="text-xs text-slate-400 dark:text-gray-500 max-w-xs">Tekan tombol di bawah untuk mengizinkan</p>
                                    <p class="text-xs text-slate-400 dark:text-gray-500 max-w-xs">akses kamera dan memulai pemindaian.</p>
                                </div>
                                <button onclick="startQrScanner()" class="bg-kinetic-primary hover:bg-teal-400 text-slate-900 dark:text-white font-bold px-5 py-2.5 rounded-xl transition shadow-[0_0_15px_rgba(20,184,166,0.3)]">
                                    Mulai Kamera
                                </button>
                            </div>
                        </div>

                        <!-- Camera Select Dropdown (hidden initially) -->
                        <div id="cameraSelectContainer" class="hidden">
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 tracking-widest uppercase mb-2">PILIH KAMERA</label>
                            <div class="relative">
                                <i class="ph ph-camera absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                <select id="cameraSelect" onchange="changeCamera(this.value)" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3 text-xs text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-kinetic-primary transition-all">
                                    <option value="">Mengambil daftar kamera...</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- FILE SCANNER VIEW (Hidden initially) -->
                    <div id="fileScannerView" class="hidden space-y-4">
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 tracking-widest uppercase mb-2">UNGGAH DOKUMEN/GAMBAR QR</label>
                        <div class="border-2 border-dashed border-slate-300 dark:border-[#333] hover:border-kinetic-primary dark:hover:border-kinetic-primary rounded-2xl p-8 text-center cursor-pointer transition-colors" onclick="document.getElementById('qrFile').click()">
                            <input type="file" id="qrFile" accept="image/*" class="hidden" onchange="scanFile(this)">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-12 h-12 rounded-full bg-slate-100 dark:bg-[#222] flex items-center justify-center text-slate-500 dark:text-gray-400 text-xl">
                                    <i class="ph-bold ph-image"></i>
                                </div>
                                <h4 class="text-sm font-bold text-slate-800 dark:text-white">Pilih File Gambar</h4>
                                <p class="text-xs text-slate-400 dark:text-gray-500">Mendukung format PNG, JPG, JPEG atau screenshot QR Code.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stop Scanner Button (When Active) -->
                    <button id="stopScannerBtn" onclick="stopQrScanner()" class="hidden w-full bg-slate-100 dark:bg-[#222] hover:bg-slate-200 dark:hover:bg-[#333] text-slate-700 dark:text-gray-300 font-bold py-3 rounded-xl text-xs transition-colors flex items-center justify-center gap-2">
                        <i class="ph-bold ph-stop"></i> Hentikan Kamera
                    </button>

                </div>
            </div>
        </div>

    </div>

    <!-- HTML5 QR Code Scanner Library from secure CDN -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        let html5QrCode = null;
        let isScannerRunning = false;
        let activeTab = 'camera';

        // Custom scanning overlay animation
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes scan {
                0%, 100% { top: 5%; }
                50% { top: 95%; }
            }
        `;
        document.head.appendChild(style);

        function switchTab(tab) {
            activeTab = tab;
            const cameraTab = document.getElementById('cameraTabBtn');
            const fileTab = document.getElementById('fileTabBtn');
            const cameraView = document.getElementById('cameraScannerView');
            const fileView = document.getElementById('fileScannerView');
            const stopBtn = document.getElementById('stopScannerBtn');

            if (tab === 'camera') {
                cameraTab.className = "w-1/2 py-4 text-sm font-bold text-slate-900 dark:text-white border-b-2 border-kinetic-primary transition-all flex items-center justify-center gap-2";
                fileTab.className = "w-1/2 py-4 text-sm font-bold text-slate-400 dark:text-gray-500 border-b-2 border-transparent hover:text-slate-600 dark:hover:text-gray-300 transition-all flex items-center justify-center gap-2";
                cameraView.classList.remove('hidden');
                fileView.classList.add('hidden');
                
                if (isScannerRunning) {
                    stopBtn.classList.remove('hidden');
                }
            } else {
                fileTab.className = "w-1/2 py-4 text-sm font-bold text-slate-900 dark:text-white border-b-2 border-kinetic-primary transition-all flex items-center justify-center gap-2";
                cameraTab.className = "w-1/2 py-4 text-sm font-bold text-slate-400 dark:text-gray-500 border-b-2 border-transparent hover:text-slate-600 dark:hover:text-gray-300 transition-all flex items-center justify-center gap-2";
                fileView.classList.remove('hidden');
                cameraView.classList.add('hidden');
                stopBtn.classList.add('hidden');
            }
            hideAlert();
        }

        // Start HTML5 QR Code Scanner
        function startQrScanner() {
            hideAlert();
            document.getElementById('scannerPlaceholder').classList.add('hidden');
            document.getElementById('scannerOverlay').classList.remove('opacity-0');
            
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("qrReader");
            }

            Html5Qrcode.getCameras().then(devices => {
                if (devices && devices.length) {
                    const cameraSelect = document.getElementById('cameraSelect');
                    cameraSelect.innerHTML = '';
                    
                    devices.forEach((device, index) => {
                        const option = document.createElement('option');
                        option.value = device.id;
                        option.textContent = device.label || `Kamera ${index + 1}`;
                        cameraSelect.appendChild(option);
                    });

                    document.getElementById('cameraSelectContainer').classList.remove('hidden');
                    document.getElementById('stopScannerBtn').classList.remove('hidden');
                    
                    // Default to back/rear camera if available
                    let cameraId = devices[0].id;
                    const backCamera = devices.find(device => device.label.toLowerCase().includes('back') || device.label.toLowerCase().includes('rear'));
                    if (backCamera) {
                        cameraId = backCamera.id;
                        cameraSelect.value = cameraId;
                    }

                    startScanning(cameraId);
                } else {
                    showAlert("Tidak ada kamera yang terdeteksi di perangkat Anda.");
                    resetPlaceholder();
                }
            }).catch(err => {
                showAlert("Gagal mengakses kamera. Pastikan Anda memberikan izin akses kamera.");
                console.error("Camera access error:", err);
                resetPlaceholder();
            });
        }

        function startScanning(cameraId) {
            isScannerRunning = true;
            html5QrCode.start(
                cameraId, 
                {
                    fps: 15,
                    qrbox: (width, height) => {
                        const size = Math.min(width, height) * 0.7;
                        return { width: size, height: size };
                    }
                },
                onScanSuccess,
                onScanFailure
            ).catch(err => {
                showAlert("Gagal menjalankan kamera: " + err);
                resetPlaceholder();
            });
        }

        function changeCamera(cameraId) {
            if (isScannerRunning) {
                html5QrCode.stop().then(() => {
                    startScanning(cameraId);
                }).catch(err => {
                    console.error("Error stopping camera for switch:", err);
                });
            }
        }

        function stopQrScanner() {
            if (html5QrCode && isScannerRunning) {
                html5QrCode.stop().then(() => {
                    resetPlaceholder();
                }).catch(err => {
                    console.error("Error stopping scanner:", err);
                });
            }
        }

        function resetPlaceholder() {
            isScannerRunning = false;
            document.getElementById('scannerPlaceholder').classList.remove('hidden');
            document.getElementById('cameraSelectContainer').classList.add('hidden');
            document.getElementById('stopScannerBtn').classList.add('hidden');
            document.getElementById('scannerOverlay').classList.add('opacity-0');
        }

        // Handle successful scan results
        function onScanSuccess(decodedText, decodedResult) {
            console.log("QR Code Decoded:", decodedText);
            
            // Validate if URL contains our validation path
            if (decodedText.includes('/validate/')) {
                // Redirect immediately to the validation URL
                stopQrScanner();
                showAlert("QR Code Valid! Mengarahkan...", "success");
                window.location.href = decodedText;
            } else {
                showAlert("QR Code tidak dikenali. Pastikan Anda memindai QR Code Surat Izin Space.in resmi.");
            }
        }

        function onScanFailure(error) {
            // Silence scan failures (triggered every frame QR isn't found)
        }

        // SCAN FILE FALLBACK
        function scanFile(input) {
            hideAlert();
            if (input.files && input.files[0]) {
                const imageFile = input.files[0];
                const fileHtml5QrCode = new Html5Qrcode("qrReader");
                
                fileHtml5QrCode.scanFile(imageFile, true)
                    .then(decodedText => {
                        onScanSuccess(decodedText, null);
                    })
                    .catch(err => {
                        showAlert("Tidak ada QR Code valid yang ditemukan di gambar tersebut. Pastikan gambar jelas.");
                        console.error("File scan error:", err);
                    });
            }
        }

        // ALERTS AND UI HELPER
        function showAlert(message, type = 'error') {
            const alertContainer = document.getElementById('scannerAlert');
            const alertMsg = document.getElementById('scannerAlertMsg');
            
            alertMsg.textContent = message;
            alertContainer.classList.remove('hidden');

            if (type === 'success') {
                alertContainer.className = "flex items-start gap-3 p-4 rounded-xl border bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-900 transition-all";
                alertContainer.querySelector('i').className = "ph-bold ph-check-circle text-xl text-green-700 dark:text-green-400 flex-shrink-0";
                alertMsg.className = "text-xs text-green-700 dark:text-green-400";
            } else {
                alertContainer.className = "flex items-start gap-3 p-4 rounded-xl border bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-900 transition-all";
                alertContainer.querySelector('i').className = "ph-bold ph-warning-circle text-xl text-red-700 dark:text-red-400 flex-shrink-0";
                alertMsg.className = "text-xs text-red-700 dark:text-red-400";
            }
        }

        function hideAlert() {
            document.getElementById('scannerAlert').classList.add('hidden');
        }

        // Clean up scanner on page leave
        window.addEventListener('beforeunload', () => {
            stopQrScanner();
        });
    </script>
