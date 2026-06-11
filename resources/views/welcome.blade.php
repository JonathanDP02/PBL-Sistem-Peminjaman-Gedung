<x-guest-layout>

<section class="relative pt-32 pb-12 lg:pt-40 lg:pb-24 flex items-center justify-center text-center">
    <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col items-center">
        <div class="max-w-3xl mx-auto w-full">
            <div class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-[#14B8A6]/10 border border-[#14B8A6]/30 text-[#14B8A6] text-sm font-semibold mb-8 backdrop-blur-sm">
                <span class="w-2 h-2 rounded-full bg-[#10ECE8] animate-pulse"></span>
                Sistem Peminjaman Gedung
            </div>
            
            <h1 class="mb-6 text-4xl font-extrabold leading-tight text-slate-900 dark:text-white sm:text-5xl tracking-wide">
                Reservasi <span class="text-[#14B8A6]">Gedung</span> & Fasilitas Kini Lebih Mudah.
            </h1>
            
            <p class="mb-10 text-base font-medium text-slate-600 dark:text-slate-400 sm:text-lg leading-relaxed max-w-2xl mx-auto">
                SpaceIn mengotomatisasi alur birokrasi peminjaman ruang kelas, aula, dan lab. Cek ketersediaan ruangan secara langsung dan ajukan peminjaman tanpa repot.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-3.5 text-base font-bold text-white dark:text-black transition-all bg-[#14B8A6] rounded-xl hover:bg-[#10ECE8] shadow-lg shadow-[#14B8A6]/30 hover:-translate-y-1">
                    Mulai Pinjam Sekarang
                    <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="py-16 relative mb-20">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .fc {
            --fc-border-color: #e2e8f0;
            --fc-button-text-color: #475569;
            --fc-button-bg-color: #ffffff;
            --fc-button-border-color: #e2e8f0;
            --fc-button-hover-bg-color: #f8fafc;
            --fc-button-hover-border-color: #cbd5e1;
            --fc-button-active-bg-color: #14b8a6;
            --fc-button-active-border-color: #14b8a6;
            --fc-button-active-text-color: #ffffff;
            --fc-today-bg-color: rgba(20, 184, 166, 0.05);
            --fc-list-event-hover-bg-color: #f1f5f9;
            font-family: inherit;
        }

        .dark .fc {
            --fc-border-color: #2A2A2A;
            --fc-button-text-color: #94a3b8;
            --fc-button-bg-color: #1A1A1A;
            --fc-button-border-color: #2A2A2A;
            --fc-button-hover-bg-color: #222;
            --fc-button-hover-border-color: #333;
            --fc-page-bg-color: transparent;
            --fc-list-event-hover-bg-color: #1e1e1e;
        }

        .fc .fc-button {
            padding: 8px 16px;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            border-radius: 12px !important;
            transition: all 0.2s;
            margin-right: 4px !important;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        .fc-event {
            cursor: pointer;
            border: none !important;
            border-radius: 8px;
            padding: 4px 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .fc-event:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.025em;
        }

        .dark .fc .fc-toolbar-title {
            color: #f8fafc !important;
        }

        .fc .fc-button-primary:not(:disabled):active, 
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            color: #ffffff !important;
        }
    </style>

    <div class="custom-container" style="align-items: flex-start; max-width: 1200px; margin: 0 auto; width: 100%;">
        <div class="flex flex-col lg:flex-row justify-between w-full lg:items-center gap-6 mb-8">
            <div class="text-left max-w-xl">
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 dark:text-white mb-3">Ketersediaan <span class="text-[#14B8A6]">Ruang</span></h2>
                <p class="text-sm text-slate-600 dark:text-gray-400 leading-relaxed">Lihat jadwal penggunaan fasilitas kampus secara langsung. Klik pada slot tersedia untuk mulai memesan.</p>
            </div>

            <div class="flex flex-wrap items-center gap-4 lg:justify-end w-full lg:w-auto">
                <!-- Premium Room & Date Filter -->
                <form action="{{ route('welcome') }}" method="GET" class="flex flex-wrap items-end gap-3">
                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Ruangan</span>
                        <select name="room_id" onchange="this.form.submit()" 
                                class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-gray-300 px-3 py-2 focus:outline-none focus:border-[#14B8A6] focus:ring-1 focus:ring-[#14B8A6] transition cursor-pointer font-semibold h-10 min-w-[200px]">
                            <option value="" class="bg-white dark:bg-[#1A1A1A] text-slate-700 dark:text-gray-300">-- Semua Ruangan --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ $selectedRoomId == $room->id ? 'selected' : '' }} class="bg-white dark:bg-[#1A1A1A] text-slate-700 dark:text-gray-300">
                                    {{ $room->room_name }} ({{ $room->building->building_name ?? 'Gedung' }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex flex-col gap-1">
                        <span class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Tanggal</span>
                        <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}" 
                               class="bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm text-slate-700 dark:text-gray-300 px-3 py-2 focus:outline-none focus:border-[#14B8A6] focus:ring-1 focus:ring-[#14B8A6] transition cursor-pointer dark:[color-scheme:dark] font-semibold h-10" 
                               onchange="this.form.submit()">
                    </div>

                    @if(request('date') || request('room_id'))
                        <a href="{{ route('welcome') }}" class="px-4 h-10 flex items-center justify-center bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/20 rounded-xl text-xs text-rose-500 transition font-extrabold tracking-wide uppercase" title="Reset Filter">
                            Reset
                        </a>
                    @endif
                </form>

                <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400 dark:text-gray-500 tracking-wider uppercase bg-slate-50 dark:bg-white/5 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-white/10 h-10 self-end">
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-[#14B8A6]"></span> Tersedia</span>
                    <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Terisi</span>
                </div>
            </div>
        </div>

        @php
            Carbon\Carbon::setLocale('id');
            $firstDate = $weekDates->first();
            $lastDate = $weekDates->last();
            $startMonth = $firstDate->translatedFormat('F');
            $endMonth = $lastDate->translatedFormat('F');
            $year = $firstDate->format('Y');
            
            if ($startMonth !== $endMonth) {
                $startYear = $firstDate->format('Y');
                $endYear = $lastDate->format('Y');
                if ($startYear !== $endYear) {
                    $monthTitle = "$startMonth $startYear – $endMonth $endYear";
                } else {
                    $monthTitle = "$startMonth – $endMonth $year";
                }
            } else {
                $monthTitle = "$startMonth $year";
            }
        @endphp

        @if($selectedRoomId)
            <!-- Month Name above calendar -->
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-base sm:text-lg font-heading font-extrabold text-slate-800 dark:text-white tracking-wide uppercase flex items-center gap-2">
                    <i class="ph ph-calendar-blank text-[#14B8A6] text-xl animate-pulse"></i>
                    Periode: <span class="text-[#14B8A6]">{{ $monthTitle }}</span>
                </h3>
            </div>

            <div class="calendar-wrapper">
                <div class="calendar-grid">
                    
                    <div class="col-header time-label" style="padding-top:16px!important">
                        <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    
                    @php
                        Carbon\Carbon::setLocale('id');
                        $timeSlots = ['00:00', '08:00', '10:00', '13:00', '15:00', '18:00'];
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

                    @php
                        $slotRanges = [
                            '00:00' => ['start' => '00:00:00', 'end' => '08:00:00'],
                            '08:00' => ['start' => '08:00:00', 'end' => '10:00:00'],
                            '10:00' => ['start' => '10:00:00', 'end' => '13:00:00'],
                            '13:00' => ['start' => '13:00:00', 'end' => '15:00:00'],
                            '15:00' => ['start' => '15:00:00', 'end' => '18:00:00'],
                            '18:00' => ['start' => '18:00:00', 'end' => '23:59:59']
                        ];
                    @endphp

                    @foreach($timeSlots as $slot)
                        <div class="time-label">{{ $slot }}</div>
                        @foreach($weekDates as $date)
                            @php
                                $isWeekend = $date->isWeekend();
                                $cellClass = 'cell';
                                if ($isWeekend) $cellClass .= ' weekend-bg text-center';
                                
                                $slotRange = $slotRanges[$slot];
                                $slotBooking = $bookings->filter(function($b) use ($date, $slotRange) {
                                    $start = \Carbon\Carbon::parse($b->booking_date)->startOfDay();
                                    $end = \Carbon\Carbon::parse($b->booking_end_date ?? $b->booking_date)->endOfDay();
                                    if (!$date->between($start, $end)) {
                                        return false;
                                    }
                                    
                                    $bStart = $b->start_time;
                                    $bEnd = $b->end_time;
                                    $sStart = $slotRange['start'];
                                    $sEnd = $slotRange['end'];
                                    
                                    return ($bStart < $sEnd) && ($bEnd > $sStart);
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
        @else
            <!-- Tampilan Kalender Umum (FullCalendar) untuk Semua Ruangan -->
            <div class="bg-white dark:bg-[#111] rounded-3xl p-6 border border-slate-200 dark:border-white/5 shadow-lg">
                <div id="calendar" class="text-slate-800 dark:text-slate-200"></div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const rawBookings = @json($allBookings);

                    const calendarEvents = rawBookings.map(b => {
                        const statusLower = b.status ? b.status.toLowerCase() : '';
                        let bgColor = statusLower === 'approved' ? '#14b8a6' : '#3b82f6';
                        
                        const startDatePart = b.booking_date.split('T')[0].split(' ')[0];
                        const endDatePart = (b.booking_end_date || b.booking_date).split('T')[0].split(' ')[0];

                        return {
                            id: b.id,
                            title: `${b.room ? b.room.room_name : 'Ruangan'} - ${b.event_name}`,
                            start: `${startDatePart}T${b.start_time}`,
                            end: `${endDatePart}T${b.end_time}`,
                            backgroundColor: bgColor,
                            borderColor: bgColor,
                            textColor: '#ffffff',
                            extendedProps: {
                                roomName: b.room ? b.room.room_name : 'Ruangan',
                                originalStatus: b.status,
                                borrowerName: b.user ? b.user.name : 'Sistem',
                                borrowerUnit: b.user && b.user.unit ? b.user.unit.unit_name : '-'
                            }
                        };
                    });

                    const calendarEl = document.getElementById('calendar');
                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        locale: 'id',
                        buttonText: {
                            today: 'Hari Ini',
                            month: 'Bulan',
                            listWeek: 'Agenda Mingguan',
                            listDay: 'Agenda Harian'
                        },
                        slotLabelFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        },
                        eventTimeFormat: {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: false
                        },
                        initialDate: '{{ request('date', now()->format('Y-m-d')) }}',
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'dayGridMonth,listWeek,listDay'
                        },
                        height: 'auto',
                        contentHeight: 'auto',
                        fixedWeekCount: false,
                        events: calendarEvents,
                        eventClick: function(info) {
                            const props = info.event.extendedProps;
                            const timeStr = `${info.event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})} - ${info.event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}`;
                            
                            const startDayStr = info.event.start.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                            let dateStr = startDayStr;
                            if (info.event.end && info.event.start.toDateString() !== info.event.end.toDateString()) {
                                const endDayStr = info.event.end.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                                dateStr = `${startDayStr} s.d. ${endDayStr}`;
                            }
                            
                            const isDark = document.documentElement.classList.contains('dark');
                            
                            Swal.fire({
                                title: `<span class="${isDark ? 'text-white' : 'text-slate-900'} font-bold">${info.event.title.split(' - ')[1] || info.event.title}</span>`,
                                html: `
                                    <div class="text-left space-y-3 mt-4">
                                        <div class="flex items-center gap-3 p-3 rounded-xl border ${isDark ? 'border-[#2A2A2A] bg-[#1A1A1A]' : 'border-slate-100 bg-slate-50'}">
                                            <div class="w-10 h-10 rounded-lg bg-teal-500/10 flex items-center justify-center text-teal-600">
                                                <i class="ph-bold ph-door text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ruangan</p>
                                                <p class="text-sm font-bold ${isDark ? 'text-slate-200' : 'text-slate-700'}">${props.roomName}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 rounded-xl border ${isDark ? 'border-[#2A2A2A] bg-[#1A1A1A]' : 'border-slate-100 bg-slate-50'}">
                                            <div class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-600">
                                                <i class="ph-bold ph-calendar text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Waktu</p>
                                                <p class="text-sm font-bold ${isDark ? 'text-slate-200' : 'text-slate-700'}">${dateStr}<br>${timeStr} WIB</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 rounded-xl border ${isDark ? 'border-[#2A2A2A] bg-[#1A1A1A]' : 'border-slate-100 bg-slate-50'}">
                                            <div class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-600">
                                                <i class="ph-bold ph-user text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Peminjam</p>
                                                <p class="text-sm font-bold ${isDark ? 'text-slate-200' : 'text-slate-700'}">${props.borrowerName} (${props.borrowerUnit})</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3 p-3 rounded-xl border ${isDark ? 'border-[#2A2A2A] bg-[#1A1A1A]' : 'border-slate-100 bg-slate-50'}">
                                            <div class="w-10 h-10 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-600">
                                                <i class="ph-bold ph-info text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                                                <p class="text-sm font-bold ${isDark ? 'text-slate-200' : 'text-slate-700'}">${props.originalStatus}</p>
                                            </div>
                                        </div>
                                    </div>
                                `,
                                icon: 'info',
                                iconColor: '#14b8a6',
                                background: isDark ? '#151515' : '#ffffff',
                                confirmButtonColor: '#14b8a6',
                                confirmButtonText: 'Tutup',
                                customClass: {
                                    popup: 'rounded-[2rem] border border-slate-200 dark:border-[#2A2A2A] shadow-2xl',
                                    confirmButton: 'rounded-xl px-8 py-3 font-bold text-sm transition transform hover:scale-105 shadow-lg shadow-teal-500/20'
                                }
                            });
                        }
                    });
                    
                    calendar.render();
                });
            </script>
        @endif
    </div>
