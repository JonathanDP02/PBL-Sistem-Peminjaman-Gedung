<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Space.in | Kinetic Dashboard</title>
    
    <!-- Fonts: Plus Jakarta Sans (Headings) & Inter (Body) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        heading: ['Plus Jakarta Sans', 'sans-serif'],
                    },
                    colors: {
                        kinetic: {
                            primary: '#14B8A6',
                            secondary: '#10ECE8',
                            tertiary: '#0DBAF9',
                            bg: '#0A0A0A',
                            surface: '#121212',
                            border: '#1E1E1E',
                            card: '#151515'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Base handling managed by Tailwind now */
        
        /* Custom Scrollbar - Adaptive */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        
        /* Light mode scrollbar */
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        /* Dark mode scrollbar */
        .dark ::-webkit-scrollbar-thumb { background: #1E1E1E; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #2A2A2A; }
        
        /* Kinetic Gradients & Glows */
        .text-gradient {
            background: linear-gradient(to right, #14B8A6, #0DBAF9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .glow-primary {
            box-shadow: 0 0 20px rgba(20, 184, 166, 0.15);
        }
        .dark .glow-primary {
            box-shadow: 0 0 30px rgba(20, 184, 166, 0.2);
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden antialiased bg-slate-50 dark:bg-kinetic-bg text-slate-900 dark:text-white transition-colors duration-300">

    <aside class="w-64 bg-white dark:bg-kinetic-bg border-r border-slate-200 dark:border-kinetic-border flex flex-col justify-between shrink-0 z-20">
        <div>
            <div class="p-6 h-20 flex items-center">
                <h1 class="font-heading font-extrabold text-2xl tracking-tight text-slate-900 dark:text-white">Space<span class="text-kinetic-primary">.in</span></h1>
            </div>
            <div class="px-6 mb-4">
                <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-4">Enterprise Booking</p>
                <nav class="space-y-1">
                    <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface transition font-medium text-sm">
                        <i class="ph ph-squares-four text-lg"></i> Dashboard
                    </a>
                    <a href="{{ url('/cari-ruangan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface transition font-medium text-sm">
                        <i class="ph ph-magnifying-glass text-lg"></i> Cari Ruangan
                    </a>
                    <a href="{{ url('/jadwal-saya') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary font-medium text-sm transition border border-teal-100 dark:border-kinetic-primary/20">
                        <i class="ph-fill ph-magnifying-glass text-lg"></i> Jadwal Saya
                    </a>
                    <a href="{{ url('/riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface transition font-medium text-sm">
                        <i class="ph ph-clock-counter-clockwise text-lg"></i> Riwayat
                    </a>
                </nav>
            </div>
        </div>
            
        <div class="p-6 border-t border-slate-200 dark:border-kinetic-border">
            <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface transition font-medium text-sm mb-4">
                <i class="ph ph-gear text-lg"></i> Pengaturan
            </a>
            
            <!-- THEME TOGGLE BUTTON -->
            <button id="themeToggleBtn" class="w-full flex items-center justify-between px-3 py-2.5 mb-4 rounded-lg bg-slate-100 dark:bg-kinetic-surface text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white transition border border-slate-200 dark:border-kinetic-border">
                <span class="text-sm font-medium flex items-center gap-2">
                    <i class="ph ph-moon dark:hidden"></i>
                    <i class="ph ph-sun hidden dark:block"></i>
                    <span class="dark:hidden">Mode Gelap</span>
                    <span class="hidden dark:block">Mode Terang</span>
                </span>
                <!-- Custom Switch UI -->
                <div class="w-8 h-4 bg-slate-300 dark:bg-kinetic-border rounded-full relative transition-colors">
                    <div class="absolute left-0.5 top-0.5 w-3 h-3 bg-white dark:bg-kinetic-primary rounded-full transition-transform dark:translate-x-4"></div>
                </div>
            </button>

            <div class="flex items-center gap-3 mt-4">
                <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border flex items-center justify-center overflow-hidden">
                    <i class="ph-fill ph-user text-slate-400 dark:text-gray-400"></i>
                </div>
                <div>
                    <p class="text-sm font-heading font-bold text-slate-900 dark:text-white">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500">INFORMATIKA '26</p>
                </div>
            </div>
            </div>
    </aside>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative">
        
       <header class="h-20 px-8 flex items-center justify-between border-b border-slate-200 dark:border-kinetic-border/50 shrink-0 z-10 bg-white/50 dark:bg-transparent backdrop-blur-sm">
            <div class="relative w-96">
                <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500"></i>
                <input type="text" placeholder="Cari ruangan atau departemen..." class="w-full bg-slate-100 dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border rounded-full pl-11 pr-4 py-2.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-500 focus:outline-none focus:border-kinetic-primary focus:ring-1 focus:ring-kinetic-primary transition">
            </div>
            <div class="flex items-center gap-4">
                <button class="relative p-2 text-slate-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white transition">
                    <i class="ph ph-bell text-xl"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-kinetic-primary rounded-full"></span>
                </button>
                <button class="p-2 text-slate-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white transition">
                    <i class="ph ph-question text-xl"></i>
                </button>
                <div class="h-6 w-px bg-slate-200 dark:bg-kinetic-border mx-2"></div>
                <button class="flex items-center gap-2 text-xs font-bold tracking-wider text-slate-500 dark:text-gray-400 uppercase hover:text-slate-900 dark:hover:text-white transition">
                    {{ Auth::user()->role->name }} <i class="ph-fill ph-caret-down text-[10px]"></i>
                </button>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" style="padding: 10px 20px; background-color: #e74c3c; color: white; border: none; border-radius: 5px; cursor: pointer;">
                        Logout
                    </button>
                </form>
            </div>
        </header>
    <body>
        <div class="flex-1 overflow-y-auto p-8 lg:p-10 space-y-8">
    
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <div>
            <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2 transition-colors">Jadwal Saya</h2>
            <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-gray-400 transition-colors">
                <i class="ph ph-calendar-blank"></i>
                <span>Oktober 2026 • Minggu ke-3</span>
            </div>
        </div>
        <div class="flex flex-wrap gap-4">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 flex items-center gap-4 shadow-sm dark:shadow-none min-w-[220px] transition-colors">
                <div class="w-12 h-12 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary flex items-center justify-center border border-teal-100 dark:border-transparent transition-colors">
                    <i class="ph ph-clock text-2xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase">Jam Terpakai</p>
                    <p class="font-heading text-2xl font-bold text-slate-900 dark:text-white transition-colors">12 <span class="text-sm font-normal text-slate-500 dark:text-gray-400">Jam</span></p>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border rounded-2xl p-4 flex items-center gap-4 shadow-sm dark:shadow-none min-w-[220px] transition-colors">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-100 dark:border-transparent transition-colors">
                    <i class="ph ph-shield-check text-2xl"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase">Skor Kepatuhan</p>
                    <p class="font-heading text-2xl font-bold text-slate-900 dark:text-white transition-colors">4.8 <span class="text-sm font-normal text-slate-500 dark:text-gray-400">/ 5.0</span></p>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center bg-white dark:bg-[#151515] p-4 rounded-2xl border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none gap-4 transition-colors">
        <div class="flex flex-wrap items-center gap-6 px-2">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                <span class="w-2.5 h-2.5 rounded-full bg-kinetic-primary"></span> Tersedia
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                <span class="w-2.5 h-2.5 rounded-full bg-blue-500"></span> Tertunda
            </div>
            <div class="flex items-center gap-2 text-xs font-bold text-slate-600 dark:text-gray-300">
                <span class="w-2.5 h-2.5 rounded-full bg-red-500"></span> Terkunci
            </div>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl text-sm font-bold text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-[#222] transition-colors">
                <i class="ph ph-faders text-lg"></i> Filter
            </button>
            <button class="flex-1 md:flex-none flex items-center justify-center gap-2 px-5 py-2.5 bg-kinetic-primary hover:bg-teal-600 dark:hover:bg-kinetic-secondary text-white dark:text-slate-900 rounded-xl text-sm font-bold shadow-[0_0_15px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-0.5">
                <i class="ph-bold ph-plus text-lg"></i> Booking Baru
            </button>
        </div>
    </div>

    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none overflow-hidden transition-colors">
<div class="flex-1 overflow-y-auto p-8 lg:p-10 space-y-8">
    
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
        <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white transition-colors">Jadwal Saya</h2>
        </div>

    <div class="bg-white dark:bg-[#151515] rounded-3xl border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none overflow-hidden transition-colors">
        
        <div class="overflow-x-auto custom-scrollbar">
            <div class="w-max">
                
                <div class="grid grid-cols-[100px_repeat(30,_180px)] border-b border-slate-100 dark:border-[#1E1E1E]">
                    <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] flex items-center justify-center bg-slate-50 dark:bg-[#111]">
                        <span class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">Waktu</span>
                    </div>
                    @for ($i = 1; $i <= 30; $i++)
                    <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] text-center transition-colors {{ $i == 17 ? 'bg-teal-50/50 dark:bg-[#1A1A1A]' : '' }}">
                        <p class="text-[10px] font-bold {{ $i == 17 ? 'text-kinetic-primary' : 'text-slate-400' }} tracking-widest uppercase mb-1">Okt</p>
                        <p class="text-xl font-heading font-bold {{ $i == 17 ? 'text-kinetic-primary' : 'text-slate-900 dark:text-white' }}">{{ $i }}</p>
                    </div>
                    @endfor
                </div>

                <div class="max-h-[480px] overflow-y-auto custom-scrollbar">
                    
                    @php
                        $startHour = 7;
                        $endHour = 22;
                    @endphp

                    @for ($hour = $startHour; $hour <= $endHour; $hour++)
                    <div class="grid grid-cols-[100px_repeat(30,_180px)] border-b border-slate-100 dark:border-[#1E1E1E] group">
                        
                        <div class="p-4 border-r border-slate-100 dark:border-[#1E1E1E] flex items-start justify-center bg-slate-50 dark:bg-[#111] sticky left-0 z-10">
                            <span class="text-xs font-bold text-slate-500 dark:text-gray-500">{{ sprintf('%02d:00', $hour) }}</span>
                        </div>

                        @for ($day = 1; $day <= 30; $day++)
                        <div class="p-2 border-r border-slate-100 dark:border-[#1E1E1E] min-h-[120px] transition-colors hover:bg-slate-50/50 dark:hover:bg-white/5">
                            
                            {{-- Contoh Logika Random untuk Demo --}}
                            @if($hour == 9 && $day == 17)
                                <div class="h-full bg-teal-50 dark:bg-[#102A24] border border-teal-200 dark:border-teal-900/50 rounded-xl p-3 shadow-sm relative">
                                    <span class="text-[9px] font-bold text-kinetic-primary uppercase tracking-wider block mb-1">Confirmed</span>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Aula Utama</h4>
                                </div>
                            @elseif($hour == 11 && $day == 19)
                                <div class="h-full bg-blue-50 dark:bg-[#101E28] border border-blue-200 dark:border-blue-900/50 rounded-xl p-3 shadow-sm">
                                    <span class="text-[9px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider block mb-1">Pending</span>
                                    <h4 class="text-xs font-bold text-slate-900 dark:text-white leading-tight">Lab Komputer</h4>
                                </div>
                            @else
                                <div class="h-full w-full border border-dashed border-slate-200 dark:border-white/10 rounded-xl flex items-center justify-center group/btn cursor-pointer hover:border-kinetic-primary/50 transition-all">
                                    <i class="ph ph-plus text-slate-300 dark:text-white/10 group-hover/btn:text-kinetic-primary text-xl"></i>
                                </div>
                            @endif

                        </div>
                        @endfor
                    </div>
                    @endfor

                </div> </div> </div> </div>

</div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-10">
        
        <div class="lg:col-span-2 bg-white dark:bg-[#151515] rounded-3xl p-6 md:p-8 border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none flex flex-col transition-colors">
            <div class="flex justify-between items-center mb-6">
                <h3 class="font-heading font-bold flex items-center gap-2 text-slate-900 dark:text-white transition-colors">
                    <i class="ph-fill ph-sparkle text-kinetic-primary text-lg"></i> Rekomendasi Slot Hari Ini
                </h3>
                <a href="#" class="text-xs font-bold text-teal-600 dark:text-kinetic-primary hover:underline transition-colors">Lihat Semua</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 flex justify-between items-center group cursor-pointer hover:border-teal-400 dark:hover:border-kinetic-primary transition-colors">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mb-1">Ruang Studio 01</p>
                        <p class="font-bold text-sm text-slate-900 dark:text-white mb-3 transition-colors">15:00 - 17:00</p>
                        <span class="px-2.5 py-1 bg-teal-100/50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary text-[10px] font-bold rounded uppercase tracking-wider">Tersedia</span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-[#222] flex items-center justify-center border border-slate-200 dark:border-[#333] group-hover:bg-teal-50 dark:group-hover:bg-kinetic-primary/10 transition-colors">
                        <i class="ph ph-arrow-right text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                    </div>
                </div>
                
                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 flex justify-between items-center group cursor-pointer hover:border-teal-400 dark:hover:border-kinetic-primary transition-colors">
                    <div>
                        <p class="text-[10px] font-bold text-slate-500 dark:text-gray-400 uppercase tracking-widest mb-1">Auditorium B</p>
                        <p class="font-bold text-sm text-slate-900 dark:text-white mb-3 transition-colors">18:30 - 20:00</p>
                        <span class="px-2.5 py-1 bg-teal-100/50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary text-[10px] font-bold rounded uppercase tracking-wider">Tersedia</span>
                    </div>
                    <div class="w-8 h-8 rounded-full bg-white dark:bg-[#222] flex items-center justify-center border border-slate-200 dark:border-[#333] group-hover:bg-teal-50 dark:group-hover:bg-kinetic-primary/10 transition-colors">
                        <i class="ph ph-arrow-right text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                    </div>
                </div>
            </div>
        </div>

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
    </body>


    </main>
    <script>
        // --- Theme Toggle Logic ---
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const html = document.documentElement;

        // On Load: Check system preference or localStorage
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            html.classList.add('dark');
        } else {
            html.classList.remove('dark');
        }

        // Click Event: Toggle and Save
        themeToggleBtn.addEventListener('click', () => {
            html.classList.toggle('dark');
            
            if (html.classList.contains('dark')) {
                localStorage.theme = 'dark';
            } else {
                localStorage.theme = 'light';
            }
        });
    </script>
</body>
</html>