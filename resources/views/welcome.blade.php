<x-guest-layout>

<section class="relative pt-32 pb-12 lg:pt-40 lg:pb-24 flex items-center justify-center text-center">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col items-center">
        <div class="max-w-3xl mx-auto w-full">
            <div class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-[#14B8A6]/10 border border-[#14B8A6]/30 text-[#14B8A6] text-sm font-semibold mb-8 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-[#10ECE8] animate-pulse"></span>
                Sistem Peminjaman Gedung
            </div>
            
            <h1 class="mb-6 text-4xl font-extrabold leading-tight text-white sm:text-5xl tracking-wide">
                Reservasi <span class="text-[#14B8A6]">Gedung</span> & Fasilitas Kini Lebih Mudah.
            </h1>
            
            <p class="mb-10 text-base font-medium text-slate-400 sm:text-lg leading-relaxed max-w-2xl mx-auto">
                SpaceIn mengotomatisasi alur birokrasi peminjaman ruang kelas, aula, dan lab. Cek ketersediaan ruangan secara real-time dan ajukan peminjaman tanpa repot.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-3.5 text-base font-bold text-black transition-all bg-[#14B8A6] rounded-xl hover:bg-[#10ECE8] shadow-lg shadow-[#14B8A6]/30 hover:-translate-y-1">
                    Mulai Pinjam Sekarang
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-16 relative mb-20">
    <div class="custom-container" style="align-items: flex-start; max-width: 1200px; margin: 0 auto; width: 100%;">
        <div class="flex justify-between w-full items-end mb-4">
            <div class="text-left">
                <h2 class="text-5xl font-bold text-white mb-2">Ketersediaan <span class="text-[#14B8A6]">Ruang</span></h2>
                <p class="text-gray-400">Lihat jadwal penggunaan fasilitas kampus secara real-time. Klik pada slot tersedia untuk mulai memesan.</p>
            </div>
            <div class="flex gap-4 items-center">
                <a href="{{ route('ruangan') }}" class="px-4 py-2 bg-white/5 border border-white/10 rounded-lg text-sm text-gray-300 flex items-center gap-2 hover:bg-white/10 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Lihat Semua Ruang
                </a>
                <div class="flex items-center gap-3 text-xs font-bold text-gray-400">
                    <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-[#14B8A6]"></div> TERSEDIA</span>
                    <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-red-500"></div> TERISI</span>
                </div>
            </div>
        </div>

        <div class="calendar-wrapper">
            <div class="calendar-grid">
                
                <div class="col-header time-label" style="padding-top:16px!important">
                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                </div>
                
                @php
                    Carbon\Carbon::setLocale('id');
                    $timeSlots = ['08:00', '10:00', '13:00', '15:00'];
                @endphp

                @foreach($weekDates as $date)
                    @php
                        $isToday = $date->isToday();
                        $isWeekend = $date->isWeekend();
                        $headerClass = 'col-header';
                        if ($isToday) $headerClass .= ' active text-[#14B8A6]';
                        elseif ($isWeekend) $headerClass .= ' dim weekend-bg';
                    @endphp
                    <div class="{{ $headerClass }}">
                        {{ strtoupper($date->translatedFormat('l')) }}<br>
                        <span class="{{ $isToday ? 'text-[#14B8A6]' : '' }}">{{ $date->format('d') }}</span>
                    </div>
                @endforeach

                @foreach($timeSlots as $slot)
                    <div class="time-label">{{ $slot }}</div>
                    @foreach($weekDates as $date)
                        @php
                            $isWeekend = $date->isWeekend();
                            $cellClass = 'cell';
                            if ($isWeekend) $cellClass .= ' weekend-bg text-center';
                            
                            $slotPrefix = substr($slot, 0, 2);
                            $slotBooking = $bookings->filter(function($b) use ($date, $slotPrefix) {
                                return Carbon\Carbon::parse($b->booking_date)->isSameDay($date) && 
                                       str_starts_with($b->start_time, $slotPrefix);
                            })->first();
                        @endphp
                        <div class="{{ $cellClass }}">
                            @if($slotBooking)
                                <div class="slot terisi">
                                    <div class="slot-header">{{ strtoupper($slotBooking->status) == 'PENDING' ? 'PENDING' : 'TERISI' }}</div>
                                    <p class="slot-title">{{ $slotBooking->room->room_name ?? 'Ruangan' }}</p>
                                </div>
                            @else
                                <div class="slot tersedia">
                                    <div class="slot-header">TERSEDIA</div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endforeach

            </div>
        </div>
    </div>
</section>

<!-- RUANG POPULER SECTION -->
<section class="py-16 relative mb-24">
    <div class="custom-container" style="max-width: 1200px; margin: 0 auto; width: 100%; px-4">
        <div class="flex items-center gap-3 mb-8">
            <svg class="text-[#14B8A6] w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
            <h2 class="text-3xl font-bold text-white">Ruang Populer</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Large Card: Gedung Riset Pusat -->
            <div class="lg:col-span-5 bg-[#111] rounded-2xl relative overflow-hidden group flex flex-col justify-end border border-white/5 hover:border-[#14B8A6]/30 min-h-[400px]">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80'); opacity: 0.4;"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/80 to-transparent"></div>
                
                <div class="relative z-10 p-8">
                    <span class="inline-block px-3 py-1 mb-4 text-[10px] font-bold tracking-wider text-[#14B8A6] bg-[#14B8A6]/20 rounded-full border border-[#14B8A6]/30">PRIME LOCATION</span>
                    <h3 class="text-3xl font-bold text-white mb-2">Gedung Riset Pusat</h3>
                    <p class="text-sm text-gray-400 mb-6 max-w-sm">Fasilitas kelas dunia dengan dukungan teknis 24/7 untuk mahasiswa tingkat akhir.</p>
                    <a href="{{ route('ruangan') }}" class="inline-block px-6 py-3 bg-[#14B8A6] text-black font-semibold rounded-xl hover:bg-[#10ECE8] transition shadow-[0_0_20px_rgba(20,184,166,0.3)]">
                        Pesan Sekarang
                    </a>
                </div>
            </div>

            <!-- Right Side Grid -->
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- Ruang Kolaborasi -->
                <div class="bg-[#111] p-6 rounded-2xl border border-white/5 hover:bg-[#151515] transition group flex flex-col">
                    <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-gray-300 mb-6 group-hover:bg-[#14B8A6]/10 group-hover:text-[#14B8A6] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Ruang Kolaborasi</h3>
                    <p class="text-xs text-gray-500 mb-8 flex-grow">Cocok untuk diskusi kelompok hingga 8 orang.</p>
                    <div class="flex items-center justify-between pt-4 border-t border-white/5">
                        <span class="text-xs font-semibold text-gray-400">12 RUANGAN</span>
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

                <!-- Auditorium Mini -->
                <div class="bg-[#111] p-6 rounded-2xl border border-white/5 hover:bg-[#151515] transition group flex flex-col">
                    <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-gray-300 mb-6 group-hover:bg-[#14B8A6]/10 group-hover:text-[#14B8A6] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Auditorium Mini</h3>
                    <p class="text-xs text-gray-500 mb-8 flex-grow">Dilengkapi sound system dan proyektor 4K.</p>
                    <div class="flex items-center justify-between pt-4 border-t border-white/5">
                        <span class="text-xs font-semibold text-gray-400">4 RUANGAN</span>
                        <svg class="w-5 h-5 text-gray-500 group-hover:text-white transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

                <!-- Web Help Card -->
                <div class="sm:col-span-2 bg-[#0c2423] border border-[#14B8A6]/20 p-8 rounded-2xl flex items-center justify-between group overflow-hidden relative">
                    <div class="absolute inset-0 bg-[#14B8A6]/5 opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold text-white mb-2">Butuh Bantuan?</h3>
                        <p class="text-sm text-[#14B8A6]/80">Tim IT kami siap membantu instalasi perangkat di ruangan pilihan Anda.</p>
                    </div>
                    <div class="relative z-10 w-12 h-12 rounded-xl bg-[#14B8A6] text-black flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

</x-guest-layout>

