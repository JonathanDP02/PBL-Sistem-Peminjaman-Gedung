<x-app-layout title="Detail Permohonan Peminjaman">
    <div class="relative px-8 pt-6 pb-32 space-y-10 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <!-- Header -->
        <div class="flex items-center gap-4 pb-6 border-b border-slate-200 dark:border-[#2A2A2A]">
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
                            <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $approval['booking']['booking_date'] }}</p>
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

                <!-- Previous Approvals -->
                @if($approval['previous_approvals'] && count($approval['previous_approvals']) > 0)
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6">
                        <h3 class="font-bold text-slate-900 dark:text-white text-sm mb-4">Persetujuan Sebelumnya</h3>
                        <div class="space-y-4">
                            @foreach($approval['previous_approvals'] as $prevApproval)
                                <div class="flex items-start gap-4 pb-4 border-b border-slate-200 dark:border-[#2A2A2A] last:border-0">
                                    <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-[#2A2A2A] flex items-center justify-center shrink-0 mt-0.5">
                                        <i class="ph-fill ph-check text-teal-600 dark:text-kinetic-primary text-sm"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">{{ $prevApproval['position'] }}</p>
                                        <p class="text-xs text-slate-500 dark:text-gray-500">Oleh {{ $prevApproval['approver_name'] }}</p>
                                        <p class="text-[10px] text-slate-400 dark:text-gray-500 mt-1">Disetujui pada {{ $prevApproval['approved_at_formatted'] }}</p>
                                        @if($prevApproval['notes'])
                                            <p class="text-xs text-slate-600 dark:text-gray-400 italic mt-1">{{ $prevApproval['notes'] }}</p>
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
                        <p class="text-sm text-slate-600 dark:text-gray-400 line-clamp-4">{{ $approval['booking']['event_description'] ?? 'Tidak ada deskripsi' }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button onclick="showRejectModal()" class="w-full py-3 rounded-xl border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 font-bold text-sm hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                            <i class="ph-bold ph-x-circle mr-2"></i>Tolak/Revisi
                        </button>
                        <button onclick="submitApprove()" class="w-full py-3 rounded-xl bg-teal-600 dark:bg-kinetic-primary text-white dark:text-[#151515] font-bold text-sm hover:bg-teal-700 dark:hover:bg-[#2dd4bf] transition-colors shadow-[0_4px_12px_rgba(20,184,166,0.2)]">
                            <i class="ph-bold ph-check-circle mr-2"></i>Setujui Sekarang
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
                <button onclick="closeDocumentModal()" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300 transition-colors">
                    <i class="ph-bold ph-x text-2xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="flex-1 overflow-auto">
                <iframe id="documentFrame" src="" class="w-full h-full" style="min-height: 500px;"></iframe>
            </div>

        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 bg-black/50 flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#151515] rounded-2xl max-w-md w-full">
            
            <div class="p-6 border-b border-slate-200 dark:border-[#2A2A2A]">
                <h3 class="font-bold text-slate-900 dark:text-white text-lg">Tolak Permohonan</h3>
                <p class="text-sm text-slate-500 dark:text-gray-500 mt-1">Berikan alasan penolakan untuk peminjam</p>
            </div>

            <div class="p-6">
                <form id="rejectForm" onsubmit="submitReject(event)">
                    <textarea id="rejectNotes" name="notes" placeholder="Masukkan alasan penolakan..." required class="w-full px-4 py-3 border border-slate-200 dark:border-[#2A2A2A] rounded-xl bg-white dark:bg-[#1A1A1A] text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-gray-600 focus:ring-teal-500 focus:border-teal-500 dark:focus:ring-kinetic-primary dark:focus:border-kinetic-primary resize-none" rows="4"></textarea>
                    
                    <div class="flex gap-3 mt-6">
                        <button type="button" onclick="closeRejectModal()" class="flex-1 py-3 rounded-xl border border-slate-200 dark:border-[#2A2A2A] text-slate-700 dark:text-gray-300 font-bold hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 text-white font-bold hover:bg-red-700 transition-colors">
                            Tolak
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function viewDocument(filePath, fileName) {
            document.getElementById('documentTitle').textContent = fileName;
            document.getElementById('documentFrame').src = '/' + filePath;
            document.getElementById('documentModal').classList.remove('hidden');
        }

        function closeDocumentModal() {
            document.getElementById('documentModal').classList.add('hidden');
            document.getElementById('documentFrame').src = '';
        }

        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectForm').reset();
        }

        function submitApprove() {
            if (confirm('Apakah Anda yakin ingin menyetujui permohonan ini?')) {
                const bookingId = '{{ $booking->id }}';
                fetch(`/approver/approvals/${bookingId}/approve`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pengajuan telah disetujui!');
                        window.location.href = '/approver/meja-kerja';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memproses persetujuan');
                });
            }
        }

        function submitReject(event) {
            event.preventDefault();
            const notes = document.getElementById('rejectNotes').value;
            if (confirm('Apakah Anda yakin ingin menolak permohonan ini?')) {
                const bookingId = '{{ $booking->id }}';
                fetch(`/approver/approvals/${bookingId}/reject`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ notes: notes })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Pengajuan telah ditolak!');
                        window.location.href = '/approver/meja-kerja';
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memproses penolakan');
                });
            }
        }

        // Close modals when clicking outside
        document.getElementById('documentModal').addEventListener('click', function(e) {
            if (e.target === this) closeDocumentModal();
        });

        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
</x-app-layout>
