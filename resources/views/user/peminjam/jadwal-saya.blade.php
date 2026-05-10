<x-app-layout title="Jadwal Saya">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>

    <style>
        /* Kustomisasi Tema FullCalendar agar menyatu dengan desain UI kita (termasuk Dark Mode) */
        .fc {
            --fc-border-color: #e2e8f0;
            --fc-button-text-color: #1e293b;
            --fc-button-bg-color: #f1f5f9;
            --fc-button-border-color: #cbd5e1;
            --fc-button-hover-bg-color: #e2e8f0;
            --fc-button-hover-border-color: #cbd5e1;
            --fc-button-active-bg-color: #14b8a6; /* Teal / Kinetic Primary */
            --fc-button-active-border-color: #14b8a6;
            --fc-button-active-text-color: #ffffff;
            --fc-today-bg-color: rgba(20, 184, 166, 0.05);
            font-family: inherit;
        }

        .dark .fc {
            --fc-border-color: #2A2A2A;
            --fc-button-text-color: #e2e8f0;
            --fc-button-bg-color: #1A1A1A;
            --fc-button-border-color: #333;
            --fc-button-hover-bg-color: #222;
            --fc-button-hover-border-color: #444;
            --fc-page-bg-color: transparent;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 800;
        }

        .fc-event {
            cursor: pointer;
            border: none !important;
            border-radius: 6px;
            padding: 2px 4px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        
        .fc-event:hover {
            transform: scale(1.02);
            filter: brightness(1.1);
        }
    </style>

    <div class="relative px-8 pt-4 pb-8 space-y-8 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div>
                <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2 transition-colors">Jadwal Saya</h2>
                <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-gray-400 transition-colors">
                    <i class="ph ph-calendar-blank"></i>
                    <span>Sistem Kalender Terpadu FullCalendar</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-4">
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 flex items-center gap-4 shadow-sm dark:shadow-none min-w-[220px] transition-colors">
                    <div class="w-12 h-12 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary flex items-center justify-center border border-teal-100 dark:border-transparent transition-colors">
                        <i class="ph ph-clock text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase">Jam Terpakai</p>
                        <p class="font-heading text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $hoursUsed }} <span class="text-sm font-normal text-slate-500 dark:text-gray-400">Jam</span></p>
                    </div>
                </div>
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 flex items-center gap-4 shadow-sm dark:shadow-none min-w-[220px] transition-colors">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-transparent transition-colors">
                        <i class="ph ph-shield-check text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase">Skor Kepatuhan</p>
                        <p class="font-heading text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ number_format($complianceScore, 1) }} <span class="text-sm font-normal text-slate-500 dark:text-gray-400">/ 5.0</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center bg-white dark:bg-[#151515] p-4 rounded-2xl border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none gap-4 transition-colors">
            
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-xs font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mr-2">Filter:</span>
                
                <div class="flex items-center gap-2 px-3 py-1.5 rounded-full border border-slate-200 dark:border-[#2A2A2A] bg-slate-50 dark:bg-[#1A1A1A] text-xs font-bold text-slate-600 dark:text-gray-400">
                    <span class="w-2.5 h-2.5 rounded-full border border-dashed border-slate-400"></span> 
                    Kosong = Tersedia
                </div>

                <button type="button" class="filter-toggle active flex items-center gap-2 px-3 py-1.5 rounded-full border transition-all text-xs font-bold border-teal-200 bg-teal-50 text-teal-700 dark:border-teal-900/50 dark:bg-[#102A24] dark:text-teal-400" data-status="booked">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#14b8a6]"></span> Dipesan (Booked)
                </button>

                <button type="button" class="filter-toggle active flex items-center gap-2 px-3 py-1.5 rounded-full border transition-all text-xs font-bold border-blue-200 bg-blue-50 text-blue-700 dark:border-blue-900/50 dark:bg-[#101E28] dark:text-blue-400" data-status="pending">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#3b82f6]"></span> Tertunda (Pending)
                </button>

                <button type="button" class="filter-toggle active flex items-center gap-2 px-3 py-1.5 rounded-full border transition-all text-xs font-bold border-red-200 bg-red-50 text-red-700 dark:border-red-900/50 dark:bg-[#2A1515] dark:text-red-400" data-status="locked">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#ef4444]"></span> Terkunci
                </button>
            </div>

            <div class="flex gap-3 w-full md:w-auto">
                <a href="{{ route('booking') }}" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-kinetic-primary hover:bg-teal-600 dark:hover:bg-kinetic-secondary text-white dark:text-slate-900 rounded-xl text-sm font-bold shadow-[0_0_15px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-0.5">
                    <i class="ph-bold ph-plus text-lg"></i> Booking Baru
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-[#151515] rounded-3xl p-6 border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none transition-colors">
            <div id="calendar" class="text-slate-800 dark:text-slate-200"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-4">
            {{-- REKOMENDASI --}}
            <div class="lg:col-span-2 bg-white dark:bg-[#151515] rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none flex flex-col transition-colors">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-heading font-bold flex items-center gap-2 text-slate-900 dark:text-white transition-colors">
                        <i class="ph-fill ph-sparkle text-kinetic-primary text-lg"></i> Rekomendasi Slot Hari Ini
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php
                        $recommendedRooms = \App\Models\Room::take(2)->get();
                        $recTimes = [['start' => '15:00', 'end' => '17:00'], ['start' => '08:30', 'end' => '10:00']];
                        $todayStr = now()->format('Y-m-d');
                    @endphp

                    @foreach($recommendedRooms as $index => $room)
                        @if(isset($recTimes[$index]))
                        <a href="{{ route('booking', ['room_id' => $room->id, 'date' => $todayStr, 'start_time' => $recTimes[$index]['start']]) }}" class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 flex justify-between items-center group cursor-pointer hover:border-teal-400 dark:hover:border-kinetic-primary transition-colors">
                            <div>
                                <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mb-1">{{ $room->room_name }}</p>
                                <p class="font-bold text-sm text-slate-900 dark:text-white mb-3 transition-colors">{{ $recTimes[$index]['start'] }} - {{ $recTimes[$index]['end'] }}</p>
                                <span class="px-2.5 py-1 bg-teal-100/50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary text-[10px] font-bold rounded uppercase tracking-wider">Tersedia</span>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-white dark:bg-[#222] flex items-center justify-center border border-slate-200 dark:border-[#333] group-hover:bg-teal-50 dark:group-hover:bg-kinetic-primary/10 transition-colors">
                                <i class="ph ph-arrow-right text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                            </div>
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- KAPASITAS MINGGUAN --}}
            <div class="bg-white dark:bg-[#151515] rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none flex flex-col justify-center relative overflow-hidden transition-colors">
                <div class="absolute -right-10 -bottom-10 opacity-5 dark:opacity-10 pointer-events-none">
                    <i class="ph-fill ph-chart-bar text-9xl text-slate-900 dark:text-white"></i>
                </div>
                
                <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mb-3 relative z-10">Kapasitas Mingguan</p>
                <div class="flex items-end gap-3 mb-6 relative z-10">
                    <h2 class="font-heading text-6xl font-extrabold text-slate-900 dark:text-white transition-colors">84<span class="text-3xl text-slate-400">%</span></h2>
                    <span class="text-xs font-bold text-teal-600 dark:text-kinetic-primary mb-2.5 flex items-center gap-1 bg-teal-50 dark:bg-kinetic-primary/10 px-2 py-1 rounded-md">
                        <i class="ph-bold ph-arrow-up text-[10px]"></i> 12% dari bln lalu
                    </span>
                </div>
                
                <div class="h-2.5 w-full bg-slate-100 dark:bg-[#2A2A2A] rounded-full overflow-hidden relative z-10 transition-colors">
                    <div class="h-full bg-gradient-to-r from-kinetic-primary to-kinetic-secondary rounded-full shadow-[0_0_10px_rgba(20,184,166,0.5)]" style="width: 84%"></div>
                </div>
            </div>
            
        </div>

    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawBookings = @json($allBookings);

        const calendarEvents = rawBookings.map(b => {
            const isLocked = b.event_name && b.event_name.toUpperCase().includes('[MAINTENANCE HARD-LOCK]');
            const statusLower = b.status ? b.status.toLowerCase() : '';
            
            let eventStatus = 'pending';
            let bgColor = '#3b82f6'; // Biru (Pending)
            let textColor = '#ffffff';

            if (isLocked) {
                eventStatus = 'locked';
                bgColor = '#ef4444'; // Merah
            } else if (statusLower === 'approved') {
                eventStatus = 'booked';
                bgColor = '#14b8a6'; // Teal
            }

            const datePart = b.booking_date.split('T')[0].split(' ')[0];

            return {
                id: b.id,
                title: `${b.room ? b.room.room_name : 'Ruangan'} - ${isLocked ? 'Locked by IT' : b.event_name}`,
                start: `${datePart}T${b.start_time}`,
                end: `${datePart}T${b.end_time}`,
                backgroundColor: bgColor,
                borderColor: bgColor,
                textColor: textColor,
                extendedProps: {
                    statusCategory: eventStatus,
                    roomName: b.room ? b.room.room_name : 'Ruangan',
                    originalStatus: b.status
                }
            };
        });

        let activeFilters = {
            booked: true,
            pending: true,
            locked: true
        };

        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            // -- KONFIGURASI BAHASA INDONESIA --
            locale: 'id', 
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu',
                day: 'Hari',
                list: 'Agenda'
            },
            
            // -- KONFIGURASI FORMAT 24 JAM --
            slotLabelFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false // Mematikan AM/PM, mengubah jadi 07:00, 08:00, dst.
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },

            initialView: 'timeGridWeek',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            slotMinTime: '07:00:00',
            slotMaxTime: '22:00:00',
            allDaySlot: false,
            expandRows: true,
            height: 700,
            
            events: function(info, successCallback, failureCallback) {
                const filteredEvents = calendarEvents.filter(e => activeFilters[e.extendedProps.statusCategory]);
                successCallback(filteredEvents);
            },
            
            eventClick: function(info) {
                const props = info.event.extendedProps;
                alert(`Detail Jadwal:\n\nRuangan: ${props.roomName}\nKegiatan: ${info.event.title.split(' - ')[1]}\nStatus: ${props.originalStatus}\nWaktu: ${info.event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})} - ${info.event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}`);
            }
        });
        
        calendar.render();

        const filterButtons = document.querySelectorAll('.filter-toggle');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const statusType = this.getAttribute('data-status');
                activeFilters[statusType] = !activeFilters[statusType];
                
                if (activeFilters[statusType]) {
                    this.classList.remove('opacity-50', 'bg-transparent');
                } else {
                    this.classList.add('opacity-50', 'bg-transparent');
                }

                calendar.refetchEvents();
            });
        });
    });
</script>