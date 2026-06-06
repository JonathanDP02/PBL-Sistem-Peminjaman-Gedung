<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-green-50 dark:bg-green-950/20 border-l-4 border-green-500 dark:border-green-600 p-4 rounded mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-green-800 dark:text-green-400">✅ Dokumen Sertifikat VALID dan SAH</h3>
                <p class="text-sm text-green-700 dark:text-green-500 mt-1">Dokumen ini telah diverifikasi dan diterbitkan oleh Politeknik Negeri Malang</p>
            </div>
        </div>
    </div>

    {{-- Perihal --}}
    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-3xl p-6 mb-6 transition-colors duration-300">
        <div class="border-b border-slate-100 dark:border-[#222] pb-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Surat Izin Peminjaman Ruangan</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Space.in — Sistem Reservasi Ruangan Polinema</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider">No. Booking</p>
                <p class="text-lg font-semibold text-gray-800 dark:text-white">#{{ $booking->id }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider">Status</p>
                <span class="inline-block bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $booking->status }}
                </span>
            </div>
        </div>
    </div>

    {{-- Detail Event --}}
    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-3xl p-6 mb-6 transition-colors duration-300">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-slate-100 dark:border-[#222] pb-2">Detail Kegiatan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider mb-1">Nama Kegiatan</p>
                <p class="text-base font-medium text-gray-800 dark:text-white break-all">{{ $booking->event_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider mb-1">Peminjam</p>
                <p class="text-base font-medium text-gray-800 dark:text-white">{{ $booking->user->name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider mb-1">Tanggal</p>
                <p class="text-base font-medium text-gray-800 dark:text-white">{{ $booking->booking_date->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider mb-1">Waktu</p>
                <p class="text-base font-medium text-gray-800 dark:text-white">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider mb-1">Ruangan</p>
                <p class="text-base font-medium text-gray-800 dark:text-white">{{ $booking->room->room_name }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider mb-1">Gedung</p>
                <p class="text-base font-medium text-gray-800 dark:text-white">{{ $booking->room->building->building_name }}</p>
            </div>
        </div>

        @if ($booking->event_description)
            <div class="mt-4 pt-4 border-t border-slate-100 dark:border-[#222]">
                <p class="text-xs text-gray-500 dark:text-gray-500 uppercase font-bold tracking-wider mb-1">Deskripsi</p>
                <p class="text-base text-gray-800 dark:text-slate-300 break-all whitespace-pre-line">{{ $booking->event_description }}</p>
            </div>
        @endif
    </div>

    {{-- Tabel Persetujuan --}}
    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-3xl p-6 mb-6 transition-colors duration-300">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 border-b border-slate-100 dark:border-[#222] pb-2">Riwayat Persetujuan</h3>

        @if ($booking->approvals->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-slate-200 dark:border-[#222]">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-[#111]">
                            <th class="border border-slate-200 dark:border-[#222] px-4 py-2.5 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Langkah</th>
                            <th class="border border-slate-200 dark:border-[#222] px-4 py-2.5 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Jabatan</th>
                            <th class="border border-slate-200 dark:border-[#222] px-4 py-2.5 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Disetujui Oleh</th>
                            <th class="border border-slate-200 dark:border-[#222] px-4 py-2.5 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Status</th>
                            <th class="border border-slate-200 dark:border-[#222] px-4 py-2.5 text-left text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Tanggal Persetujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking->approvals as $approval)
                            <tr class="hover:bg-slate-50 dark:hover:bg-[#111] transition-colors">
                                <td class="border border-slate-200 dark:border-[#222] px-4 py-2 text-sm text-slate-600 dark:text-slate-400">{{ $approval->step->step_order ?? '-' }}</td>
                                <td class="border border-slate-200 dark:border-[#222] px-4 py-2 text-sm text-slate-600 dark:text-slate-400">{{ $approval->step->position->name ?? '-' }}</td>
                                <td class="border border-slate-200 dark:border-[#222] px-4 py-2 text-sm font-medium text-slate-800 dark:text-white">{{ $approval->approver->name ?? '-' }}</td>
                                <td class="border border-slate-200 dark:border-[#222] px-4 py-2 text-sm">
                                    <span class="inline-block {{ $approval->approval_status === 'Approved' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400' }} px-2.5 py-1 rounded text-xs font-semibold">
                                        {{ ucfirst($approval->approval_status) }}
                                    </span>
                                </td>
                                <td class="border border-slate-200 dark:border-[#222] px-4 py-2 text-sm text-slate-600 dark:text-slate-400">
                                    {{ $approval->approved_at?->format('d F Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-slate-500 dark:text-gray-500 py-6 text-sm">Belum ada persetujuan</p>
        @endif
    </div>

    {{-- Catatan Penting --}}
    <div class="bg-yellow-50 dark:bg-yellow-950/20 border border-yellow-200 dark:border-yellow-900/50 rounded-2xl p-4 mb-6">
        <p class="text-xs text-yellow-800 dark:text-yellow-500 leading-relaxed">
            <strong>⚠️ Penting:</strong> Jika dokumen ini tidak dapat diverifikasi di halaman verifikasi resmi Space.in, berarti dokumen tersebut adalah palsu. Hubungi admin Polinema jika Anda menemukan aktivitas mencurigakan.
        </p>
    </div>

    {{-- Download Button --}}
    <div class="text-center">
        @auth
            <a href="{{ route('booking.pdf', $booking->id) }}" class="inline-flex items-center gap-2 bg-kinetic-primary hover:bg-[#10ECE8] text-slate-900 px-6 py-3 rounded-xl font-bold transition shadow-[0_0_15px_rgba(20,184,166,0.2)]">
                <i class="ph-bold ph-download-simple text-lg"></i> Download PDF Lengkap
            </a>
        @else
            <p class="text-sm text-slate-500 dark:text-gray-400">
                <a href="{{ route('login') }}" class="text-kinetic-primary hover:underline font-semibold">Masuk</a> untuk mendownload PDF lengkap.
            </p>
        @endauth
    </div>

    <div class="text-center mt-8 pt-8 border-t border-slate-200 dark:border-[#222] text-xs text-slate-400 dark:text-gray-500">
        <p>Generated by Space.in @ {{ now()->format('d F Y H:i') }}</p>
    </div>
</div>
