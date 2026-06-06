@php
    $isWadir2 = false;
    $approver = Auth::user();
    if ($approver && $approver->position) {
        $posName = strtolower($approver->position->name);
        $hasTwo = str_contains($posName, 'wadir ii') || str_contains($posName, 'wadir 2') || str_contains($posName, 'wakil direktur ii');
        $hasThree = str_contains($posName, 'wadir iii') || str_contains($posName, 'wadir 3') || str_contains($posName, 'wakil direktur iii');
        if ($hasTwo && !$hasThree) {
            $isWadir2 = true;
        }
    }
@endphp
<x-app-layout title="Detail Permohonan Peminjaman">
    <div class="relative px-8 pt-4 pb-16 space-y-6 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <!-- Header -->
        <div class="flex items-center gap-4 pb-3 border-b border-slate-200 dark:border-[#2A2A2A]">
            <a href="{{ route('meja-kerja') }}" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100 dark:hover:bg-[#2A2A2A] text-slate-900 dark:text-white transition-colors">
                <i class="ph-bold ph-arrow-left text-xl"></i>
            </a>
            <div>
                <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-0.5">Detail Permohonan</p>
                <h1 class="font-heading text-2xl font-extrabold text-slate-900 dark:text-white">{{ $approval['booking']['event_name'] }}</h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Booking Info Cards -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-4">
                        <p class="text-[10px] font-bold text-slate-500 dark:text-gray-500 uppercase tracking-widest mb-2">Ruangan</p>
                        <div class="flex items-start gap-2">
                            <i class="ph-bold ph-map-pin text-teal-600 dark:text-kinetic-primary mt-0.5"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $approval['room']['room_name'] }}</p>
                                <p class="text-xs text-slate-500 dark:text-gray-500">{{ $approval['room']['building']['building_name'] }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-4">
                        <p class="text-[10px] font-bold text-slate-500 dark:text-gray-500 uppercase tracking-widest mb-2">Kapasitas</p>
                        <div class="flex items-start gap-2">
                            <i class="ph-bold ph-users text-teal-600 dark:text-kinetic-primary mt-0.5"></i>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $approval['room']['capacity'] ?? '-' }} Orang</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-4">
                        <p class="text-[10px] font-bold text-slate-500 dark:text-gray-500 uppercase tracking-widest mb-2">Tanggal</p>
                        <div class="flex items-start gap-2">
                            <i class="ph-bold ph-calendar-blank text-teal-600 dark:text-kinetic-primary mt-0.5"></i>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $booking->getFormattedDateRange(true) }}</p>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-4">
                        <p class="text-[10px] font-bold text-slate-500 dark:text-gray-500 uppercase tracking-widest mb-2">Waktu</p>
                        <div class="flex items-start gap-2">
                            <i class="ph-bold ph-clock text-teal-600 dark:text-kinetic-primary mt-0.5"></i>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $approval['booking']['start_time'] }} - {{ $approval['booking']['end_time'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Peminjam Info -->
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4">Informasi Peminjam</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-teal-100 dark:bg-[#2A2A2A] flex items-center justify-center text-teal-600 dark:text-kinetic-primary font-bold">
                            {{ substr($approval['peminjam']['name'], 0, 2) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $approval['peminjam']['name'] }}</p>
                            <p class="text-xs text-slate-500 dark:text-gray-500">{{ $approval['peminjam']['unit']['name'] ?? '-' }}</p>
                            <p class="text-xs text-slate-500 dark:text-gray-500">{{ $approval['peminjam']['email'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Documents Section -->
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6">
                    <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4">Dokumen yang Diupload</h3>
                    
                    @if($approval['documents_uploaded'] && count($approval['documents_uploaded']) > 0)
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($approval['documents_uploaded'] as $doc)
                                <button onclick="viewDocument('{{ $doc['file_path'] }}', '{{ $doc['document_name'] }}')" class="group relative bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] hover:border-teal-500 dark:hover:border-kinetic-primary rounded-xl p-4 transition-all hover:shadow-lg">
                                    <div class="flex flex-col items-center justify-center gap-2">
                                        @if(strtolower($doc['document_type']) === 'pdf')
                                            <i class="ph-fill ph-file-pdf text-red-500 text-4xl group-hover:scale-110 transition-transform"></i>
                                        @elseif(in_array(strtolower($doc['document_type']), ['image', 'jpg', 'png', 'jpeg']))
                                            <i class="ph-fill ph-file-image text-blue-500 text-4xl group-hover:scale-110 transition-transform"></i>
                                        @else
                                            <i class="ph-fill ph-file-doc text-indigo-500 text-4xl group-hover:scale-110 transition-transform"></i>
                                        @endif
                                        <p class="text-xs font-bold text-slate-700 dark:text-gray-300 text-center line-clamp-2">{{ $doc['document_name'] }}</p>
                                        <p class="text-[10px] text-slate-500 dark:text-gray-500">{{ $doc['file_size'] }}</p>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    @else
                        <div class="py-8 text-center">
                            <i class="ph-fill ph-file text-4xl text-slate-300 dark:text-[#2A2A2A] mb-2"></i>
                            <p class="text-sm text-slate-500 dark:text-gray-500">Belum ada dokumen yang diupload</p>
                        </div>
                    @endif
                </div>

                <!-- Approval History -->
                @if($approval['approval_history'] && count($approval['approval_history']) > 0)
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4">Riwayat Persetujuan</h3>
                        <div class="space-y-4">
                            @foreach($approval['approval_history'] as $hist)
                                <div class="flex items-start gap-4 pb-4 border-b border-slate-200 dark:border-[#2A2A2A] last:border-0">
                                    <div class="w-8 h-8 rounded-full {{ $hist['approval_status'] === 'Approved' ? 'bg-teal-100 dark:bg-emerald-500/10' : 'bg-red-100 dark:bg-red-500/10' }} flex items-center justify-center shrink-0 mt-0.5">
                                        @if($hist['approval_status'] === 'Approved')
                                            <i class="ph-fill ph-check text-teal-600 dark:text-emerald-500 text-sm"></i>
                                        @else
                                            <i class="ph-fill ph-x text-red-600 dark:text-red-500 text-sm"></i>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $hist['position'] }}</p>
                                                <p class="text-xs text-slate-500 dark:text-gray-500">Oleh {{ $hist['approver_name'] }}</p>
                                            </div>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $hist['approval_status'] === 'Approved' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                                {{ $hist['approval_status'] }}
                                            </span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 dark:text-gray-500 mt-1">Pada {{ $hist['approved_at_formatted'] }}</p>
                                        @if($hist['notes'])
                                            <p class="text-xs text-slate-600 dark:text-gray-400 italic mt-1 p-2 bg-slate-50 dark:bg-[#1A1A1A] rounded-lg">"{{ $hist['notes'] }}"</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            <!-- Right Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 sticky top-6">
                    
                    <!-- Status -->
                    <div class="flex items-center gap-2 mb-6">
                        <span class="w-2 h-2 rounded-full {{ $approval['priority_indicator'] === 'urgent' ? 'bg-red-500' : ($approval['priority_indicator'] === 'high' ? 'bg-yellow-500' : 'bg-green-500') }} shadow-lg"></span>
                        <span class="text-[10px] font-bold {{ $approval['priority_indicator'] === 'urgent' ? 'text-red-500' : ($approval['priority_indicator'] === 'high' ? 'text-yellow-500' : 'text-green-500') }} uppercase tracking-widest">{{ ucfirst($approval['priority_indicator']) }} Priority</span>
                    </div>

                    <!-- Workflow Progress -->
                    <div class="mb-8">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4">Progress Persetujuan</h3>
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <div class="w-6 h-6 rounded-full bg-teal-100 dark:bg-[#2A2A2A] flex items-center justify-center shrink-0 text-teal-600 dark:text-kinetic-primary text-xs font-bold">
                                    ✓
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700 dark:text-gray-300">Step {{ $approval['current_approver_required']['step_order'] ?? 1 }} dari {{ $approval['workflow']['total_steps'] }}</p>
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500">{{ $approval['current_approver_required']['position']['name'] ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Event -->
                    <div class="mb-8 pb-8 border-b border-slate-200 dark:border-[#2A2A2A]">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-3">Deskripsi Event</h3>
                        <p class="text-sm text-slate-600 dark:text-gray-400 break-all whitespace-pre-line">{{ $approval['booking']['event_description'] ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        @if (!$isWadir2)
                            <button onclick="showRejectModal()" class="w-full py-3 rounded-xl border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 font-bold text-sm hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                <i class="ph-bold ph-x-circle mr-2"></i>Tolak/Revisi
                            </button>
                        @endif
                        <button onclick="submitApprove()" class="w-full py-3 rounded-xl bg-teal-600 dark:bg-kinetic-primary text-white dark:text-[#151515] font-bold text-sm hover:bg-teal-700 dark:hover:bg-[#2dd4bf] transition-colors shadow-[0_4px_12px_rgba(20,184,166,0.2)]">
                            <i class="ph-bold ph-check-circle mr-2"></i>{{ $isWadir2 ? 'Terbitkan Disposisi' : (($approval['booking']['revision_count'] ?? 0) > 0 ? 'Setujui Revisi' : 'Setujui Sekarang') }}
                        </button>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- Document Viewer Modal -->
    <div id="documentModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#151515] rounded-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-[#2A2A2A]">
                <h3 id="documentTitle" class="font-bold text-slate-900 dark:text-white">Dokumen</h3>
                <div class="flex items-center gap-4">
                    <a id="downloadBtn" href="" download class="text- kinetic-primary hover:text-teal-600 transition-colors">
                        <i class="ph-bold ph-download-simple text-2xl"></i>
                    </a>
                    <button onclick="closeDocumentModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 transition-colors">
                        <i class="ph-bold ph-x text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-auto">
                <iframe id="documentFrame" src="" class="w-full h-full" style="min-height: 500px;"></iframe>
            </div>

        </div>
    </div>

    <!-- Reject Modal -->
    <template x-teleport="body">
        <div id="rejectModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-300">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl w-full max-w-lg p-8 relative shadow-2xl transform scale-95 transition-all duration-300">
                
                <button onclick="closeRejectModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>

                <div class="mb-6">
                    <h3 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-1">Tolak Permohonan</h3>
                    <p class="text-xl text-slate-500 dark:text-gray-400">Tuliskan instruksi atau alasan dengan jelas untuk peminjam.</p>
                </div>

                <form id="rejectForm" onsubmit="submitReject(event)" class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Alasan Penolakan / Revisi</label>
                        <textarea id="rejectNotes" name="notes" placeholder="Contoh: Lampiran proposal kurang tanda tangan ketua unit. Harap upload kembali proposal yang telah disetujui." required class="w-full px-4 py-3 border border-slate-200 dark:border-[#2A2A2A] rounded-xl bg-slate-50 dark:bg-[#1A1A1A] text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors resize-none text-sm leading-relaxed" rows="4"></textarea>
                    </div>

                    <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-[#2A2A2A] mt-6">
                        <button type="button" onclick="closeRejectModal()" class="w-1/3 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3.5 rounded-xl transition-colors text-sm">
                            Batal
                        </button>
                        <button type="submit" class="w-2/3 bg-red-600 hover:bg-red-700 text-white font-bold py-3.5 rounded-xl transition-colors shadow-[0_0_15px_rgba(239,68,68,0.2)] text-sm">
                            Ya, Tolak
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <script>
        function viewDocument(filePath, fileName) {
            document.getElementById('documentTitle').textContent = fileName;
            document.getElementById('documentFrame').src = filePath;
            document.getElementById('downloadBtn').href = filePath;
            document.getElementById('documentModal').classList.remove('hidden');
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.add('hidden');
            document.getElementById('documentFrame').src = '';
        }

        function showRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRejectModal() {
            const modal = document.getElementById('rejectModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
            document.getElementById('rejectForm').reset();
        }

        let disposisiPrinted = false;

        function closeDisposisiModal() {
            const modal = document.getElementById('disposisiModal');
            if (modal) modal.classList.add('hidden');
        }

        function getDisposisiData() {
            const klasifikasi = document.querySelector('input[name="disposisi_klasifikasi"]:checked')?.value || 'Biasa';
            const tujuan = Array.from(document.querySelectorAll('input[name="disposisi_tujuan"]:checked')).map(cb => cb.value);
            const isi = Array.from(document.querySelectorAll('input[name="disposisi_isi"]:checked')).map(cb => cb.value);
            const catatan = document.getElementById('disposisi_catatan')?.value || '';

            return { klasifikasi, tujuan, isi, catatan };
        }

        function printDisposisi() {
            const data = getDisposisiData();
            const bookingId = '{{ $booking->id }}';

            const queryParams = new URLSearchParams({
                klasifikasi: data.klasifikasi,
                tujuan: JSON.stringify(data.tujuan),
                isi: JSON.stringify(data.isi),
                catatan: data.catatan
            });

            window.open(`/approver/bookings/${bookingId}/preview-disposisi?${queryParams.toString()}`, '_blank');

            disposisiPrinted = true;
            const btn = document.getElementById('confirmApproveBtn');
            if (btn) {
                btn.removeAttribute('disabled');
            }
        }

        function executeApproval() {
            const data = getDisposisiData();
            const bookingId = '{{ $booking->id }}';
            const modalContainer = Alpine.$data(document.getElementById('global-modal-container'));

            closeDisposisiModal();

            fetch(`/approver/approvals/${bookingId}/approve`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    disposisi_data: data
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalContainer.showAlert('Berhasil', 'Pengajuan telah disetujui!', 'success', () => {
                        window.location.href = '/approver/meja-kerja';
                    });
                } else {
                    modalContainer.showAlert('Gagal', 'Error: ' + (data.error || data.message || 'Terjadi kesalahan'), 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContainer.showAlert('Kesalahan', 'Gagal memproses persetujuan', 'danger');
            });
        }

        function submitApprove() {
            const isWadir2 = {{ $isWadir2 ? 'true' : 'false' }};
            if (isWadir2) {
                const modal = document.getElementById('disposisiModal');
                if (modal) modal.classList.remove('hidden');
                return;
            }

            const modalContainer = Alpine.$data(document.getElementById('global-modal-container'));
            modalContainer.showConfirm(
                'Setujui Permohonan?',
                'Apakah Anda yakin ingin menyetujui permohonan peminjaman ini?',
                () => {
                    const bookingId = '{{ $booking->id }}';
                    fetch(`/approver/approvals/${bookingId}/approve`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modalContainer.showAlert('Berhasil', 'Pengajuan telah disetujui!', 'success', () => {
                                window.location.href = '/approver/meja-kerja';
                            });
                        } else {
                            modalContainer.showAlert('Gagal', 'Error: ' + (data.error || data.message || 'Terjadi kesalahan'), 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalContainer.showAlert('Kesalahan', 'Gagal memproses persetujuan', 'danger');
                    });
                },
                'success',
                'Ya, Setujui'
            );
        }

        function submitReject(event) {
            event.preventDefault();
            const notes = document.getElementById('rejectNotes').value;
            const modalContainer = Alpine.$data(document.getElementById('global-modal-container'));
            
            modalContainer.showConfirm(
                'Tolak Permohonan?',
                'Apakah Anda yakin ingin menolak permohonan ini?',
                () => {
                    closeRejectModal();
                    
                    const bookingId = '{{ $booking->id }}';
                    fetch(`/approver/approvals/${bookingId}/reject`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ notes: notes })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modalContainer.showAlert('Berhasil', 'Pengajuan telah ditolak!', 'success', () => {
                                window.location.href = '/approver/meja-kerja';
                            });
                        } else {
                            modalContainer.showAlert('Gagal', 'Error: ' + (data.error || data.message || 'Terjadi kesalahan'), 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalContainer.showAlert('Kesalahan', 'Gagal memproses penolakan', 'danger');
                    });
                },
                'danger',
                'Ya, Tolak'
            );
        }

        // Close modals when clicking outside
        const documentModal = document.getElementById('documentModal');
        if (documentModal) {
            documentModal.addEventListener('click', function(e) {
                if (e.target === this) closeDocumentModal();
            });
        }

        document.body.addEventListener('click', function(e) {
            const rejectModal = document.getElementById('rejectModal');
            if (rejectModal && e.target === rejectModal) {
                closeRejectModal();
            }
        });
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

    @if ($isWadir2)
        <!-- Disposisi Wadir II Modal -->
        <template x-teleport="body">
            <div id="disposisiModal" class="hidden fixed inset-0 z-[9999] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 transition-all duration-300">
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col shadow-2xl transform scale-95 transition-all duration-300">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-6 border-b border-slate-200 dark:border-[#2A2A2A]">
                        <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white">Form Disposisi Wadir II</h3>
                        <button onclick="closeDisposisiModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 transition-colors">
                            <i class="ph-bold ph-x text-2xl"></i>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="flex-1 overflow-auto p-6 space-y-6">
                        <!-- Klasifikasi -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-3">Klasifikasi Surat</label>
                            <div class="flex flex-wrap gap-4">
                                @foreach (['Sangat Rahasia', 'Rahasia', 'Sangat Segera', 'Segera', 'Biasa'] as $klas)
                                    <label class="inline-flex items-center gap-2.5 text-sm text-slate-700 dark:text-gray-300 cursor-pointer">
                                        <input type="radio" name="disposisi_klasifikasi" value="{{ $klas }}" {{ $klas === 'Biasa' ? 'checked' : '' }} class="w-5 h-5 border-2 border-slate-400 dark:border-slate-600 bg-white dark:bg-[#1A1A1A] text-teal-600 checked:bg-teal-600 checked:border-teal-600 focus:ring-teal-500 focus:ring-2 accent-teal-600 cursor-pointer">
                                        {{ $klas }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Diteruskan Kepada -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-3">Diteruskan Kepada</label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                @php
                                    $tujuanOptions = [
                                        'Wakil Direktur I', 'Wakil Direktur III', 'Wakil Direktur IV',
                                        'Kajur Teknik Elektro', 'Kajur Teknik Mesin', 'Kajur Teknik Sipil',
                                        'Kajur Teknik Kimia', 'Kajur Akuntansi', 'Kajur Administrasi Niaga',
                                        'Kajur Teknologi Informasi', 'Kasubag Akademik', 'Kasubag Umum',
                                        'Pokja Adm. Akademik & Registrasi', 'Pokja Eval. Akademik & Pengelolaan Data',
                                        'Pokja Pembinaan Keg. Mhs & Alumni', 'Pokja Tata Usaha', 'Pokja Protokoler',
                                        'Pokja Rumah Tangga', 'Pokja Perencanaan', 'Pokja Monev', 'Pokja Keuangan',
                                        'Pokja Pengelola BMN', 'Pokja Kepegawaian', 'Pokja Ortala', 'Pokja Kerja Sama',
                                        'Pokja Humas', 'Pokja Hukum', 'Pusat P2MPP', 'Pusat P3M', 'UPA Perpustakaan',
                                        'UPA TIK', 'UPA Bahasa', 'UPA PP', 'UPA PKK', 'UPA LUK', 'UPA Percetakan & Penerbitan',
                                        'Pokja Unit Pengelola Usaha', 'Tim Kerja Pimpinan', 'PPK', 'Pokja UPPBJ',
                                        'Tim Teknis PBJ', 'Admin PPK', 'Adm. Wadir II'
                                    ];
                                @endphp
                                @foreach ($tujuanOptions as $tuj)
                                    <label class="inline-flex items-center gap-2.5 text-sm text-slate-700 dark:text-gray-300 cursor-pointer">
                                        <input type="checkbox" name="disposisi_tujuan" value="{{ $tuj }}" class="w-5 h-5 border-2 border-slate-400 dark:border-slate-600 bg-white dark:bg-[#1A1A1A] rounded text-teal-600 checked:bg-teal-600 checked:border-teal-600 focus:ring-teal-500 focus:ring-2 accent-teal-600 cursor-pointer">
                                        {{ $tuj }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Isi Disposisi -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-3">Isi Disposisi</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @php
                                    $isiOptions = [
                                        'Mohon diproses sesuai aturan yang berlaku',
                                        'Mohon ditindaklanjuti',
                                        'Mohon masukan',
                                        'Mohon diinfokan',
                                        'Mohon bisa dibantu',
                                        'Mohon diterima dengan baik dan dibalas',
                                        'Mohon diagendakan',
                                        'Untuk diketahui',
                                        'Sebagai refrensi',
                                        'Arsip'
                                    ];
                                @endphp
                                @foreach ($isiOptions as $isiOpt)
                                    <label class="inline-flex items-center gap-2.5 text-sm text-slate-700 dark:text-gray-300 cursor-pointer">
                                        <input type="checkbox" name="disposisi_isi" value="{{ $isiOpt }}" class="w-5 h-5 border-2 border-slate-400 dark:border-slate-600 bg-white dark:bg-[#1A1A1A] rounded text-teal-600 checked:bg-teal-600 checked:border-teal-600 focus:ring-teal-500 focus:ring-2 accent-teal-600 cursor-pointer">
                                        {{ $isiOpt }}
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Catatan/Jawaban Disposisi -->
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Jawaban Disposisi / Catatan</label>
                            <textarea id="disposisi_catatan" name="disposisi_catatan" placeholder="Tuliskan catatan tambahan jika ada..." class="w-full px-4 py-3 border border-slate-200 dark:border-[#2A2A2A] rounded-xl bg-slate-50 dark:bg-[#1A1A1A] text-slate-900 dark:text-white text-sm focus:outline-none focus:border-kinetic-primary transition-colors resize-none" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-6 border-t border-slate-200 dark:border-[#2A2A2A] flex justify-between items-center gap-3">
                        <button type="button" onclick="closeDisposisiModal()" class="px-5 py-3 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold rounded-xl transition-colors text-sm">
                            Batal
                        </button>
                        <div class="flex items-center gap-3">
                            <button type="button" onclick="printDisposisi()" class="px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors text-sm flex items-center gap-2">
                                <i class="ph ph-printer"></i> Simpan & Cetak Disposisi
                            </button>
                            <button type="button" id="confirmApproveBtn" onclick="executeApproval()" class="px-5 py-3 bg-teal-600 hover:bg-teal-700 text-white font-bold rounded-xl transition-colors text-sm flex items-center gap-2 shadow-lg">
                                <i class="ph ph-check-circle"></i> Ya, Setujui & Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    @endif
</x-app-layout>
