<x-app-layout>
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded mb-8">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-lg font-medium text-green-800">✅ Dokumen Sertifikat VALID dan SAH</h3>
                <p class="text-sm text-green-700 mt-1">Dokumen ini telah diverifikasi dan diterbitkan oleh Politeknik Negeri Malang</p>
            </div>
        </div>
    </div>

    {{-- Perihal --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="border-b pb-4 mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Surat Izin Peminjaman Ruangan</h2>
            <p class="text-sm text-gray-600 mt-1">Space.in — Sistem Reservasi Ruangan Polinema</p>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">No. Booking</p>
                <p class="text-lg font-semibold text-gray-800">#{{ $booking->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Status</p>
                <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $booking->status }}
                </span>
            </div>
        </div>
    </div>

    {{-- Detail Event --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Detail Kegiatan</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600">Nama Kegiatan</p>
                <p class="text-base font-medium text-gray-800">{{ $booking->event_name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Peminjam</p>
                <p class="text-base font-medium text-gray-800">{{ $booking->user->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Tanggal</p>
                <p class="text-base font-medium text-gray-800">{{ $booking->booking_date->format('d F Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Waktu</p>
                <p class="text-base font-medium text-gray-800">{{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }} WIB</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Ruangan</p>
                <p class="text-base font-medium text-gray-800">{{ $booking->room->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600">Gedung</p>
                <p class="text-base font-medium text-gray-800">{{ $booking->room->building->name }}</p>
            </div>
        </div>

        @if ($booking->event_description)
            <div class="mt-4 pt-4 border-t">
                <p class="text-sm text-gray-600">Deskripsi</p>
                <p class="text-base text-gray-800">{{ $booking->event_description }}</p>
            </div>
        @endif
    </div>

    {{-- Tabel Persetujuan --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Riwayat Persetujuan</h3>

        @if ($booking->approvals->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Langkah</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Jabatan</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Disetujui Oleh</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Status</th>
                            <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold">Tanggal Persetujuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($booking->approvals as $approval)
                            <tr class="hover:bg-gray-50">
                                <td class="border border-gray-300 px-4 py-2 text-sm">{{ $approval->step->step_order ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm">{{ $approval->step->position->name ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm font-medium">{{ $approval->approver->name ?? '-' }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-sm">
                                    <span class="inline-block {{ $approval->approval_status === 'Approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} px-2 py-1 rounded text-xs font-semibold">
                                        {{ ucfirst($approval->approval_status) }}
                                    </span>
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-sm">
                                    {{ $approval->approved_at?->format('d F Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-600 py-4">Belum ada persetujuan</p>
        @endif
    </div>

    {{-- Catatan Penting --}}
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <p class="text-sm text-yellow-800">
            <strong>⚠️ Penting:</strong> Jika dokumen ini tidak dapat diverifikasi di halaman verifikasi resmi Space.in, berarti dokumen tersebut adalah palsu. Hubungi admin Polinema jika Anda menemukan aktivitas mencurigakan.
        </p>
    </div>

    {{-- Download Button --}}
    <div class="text-center">
        @auth
            <a href="{{ route('booking.pdf', $booking->id) }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                📥 Download PDF Lengkap
            </a>
        @else
            <p class="text-sm text-gray-600">
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Login</a> untuk download PDF lengkap
            </p>
        @endauth
    </div>

    <div class="text-center mt-8 pt-8 border-t text-sm text-gray-600">
        <p>Generated by Space.in @ {{ now()->format('d F Y H:i') }}</p>
    </div>
</div>
</x-app-layout>