</section>

<!-- RUANG POPULER SECTION -->
<section class="py-16 relative mb-24">
    <div class="custom-container" style="max-width: 1200px; margin: 0 auto; width: 100%; px-4">
        <div class="flex items-center gap-3 mb-8">
            <svg class="text-[#14B8A6] w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
            </svg>
            <h2 class="text-3xl font-bold text-slate-900 dark:text-white">Ruang Populer</h2>
        </div>

        @if($popularRooms->isNotEmpty())
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Large Card: First dynamic room -->
            @php $firstRoom = $popularRooms->first(); @endphp
            <div class="lg:col-span-5 bg-[#0a0a0a] rounded-2xl relative overflow-hidden group flex flex-col justify-end border border-[#14B8A6]/20 hover:border-[#14B8A6]/30 min-h-[400px]">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('{{ $firstRoom->image ? asset('storage/' . $firstRoom->image) : 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80' }}'); opacity: 0.45;"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/75 to-transparent"></div>
                
                <div class="relative z-10 p-8 text-left">
                    <span class="inline-block px-3 py-1 mb-4 text-[10px] font-bold tracking-wider text-[#14B8A6] bg-[#14B8A6]/10 dark:bg-[#14B8A6]/20 rounded-full border border-[#14B8A6]/30">{{ strtoupper($firstRoom->building->building_name ?? 'LOKASI UTAMA') }}</span>
                    <h3 class="text-3xl font-bold text-white mb-2">{{ $firstRoom->room_name }}</h3>
                    <p class="text-sm text-gray-300 mb-6 max-w-sm">{{ $firstRoom->description ?? 'Fasilitas premium dengan kenyamanan dan teknologi terkini.' }}</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 dark:text-gray-300">KAPASITAS: {{ $firstRoom->capacity }} ORANG</span>
                        <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-[#14B8A6] text-white dark:text-black font-semibold rounded-xl hover:bg-[#10ECE8] transition shadow-[0_0_20px_rgba(20,184,166,0.3)]">
                            Pesan Sekarang
                        </a>
                    </div>
                </div>
            </div>
 
            <!-- Right Side Grid: Rest of dynamic rooms -->
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                @foreach($popularRooms->skip(1) as $room)
                <div class="bg-white dark:bg-[#111] p-6 rounded-2xl border border-slate-200 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-[#151515] hover:border-[#14B8A6]/30 transition group flex flex-col text-left">
                    @if($room->image)
                        <div class="w-full h-32 rounded-xl overflow-hidden mb-4 relative">
                            <img src="{{ asset('storage/' . $room->image) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" alt="{{ $room->room_name }}">
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 dark:text-gray-300 mb-4 group-hover:bg-[#14B8A6]/10 group-hover:text-[#14B8A6] transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                    @endif
                    <span class="text-[10px] font-bold text-[#14B8A6] uppercase mb-1">{{ $room->building->building_name ?? 'Gedung' }}</span>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2 group-hover:text-[#10ECE8] transition">{{ $room->room_name }}</h3>
                    <p class="text-xs text-slate-500 dark:text-gray-500 mb-6 flex-grow truncate">{{ $room->description ?? 'Fasilitas premium siap digunakan.' }}</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                        <span class="text-xs font-semibold text-slate-500 dark:text-gray-400">KAPASITAS: {{ $room->capacity }} ORANG</span>
                        <a href="{{ route('login') }}" class="text-[#14B8A6] group-hover:text-white transition">
                            <svg class="w-5 h-5 text-slate-400 dark:text-gray-500 group-hover:text-[#10ECE8] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
 
                <!-- Dynamic Web Help Card to balance grid if only 1 or 2 rooms on the right -->
                <div class="sm:col-span-2 bg-[#f0fdfa] dark:bg-[#0c2423] border border-[#14B8A6]/20 p-8 rounded-2xl flex items-center justify-between group overflow-hidden relative text-left">
                    <div class="absolute inset-0 bg-[#14B8A6]/5 opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Butuh Bantuan?</h3>
                        <p class="text-sm text-slate-600 dark:text-[#14B8A6]/80">Tim IT kami siap membantu instalasi perangkat di ruangan pilihan Anda.</p>
                    </div>
                    <div class="relative z-10 w-12 h-12 rounded-xl bg-[#14B8A6] text-white dark:text-black flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
 
            </div>
        </div>
        @else
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            <!-- Left Large Card: Gedung Riset Pusat -->
            <div class="lg:col-span-5 bg-white dark:bg-[#111] rounded-2xl relative overflow-hidden group flex flex-col justify-end border border-slate-200 dark:border-white/5 hover:border-[#14B8A6]/30 min-h-[400px]">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80'); opacity: 0.4;"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-white dark:from-[#0A0A0A] via-white/90 dark:via-[#0A0A0A]/80 to-transparent"></div>
                
                <div class="relative z-10 p-8 text-left">
                    <span class="inline-block px-3 py-1 mb-4 text-[10px] font-bold tracking-wider text-[#14B8A6] bg-[#14B8A6]/10 dark:bg-[#14B8A6]/20 rounded-full border border-[#14B8A6]/30">PRIME LOCATION</span>
                    <h3 class="text-3xl font-bold text-slate-900 dark:text-white mb-2">Gedung Riset Pusat</h3>
                    <p class="text-sm text-slate-600 dark:text-gray-400 mb-6 max-w-sm">Fasilitas kelas dunia dengan dukungan teknis 24/7 untuk mahasiswa tingkat akhir.</p>
                    <a href="{{ route('ruangan') }}" class="inline-block px-6 py-3 bg-[#14B8A6] text-white dark:text-black font-semibold rounded-xl hover:bg-[#10ECE8] transition shadow-[0_0_20px_rgba(20,184,166,0.3)]">
                        Pesan Sekarang
                    </a>
                </div>
            </div>

            <!-- Right Side Grid -->
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-6">
                
                <!-- Ruang Kolaborasi -->
                <div class="bg-white dark:bg-[#111] p-6 rounded-2xl border border-slate-200 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-[#151515] transition group flex flex-col text-left">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 dark:text-gray-300 mb-6 group-hover:bg-[#14B8A6]/10 group-hover:text-[#14B8A6] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('Ruang Kolaborasi') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mb-8 flex-grow">Cocok untuk diskusi kelompok hingga 8 orang.</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                        <span class="text-xs font-semibold text-slate-500 dark:text-gray-400">12 RUANGAN</span>
                        <svg class="w-5 h-5 text-slate-400 dark:text-gray-500 group-hover:text-[#10ECE8] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

                <!-- Auditorium Mini -->
                <div class="bg-white dark:bg-[#111] p-6 rounded-2xl border border-slate-200 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-[#151515] transition group flex flex-col text-left">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-500 dark:text-gray-300 mb-6 group-hover:bg-[#14B8A6]/10 group-hover:text-[#14B8A6] transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">{{ __('Auditorium Mini') }}</h3>
                    <p class="text-xs text-slate-500 dark:text-gray-400 mb-8 flex-grow">Dilengkapi sound system dan proyektor 4K.</p>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-white/5">
                        <span class="text-xs font-semibold text-slate-500 dark:text-gray-400">4 RUANGAN</span>
                        <svg class="w-5 h-5 text-slate-400 dark:text-gray-500 group-hover:text-[#10ECE8] transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

                <!-- Web Help Card -->
                <div class="sm:col-span-2 bg-[#f0fdfa] dark:bg-[#0c2423] border border-[#14B8A6]/20 p-8 rounded-2xl flex items-center justify-between group overflow-hidden relative text-left">
                    <div class="absolute inset-0 bg-[#14B8A6]/5 opacity-0 group-hover:opacity-100 transition"></div>
                    <div class="relative z-10">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Butuh Bantuan?</h3>
                        <p class="text-sm text-slate-600 dark:text-[#14B8A6]/80">Tim IT kami siap membantu instalasi perangkat di ruangan pilihan Anda.</p>
                    </div>
                    <div class="relative z-10 w-12 h-12 rounded-xl bg-[#14B8A6] text-white dark:text-black flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>

            </div>
        </div>
        @endif
    </div>
</section>

</x-guest-layout>

