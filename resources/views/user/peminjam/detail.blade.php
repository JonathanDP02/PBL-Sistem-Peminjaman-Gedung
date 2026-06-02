<x-app-layout title="Detail Pesanan">
    <div class="relative px-8 pt-4 pb-8 space-y-8 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <nav class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-2">
                    @php
                        $userRole = Auth::user()?->role?->name;
                        $backRoute = route('dashboard');
                        $backText = 'Dashboard';
                        if ($userRole === 'Peminjam') {
                            $backRoute = route('riwayat');
                            $backText = 'Riwayat';
                        }
                    @endphp
                    <a href="{{ $backRoute }}" class="hover:text-kinetic-primary transition">{{ $backText }}</a>
                    <i class="ph ph-caret-right text-[10px]"></i>
                    <span class="text-slate-500">Detail Pesanan</span>
                </nav>
                <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Detail Peminjaman</h2>
                <div class="flex items-center gap-3">
                    <span class="text-sm font-mono font-bold text-slate-400">#BKG-{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                    @php
                        $statusClass = '';
                        $statusText = $booking->status;
                        if ($booking->status === 'Approved') {
                            $statusClass = 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20';
                        } elseif ($booking->status === 'Pending') {
                            $statusClass = 'bg-amber-500/10 text-amber-500 border-amber-500/20';
                        } elseif ($booking->status === 'Revising') {
                            $statusClass = 'bg-red-500/10 text-red-500 border-red-500/20';
                            $statusText = 'Butuh Revisi';
                        } elseif ($booking->status === 'Cancelled' || $booking->status === 'Rejected') {
                            $statusClass = 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                        } else {
                            $statusClass = 'bg-slate-500/10 text-slate-500 border-slate-500/20';
                        }
                    @endphp
                    <span class="px-2 py-0.5 border rounded text-[10px] font-bold uppercase tracking-wider {{ $statusClass }}">{{ $statusText }}</span>
                    <div class="flex items-center gap-2 ml-2">
                        <div class="w-24 h-1.5 bg-slate-100 dark:bg-[#2A2A2A] rounded-full overflow-hidden">
                            <div class="h-full bg-kinetic-primary transition-all duration-1000" style="width: {{ $booking->progress_percentage }}%"></div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400">{{ $booking->progress_percentage }}%</span>
                    </div>
                </div>
            </div>
            <div class="flex gap-3">
                @if($booking->status === 'Approved')
                <a href="{{ route('booking.pdf', $booking->id) }}" target="_blank" class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-[#222] transition shadow-sm dark:shadow-none">
                    <i class="ph ph-printer text-lg"></i> Cetak Bukti
                </a>
                @endif
                
                @if(in_array($booking->status, ['Pending', 'Revising', 'Draft']) && Auth::user()?->role?->name === 'Peminjam')
                <button onclick="cancelBooking({{ $booking->id }})" class="flex items-center gap-2 px-5 py-2.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-500 border border-red-200 dark:border-red-500/20 rounded-xl text-sm font-bold hover:bg-red-100 dark:hover:bg-red-500/20 transition">
                    <i class="ph ph-x-circle text-lg"></i> Batalkan
                </button>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-5 space-y-6">
                @if($booking->status === 'Revising')
                <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-5 flex flex-col gap-4">
                    <div class="flex gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/20">
                            <i class="ph-fill ph-warning text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-red-500 mb-1">Butuh Revisi Dokumen</h4>
                            @php
                                $lastRejectLog = $booking->logs->where('action', 'REJECTED')->last();
                            @endphp
                            <p class="text-[11px] text-red-400 leading-relaxed">{{ $lastRejectLog ? $lastRejectLog->notes : 'Dokumen Anda perlu diperbaiki. Silakan unggah ulang dokumen yang sesuai.' }}</p>
                        </div>
                    </div>
                    
                    {{-- Form Revisi --}}
                    <div class="mt-2 border-t border-red-500/20 pt-4">
                        <form action="{{ route('booking.revise', $booking->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="form-revisi">
                            @csrf
                            @foreach($booking->workflow->requirements->where('is_mandatory', true) as $req)
                            <div>
                                <label class="block text-xs font-bold text-red-400 mb-1">{{ $req->document_name }} <span class="text-red-500">*</span></label>
                                <input type="file" name="requirement_{{ $req->id }}" required class="block w-full text-xs text-slate-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100 dark:file:bg-red-900/30 dark:file:text-red-400 dark:hover:file:bg-red-900/50 transition-colors">
                            </div>
                            @endforeach
                            <button type="button" onclick="submitRevisi()" class="w-full py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-bold shadow-md transition-colors flex justify-center items-center gap-2">
                                <i class="ph ph-upload-simple"></i> Kirim Revisi
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-8 shadow-sm dark:shadow-none transition-colors">
                    <h3 class="font-heading font-bold text-slate-900 dark:text-white mb-8">Status Progress</h3>
                    
                    <div class="relative space-y-8">
                        <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-slate-100 dark:bg-[#2A2A2A]"></div>

                        {{-- Log History --}}
                        @foreach($booking->logs->sortBy('created_at') as $log)
                        <div class="relative flex gap-6">
                            @php
                                $icon = 'ph-check';
                                $bg = 'bg-kinetic-primary';
                                $textColor = 'text-teal-600 dark:text-kinetic-primary';
                                
                                if ($log->action === 'REJECTED') {
                                    $icon = 'ph-x';
                                    $bg = 'bg-red-500';
                                    $textColor = 'text-red-500';
                                } elseif ($log->action === 'REVISED') {
                                    $icon = 'ph-arrow-counter-clockwise';
                                    $bg = 'bg-amber-500';
                                    $textColor = 'text-amber-500';
                                } elseif ($log->action === 'CANCELLED') {
                                    $icon = 'ph-prohibit';
                                    $bg = 'bg-slate-500';
                                    $textColor = 'text-slate-500';
                                } elseif ($log->action === 'SUBMITTED' || $log->action === 'CREATED') {
                                    $icon = 'ph-paper-plane-right';
                                }
                            @endphp
                            
                            <div class="w-8 h-8 rounded-full {{ $bg }} text-white flex items-center justify-center z-10 shadow-lg {{ str_replace('bg-', 'shadow-', $bg) }}/20">
                                <i class="ph-bold {{ $icon }} text-sm"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold {{ $textColor }} mb-1 uppercase tracking-widest">{{ $log->created_at->translatedFormat('d M Y • H:i') }}</p>
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">{{ $log->action }}</h4>
                                <p class="text-xs text-slate-500 dark:text-gray-500 leading-relaxed">{{ $log->notes ?: 'Tindakan dicatat oleh sistem.' }}</p>
                            </div>
                        </div>
                        @endforeach

                        {{-- Pending Step if applicable --}}
                        @if($booking->status === 'Pending')
                        <div class="relative flex gap-6">
                            <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-[#151515] border-4 border-white dark:border-[#151515] ring-2 ring-slate-200 dark:ring-[#2A2A2A] flex items-center justify-center z-10">
                                <div class="w-2 h-2 rounded-full bg-amber-400 dark:bg-amber-600"></div>
                            </div>
                            <div class="opacity-70">
                                <p class="text-[10px] font-bold text-amber-500 mb-1 uppercase tracking-widest">PENDING</p>
                                @php
                                    // Use dynamically built bookingSteps first, fallback to workflow template steps
                                    $currentBookingStep = $booking->bookingSteps->where('step_order', $booking->current_step)->first();
                                    $currentStepPosition = $currentBookingStep?->position?->name
                                        ?? $booking->workflow->steps->where('step_order', $booking->current_step)->first()?->position?->name;
                                @endphp
                                <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">{{ $currentStepPosition ? 'Persetujuan ' . $currentStepPosition : 'Menunggu Proses' }}</h4>
                                <p class="text-xs text-slate-500 dark:text-gray-500 leading-relaxed">Verifikasi oleh pejabat terkait</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="lg:col-span-7 space-y-6">
                
                <div class="relative h-64 rounded-3xl overflow-hidden group shadow-xl border border-slate-200 dark:border-[#2A2A2A]">
                    <img src="{{ $booking->room->image ? asset('storage/'.$booking->room->image) : 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1200' }}" alt="Room" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                    <div class="absolute bottom-8 left-8">
                        <span class="px-2 py-1 bg-kinetic-primary/20 backdrop-blur-md text-kinetic-primary border border-kinetic-primary/30 rounded text-[10px] font-bold uppercase mb-3 inline-block">{{ $booking->room->room_name }}</span>
                        <h3 class="text-3xl font-heading font-extrabold text-white">{{ $booking->room->building->name ?? $booking->room->building->building_name }}</h3>
                        <p class="text-sm text-gray-300">Lantai {{ $booking->room->floor ?? '-' }} • Kapasitas {{ $booking->room->capacity }} Orang</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 transition-colors shadow-sm dark:shadow-none">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-4">Waktu Peminjaman</p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <i class="ph ph-calendar text-kinetic-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d M Y') }}</p>
                                    <p class="text-[10px] text-slate-500">Tanggal Kegiatan</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="ph ph-clock text-kinetic-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white">{{ date('H:i', strtotime($booking->start_time)) }} - {{ date('H:i', strtotime($booking->end_time)) }} WIB</p>
                                    <p class="text-[10px] text-slate-500">Durasi: {{ \Carbon\Carbon::parse($booking->end_time)->diffInHours(\Carbon\Carbon::parse($booking->start_time)) }} Jam</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 transition-colors shadow-sm dark:shadow-none">
                        <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-4">Keperluan & Kegiatan</p>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <i class="ph ph-article text-kinetic-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">{{ $booking->event_name }}</p>
                                    <p class="text-[10px] text-slate-500">Nama Kegiatan</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <i class="ph ph-text-align-left text-kinetic-primary text-xl"></i>
                                <div>
                                    <p class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">{{ $booking->event_description ?: '-' }}</p>
                                    <p class="text-[10px] text-slate-500">Deskripsi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 transition-colors shadow-sm dark:shadow-none">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-4">Dokumen Terlampir</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($booking->attachments as $doc)
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-[#1A1A1A] border border-slate-100 dark:border-[#222] rounded-xl group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#111] flex items-center justify-center text-kinetic-primary shadow-sm">
                                    <i class="ph ph-file-pdf text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-[120px]">{{ $doc->document_type }}</p>
                                    <p class="text-[10px] text-slate-400">{{ $doc->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('booking.attachment.show', ['id' => $booking->id, 'attachmentId' => $doc->id]) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-kinetic-primary hover:text-white text-slate-400 transition-all">
                                <i class="ph ph-eye text-lg"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-8 flex items-center gap-8 transition-colors">
                    <div class="w-32 h-32 bg-white dark:bg-[#222] rounded-2xl flex items-center justify-center border border-slate-300 dark:border-[#333] shrink-0 relative overflow-hidden group">
                        @if($booking->status === 'Approved')
                        <img src="data:image/png;base64, {!! App\Support\QrCodeHelper::generateBase64(url('/validate/'.$booking->id)) !!}" class="w-24 h-24 object-contain transition-transform group-hover:scale-105 z-10">
                        @else
                        <i class="ph-fill ph-lock-key text-4xl text-slate-400 dark:text-gray-600 transition-transform group-hover:scale-110 z-10"></i>
                        @endif
                        <div class="absolute inset-0 bg-black/5 dark:bg-black/20"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white">E-Sertifikat & Izin Digital</h3>
                            <div class="flex gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full {{ $booking->status === 'Approved' ? 'bg-kinetic-primary' : 'bg-red-500' }}"></span>
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-gray-600"></span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed mb-6">
                            Dokumen digital dan QR Code akses pintu akan aktif secara otomatis setelah status pesanan berubah menjadi <span class="text-teal-600 dark:text-kinetic-primary font-bold">"Disetujui"</span>.
                        </p>
                        
                        @if($booking->status === 'Approved')
                        <a href="{{ route('booking.pdf', $booking->id) }}" target="_blank" class="flex w-max items-center gap-2 px-6 py-2.5 bg-kinetic-primary hover:bg-teal-600 text-white rounded-xl text-xs font-bold border border-transparent transition-colors shadow-md shadow-kinetic-primary/30">
                            <i class="ph ph-download-simple"></i> Unduh PDF Izin
                        </a>
                        @else
                        <button disabled class="flex items-center gap-2 px-6 py-2.5 bg-slate-200 dark:bg-[#111] text-slate-400 dark:text-gray-600 rounded-xl text-xs font-bold border border-slate-300 dark:border-[#2A2A2A] cursor-not-allowed transition-colors">
                            <i class="ph ph-lock"></i> Belum Tersedia
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>

<script>
    async function cancelBooking(id) {
        if (!confirm('Apakah Anda yakin ingin membatalkan peminjaman ini?')) return;
        
        try {
            const cancelUrl = `{{ route('booking.cancel', ['id' => '__ID__']) }}`.replace('__ID__', id);
        const response = await fetch(cancelUrl, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            if (response.ok) {
                alert('Berhasil dibatalkan!');
                window.location.reload();
            } else {
                alert(data.error || 'Terjadi kesalahan.');
            }
        } catch (e) {
            alert('Gagal terhubung ke server.');
        }
    }

    async function submitRevisi() {
        const form = document.getElementById('form-revisi');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const btn = form.querySelector('button');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="ph ph-spinner animate-spin"></i> Mengirim...';
        btn.disabled = true;

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            if (response.ok) {
                alert('Berhasil mengirim revisi!');
                window.location.reload();
            } else {
                if (data.errors) {
                    alert(Object.values(data.errors).flat().join('\n'));
                } else {
                    alert(data.error || 'Terjadi kesalahan.');
                }
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        } catch (e) {
            alert('Gagal mengirim data.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>