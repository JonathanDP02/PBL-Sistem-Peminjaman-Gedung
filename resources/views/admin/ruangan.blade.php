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
                    <a href="{{ url('/cari-ruangan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary font-medium text-sm transition border border-teal-100 dark:border-kinetic-primary/20">
                        <i class="ph-fill ph-magnifying-glass text-lg"></i> Cari Ruangan
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

<div class="flex-1 overflow-y-auto p-10 space-y-10">
            
            <div>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-teal-50 dark:bg-kinetic-primary/10 border border-teal-200 dark:border-kinetic-primary/20 rounded-full text-[10px] font-bold text-teal-700 dark:text-kinetic-secondary tracking-widest uppercase mb-4 transition-colors">
                    <span class="w-1.5 h-1.5 bg-kinetic-primary rounded-full"></span> Sistem Reservasi Terpadu
                </span>
                <h2 class="font-heading text-4xl md:text-5xl font-extrabold text-slate-900 dark:text-white mb-4 transition-colors">
                    Reservasi <span class="text-kinetic-primary">Tanpa Batas</span>
                </h2>
                <p class="text-slate-500 dark:text-gray-400 text-sm max-w-2xl leading-relaxed transition-colors">
                    Wujudkan kegiatan akademik dan organisasi Anda dengan akses mudah ke seluruh ruang pertemuan dan auditorium universitas.
                </p>
            </div>

            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none rounded-3xl p-8 lg:p-10 transition-colors duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    
                    <div class="space-y-8">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Pilih Ruangan</label>
                            <div class="relative">
                                <i class="ph ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                <input type="text" placeholder="Cari nama ruangan atau nomor gedung..." class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Tanggal Penggunaan</label>
                                <div class="relative">
                                    <i class="ph ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                    <input type="date" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Waktu Pelaksanaan</label>
                                <div class="relative">
                                    <i class="ph ph-clock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 dark:text-gray-500 text-lg"></i>
                                    <input type="time" class="w-full bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-12 pr-4 py-3.5 text-sm text-slate-900 dark:text-gray-400 focus:outline-none focus:border-kinetic-primary transition-colors [color-scheme:light] dark:[color-scheme:dark]">
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Alur Prosedur (SOP)</label>
                            <div class="grid grid-cols-2 gap-4">
                                <button class="bg-teal-50 dark:bg-[#1A1A1A] border border-kinetic-primary rounded-xl p-5 text-left relative group transition-colors">
                                    <i class="ph-fill ph-check-circle absolute top-4 right-4 text-kinetic-primary text-xl"></i>
                                    <p class="font-bold text-slate-900 dark:text-white text-sm mb-1">SOP A</p>
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500">Kegiatan Internal Fakultas & Lab</p>
                                </button>
                                <button class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] hover:border-slate-400 dark:hover:border-gray-500 rounded-xl p-5 text-left transition-colors">
                                    <p class="font-bold text-slate-600 dark:text-gray-300 text-sm mb-1">SOP B</p>
                                    <p class="text-[10px] text-slate-500 dark:text-gray-500">Kegiatan Organisasi & Eksternal</p>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col h-full justify-between">
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-400 tracking-widest uppercase mb-3">Lampiran Dokumen</label>
                            <div class="space-y-3">
                                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-slate-400 dark:text-gray-400">
                                            <i class="ph ph-file-text text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Izin Dekanat</p>
                                            <p class="text-[10px] text-slate-500 dark:text-gray-500">Format PDF, Maks 5MB</p>
                                        </div>
                                    </div>
                                    <i class="ph ph-cloud-arrow-up text-xl text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                                </div>
                                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-slate-400 dark:text-gray-400">
                                            <i class="ph ph-clipboard-text text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">Proposal Kegiatan</p>
                                            <p class="text-[10px] text-slate-500 dark:text-gray-500">Wajib untuk SOP B</p>
                                        </div>
                                    </div>
                                    <i class="ph ph-cloud-arrow-up text-xl text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                                </div>
                                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between group hover:border-kinetic-primary/50 transition-colors cursor-pointer">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-slate-400 dark:text-gray-400">
                                            <i class="ph ph-identification-card text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-slate-900 dark:text-white mb-0.5">KTM Ketua Pelaksana</p>
                                            <p class="text-[10px] text-slate-500 dark:text-gray-500">Scan berwarna (JPG/PNG)</p>
                                        </div>
                                    </div>
                                    <i class="ph ph-cloud-arrow-up text-xl text-slate-400 dark:text-gray-500 group-hover:text-kinetic-primary transition-colors"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <button class="w-full bg-gradient-to-r from-kinetic-primary to-kinetic-secondary hover:from-teal-400 hover:to-cyan-400 text-slate-900 font-bold py-4 rounded-xl shadow-[0_0_20px_rgba(20,184,166,0.3)] transition transform hover:-translate-y-1">
                                Pesan Sekarang
                            </button>
                            <p class="text-[10px] text-center text-slate-500 dark:text-gray-500 mt-4">*Proses verifikasi dokumen memakan waktu ±24 jam hari kerja.</p>
                        </div>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 pb-10">
                <div class="bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                    <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-teal-500/10 text-kinetic-primary flex items-center justify-center shrink-0 border border-teal-100 dark:border-transparent">
                        <i class="ph-bold ph-shield-check text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Approval Cepat</h4>
                        <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Sistem otomatis mendeteksi ketersediaan ruang secara real-time untuk mempercepat birokrasi.</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                    <div class="w-10 h-10 rounded-full bg-cyan-50 dark:bg-cyan-500/10 text-kinetic-tertiary flex items-center justify-center shrink-0 border border-cyan-100 dark:border-transparent">
                        <i class="ph-bold ph-git-branch text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Pelacakan Alur</h4>
                        <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Pantau status pengajuan Anda dari tahap admin hingga persetujuan rektorat dalam satu dashboard.</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none rounded-2xl p-6 flex gap-4 transition-colors duration-300">
                    <div class="w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-500/10 text-blue-500 dark:text-blue-400 flex items-center justify-center shrink-0 border border-blue-100 dark:border-transparent">
                        <i class="ph-bold ph-chart-bar text-xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Log Digital</h4>
                        <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Seluruh riwayat penggunaan dan lampiran tersimpan aman untuk kebutuhan laporan pertanggungjawaban.</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border shadow-sm dark:shadow-none rounded-2xl p-6 transition-colors duration-300">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-2 h-2 bg-kinetic-primary rounded-full animate-pulse"></span>
                        <span class="text-[10px] font-bold text-kinetic-primary tracking-widest uppercase">Live Insight</span>
                    </div>
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">12 Ruangan Tersedia</h4>
                    <p class="text-[10px] text-slate-500 dark:text-gray-500 leading-relaxed">Gedung Fakultas Teknik memiliki slot terbanyak saat ini.</p>
                </div>
            </div>

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