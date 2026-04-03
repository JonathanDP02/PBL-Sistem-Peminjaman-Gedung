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

    <!-- Sidebar -->
    <aside class="w-64 bg-white dark:bg-kinetic-bg border-r border-slate-200 dark:border-kinetic-border flex flex-col justify-between shrink-0 z-20 transition-colors duration-300">
        <div>
            <div class="p-6 h-20 flex items-center">
                <h1 class="font-heading font-extrabold text-2xl tracking-tight text-slate-900 dark:text-white">Space<span class="text-kinetic-primary">.in</span></h1>
            </div>
            <div class="px-6 mb-4">
                <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-4">Enterprise Booking</p>
                <nav class="space-y-1">
                    <a href="{{ url('/admin/dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary font-medium text-sm transition border border-teal-100 dark:border-kinetic-primary/20">
                        <i class="ph-fill ph-squares-four text-lg"></i> Dashboard
                    </a>
                    <a href="{{ url('/cari-ruangan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface transition font-medium text-sm">
                        <i class="ph ph-magnifying-glass text-lg"></i> Cari Ruangan
                    </a>
                    <a href="{{ url('/jadwal-saya') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface transition font-medium text-sm">
                        <i class="ph ph-calendar-blank text-lg"></i> Jadwal Saya
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

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative transition-colors duration-300">
        <!-- Ambient Glow (Visible mostly in dark mode, subtle in light mode) -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-100/50 dark:bg-kinetic-primary/5 rounded-full blur-[100px] pointer-events-none transition-colors duration-300"></div>

        <!-- Top Header -->
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

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-8 space-y-8 relative z-10">
            
            <!-- Hero / Welcome Banner -->
            <div class="relative rounded-2xl bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border p-8 overflow-hidden glow-primary flex flex-col md:flex-row justify-between items-start md:items-center gap-6 transition-colors duration-300">
                <div class="absolute right-0 top-0 bottom-0 w-1/2 bg-gradient-to-l from-teal-50 dark:from-kinetic-primary/10 to-transparent pointer-events-none transition-colors duration-300"></div>
                
                <div class="relative z-10">
                    <span class="px-2 py-1 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-secondary text-[10px] font-bold rounded uppercase tracking-wider border border-teal-200 dark:border-kinetic-primary/20 mb-3 inline-block">Selamat Datang Kembali</span>
                    <h2 class="font-heading text-3xl font-extrabold mb-1 text-slate-900 dark:text-white">Ruang Kerja <span class="text-gradient">Akademis</span></h2>
                    <p class="text-sm text-slate-500 dark:text-gray-400 max-w-md">Pantau status pengajuan ruanganmu dan pastikan tidak ada dokumen yang tertinggal.</p>
                </div>
                
                <button class="relative z-10 bg-kinetic-primary hover:bg-teal-600 dark:hover:bg-kinetic-secondary text-white dark:text-kinetic-bg font-heading font-bold px-6 py-3 rounded-xl transition flex items-center gap-2 shadow-[0_0_15px_rgba(20,184,166,0.2)] dark:shadow-[0_0_15px_rgba(20,184,166,0.3)]">
                    <i class="ph-bold ph-plus"></i> Booking Baru
                </button>
            </div>

            <!-- Real Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Hard Lock / Approved -->
                <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-6 relative overflow-hidden group hover:border-teal-400 dark:hover:border-kinetic-primary/50 transition">
                    <div class="absolute right-6 top-6 text-teal-100 dark:text-kinetic-primary/20 group-hover:text-teal-200 dark:group-hover:text-kinetic-primary/40 transition">
                        <i class="ph-fill ph-check-circle text-5xl"></i>
                    </div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-4">Disetujui</p>
                    <p class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">12</p>
                    <p class="text-xs text-teal-600 dark:text-kinetic-primary font-medium">Jadwal Hard-Lock</p>
                </div>

                <!-- Soft Lock / Pending -->
                <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-6 relative overflow-hidden group hover:border-cyan-400 dark:hover:border-kinetic-tertiary/50 transition">
                    <div class="absolute right-6 top-6 text-cyan-100 dark:text-kinetic-tertiary/20 group-hover:text-cyan-200 dark:group-hover:text-kinetic-tertiary/40 transition">
                        <i class="ph-fill ph-clock-countdown text-5xl"></i>
                    </div>
                    <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-4">Menunggu</p>
                    <p class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1">03</p>
                    <p class="text-xs text-cyan-600 dark:text-kinetic-tertiary font-medium">Verifikasi Birokrasi</p>
                </div>

                <!-- Revising / Rejected -->
                <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-red-200 dark:border-red-500/20 rounded-2xl p-6 relative overflow-hidden group hover:border-red-400 dark:hover:border-red-500/50 transition">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-50 dark:from-red-500/5 to-transparent transition-colors duration-300"></div>
                    <div class="absolute right-6 top-6 text-red-100 dark:text-red-500/20 group-hover:text-red-200 dark:group-hover:text-red-500/40 transition">
                        <i class="ph-fill ph-warning-circle text-5xl"></i>
                    </div>
                    <p class="text-[10px] font-bold tracking-widest text-red-500 dark:text-red-400 uppercase mb-4 relative z-10">Urgent</p>
                    <p class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1 relative z-10">01</p>
                    <p class="text-xs text-red-600 dark:text-red-400 font-medium relative z-10">Perlu Revisi Dokumen</p>
                </div>
            </div>

            <!-- Split Layout: Bookings & Sidebar -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Left: Booking Terkini (2/3) -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="flex justify-between items-end">
                        <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white">Booking Terkini</h3>
                        <a href="#" class="text-xs font-medium text-teal-600 dark:text-kinetic-primary hover:text-teal-700 dark:hover:text-kinetic-secondary transition">Lihat Semua</a>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Item 1: Approved -->
                        <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-5 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-kinetic-surface transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border flex items-center justify-center">
                                    <i class="ph-fill ph-buildings text-xl text-teal-600 dark:text-kinetic-primary"></i>
                                </div>
                                <div>
                                    <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">Lab Robotika Terpadu</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Sesi Riset Mandiri • 14:00 - 16:00 WIB</p>
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-6">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Tanggal</p>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">Besok, 24 Okt</p>
                                </div>
                                <span class="px-3 py-1 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary border border-teal-200 dark:border-kinetic-primary/20 rounded-md text-[10px] font-bold tracking-wider uppercase">
                                    Approved
                                </span>
                            </div>
                        </div>

                        <!-- Item 2: Pending -->
                        <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-5 flex items-center justify-between hover:bg-slate-50 dark:hover:bg-kinetic-surface transition">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border flex items-center justify-center">
                                    <i class="ph-fill ph-users-three text-xl text-cyan-600 dark:text-kinetic-tertiary"></i>
                                </div>
                                <div>
                                    <h4 class="font-heading font-bold text-sm text-slate-900 dark:text-white">Ruang Diskusi 04</h4>
                                    <p class="text-xs text-slate-500 dark:text-gray-400 mt-0.5">Kerja Kelompok Jarkom • 09:00 - 11:00 WIB</p>
                                </div>
                            </div>
                            <div class="text-right flex items-center gap-6">
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-wider">Tanggal</p>
                                    <p class="text-sm font-semibold text-slate-600 dark:text-gray-300">26 Okt 2026</p>
                                </div>
                                <span class="px-3 py-1 bg-slate-100 dark:bg-gray-800 text-slate-600 dark:text-gray-400 border border-slate-200 dark:border-gray-700 rounded-md text-[10px] font-bold tracking-wider uppercase">
                                    Pending
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Notifications & Calendar (1/3) -->
                <div class="space-y-6">
                    <!-- Mini Calendar -->
                    <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="font-heading font-bold text-slate-900 dark:text-white">Oktober 2026</h3>
                            <div class="flex gap-2">
                                <button class="text-slate-400 dark:text-gray-500 hover:text-slate-900 dark:hover:text-white transition"><i class="ph-bold ph-caret-left"></i></button>
                                <button class="text-slate-400 dark:text-gray-500 hover:text-slate-900 dark:hover:text-white transition"><i class="ph-bold ph-caret-right"></i></button>
                            </div>
                        </div>
                        <div class="grid grid-cols-7 gap-1 text-center text-xs">
                            <div class="text-slate-500 dark:text-gray-500 font-medium mb-2">M</div>
                            <div class="text-slate-500 dark:text-gray-500 font-medium mb-2">S</div>
                            <div class="text-slate-500 dark:text-gray-500 font-medium mb-2">S</div>
                            <div class="text-slate-500 dark:text-gray-500 font-medium mb-2">R</div>
                            <div class="text-slate-500 dark:text-gray-500 font-medium mb-2">K</div>
                            <div class="text-slate-500 dark:text-gray-500 font-medium mb-2">J</div>
                            <div class="text-slate-500 dark:text-gray-500 font-medium mb-2">S</div>
                            
                            <!-- Dates -->
                            <div class="py-2 text-slate-400 dark:text-gray-500">20</div>
                            <div class="py-2 text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white cursor-pointer transition">21</div>
                            <div class="py-2 text-slate-600 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white cursor-pointer transition">22</div>
                            <div class="py-2 bg-teal-50 dark:bg-kinetic-primary/20 text-teal-700 dark:text-kinetic-primary font-bold rounded-lg border border-teal-200 dark:border-kinetic-primary/30 relative">
                                23
                                <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-teal-600 dark:bg-kinetic-primary rounded-full"></span>
                            </div>
                            <div class="py-2 text-slate-900 dark:text-white font-medium cursor-pointer relative">
                                24
                                <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-cyan-500 dark:bg-kinetic-tertiary rounded-full"></span>
                            </div>
                            <div class="py-2 text-slate-900 dark:text-white font-medium cursor-pointer">25</div>
                            <div class="py-2 text-slate-900 dark:text-white font-medium cursor-pointer relative">
                                26
                                <span class="absolute bottom-1 left-1/2 -translate-x-1/2 w-1 h-1 bg-slate-400 dark:bg-gray-500 rounded-full"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Notifications -->
                    <div class="bg-white dark:bg-kinetic-card shadow-sm dark:shadow-none border border-slate-200 dark:border-kinetic-border rounded-2xl p-6">
                        <h3 class="font-heading font-bold mb-6 text-slate-900 dark:text-white">Notifikasi</h3>
                        <div class="space-y-5">
                            <div class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-teal-50 dark:bg-kinetic-primary/10 text-teal-600 dark:text-kinetic-primary flex items-center justify-center shrink-0 border border-teal-100 dark:border-kinetic-primary/20">
                                    <i class="ph-bold ph-check"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white mb-0.5">Booking Approved</p>
                                    <p class="text-xs text-slate-500 dark:text-gray-400 line-clamp-2">Lab Robotika telah disetujui oleh Wadir 2 untuk besok.</p>
                                    <p class="text-[10px] text-slate-400 dark:text-gray-500 mt-1">2 jam yang lalu</p>
                                </div>
                            </div>
                            
                            <div class="flex gap-4">
                                <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center shrink-0 border border-red-100 dark:border-red-500/20">
                                    <i class="ph-bold ph-warning"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white mb-0.5">Butuh Revisi</p>
                                    <p class="text-xs text-slate-500 dark:text-gray-400 line-clamp-2">Lengkapi dokumen Proposal format baru untuk Lab AI.</p>
                                    <p class="text-[10px] text-slate-400 dark:text-gray-500 mt-1">5 jam yang lalu</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <!-- Footer -->
            <footer class="pt-8 pb-4 border-t border-slate-200 dark:border-kinetic-border/50 flex justify-between items-center text-xs text-slate-500 dark:text-gray-500">
                <p><span class="font-heading font-bold text-slate-900 dark:text-white">Space.in</span> © 2026 Enterprise Booking System</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-slate-900 dark:hover:text-white transition">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-slate-900 dark:hover:text-white transition">Pusat Bantuan</a>
                    <a href="#" class="hover:text-slate-900 dark:hover:text-white transition">Privasi</a>
                </div>
            </footer>
        </div>
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