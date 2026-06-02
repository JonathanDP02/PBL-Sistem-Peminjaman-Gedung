<x-app-layout title="Jadwal Saya">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Kustomisasi Tema FullCalendar agar menyatu dengan desain UI kita (termasuk Dark Mode) */
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

        /* Modernize Toolbar Buttons */
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

        .fc .fc-button-primary:not(:disabled):active, 
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            box-shadow: 0 4px 12px rgba(20, 184, 166, 0.3);
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.025em;
        }

        .dark .fc .fc-toolbar-title {
            color: #f8fafc;
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

        /* List View Overrides */
        .fc-list {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid var(--fc-border-color) !important;
        }

        .fc-list-day-cushion {
            background-color: #f8fafc !important;
            padding: 12px 20px !important;
        }

        .dark .fc-list-day-cushion {
            background-color: #1A1A1A !important;
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

        <div class="bg-white dark:bg-[#151515] rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none flex flex-col transition-colors pb-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-heading font-bold flex items-center gap-2 text-slate-900 dark:text-white transition-colors">
                    <i class="ph-fill ph-sparkle text-kinetic-primary text-lg"></i> Rekomendasi Slot Hari Ini
                </h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $recommendedRooms = \App\Models\Room::take(4)->get();
                    $recTimes = [
                        ['start' => '15:00', 'end' => '17:00'], 
                        ['start' => '08:30', 'end' => '10:00'],
                        ['start' => '10:30', 'end' => '12:00'],
                        ['start' => '13:00', 'end' => '15:00']
                    ];
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
                listWeek: 'Agenda Mingguan',
                listDay: 'Agenda Harian'
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

            // -- TANGANI INITIAL DATE DARI URL --
            initialDate: (function() {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('date') || new Date();
            })(),

            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listWeek,listDay'
            },
            height: 'auto',
            contentHeight: 'auto',
            fixedWeekCount: false,
            
            events: function(info, successCallback, failureCallback) {
                const filteredEvents = calendarEvents.filter(e => activeFilters[e.extendedProps.statusCategory]);
                successCallback(filteredEvents);
            },
            
            eventClick: function(info) {
                const props = info.event.extendedProps;
                const timeStr = `${info.event.start.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})} - ${info.event.end.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})}`;
                const dateStr = info.event.start.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                
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

</x-app-layout>