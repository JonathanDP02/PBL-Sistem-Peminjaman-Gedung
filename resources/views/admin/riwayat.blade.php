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
                        <i class="ph ph-calendar-blank text-lg"></i> Cari Ruangan
                    </a>
                    <a href="{{ url('/jadwal-saya') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface transition font-medium text-sm">
                        <i class="ph ph-calendar-blank text-lg"></i> Jadwal Saya
                    </a>
                    <a href="{{ url('/riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary font-medium text-sm transition border border-teal-100 dark:border-kinetic-primary/20">
                        <i class="ph-fill ph-magnifying-glass text-lg"></i> Riwayat
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
        <div class="flex-1 overflow-y-auto p-8 lg:p-10 space-y-8 transition-colors duration-300">
    
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-2">
                <a href="#" class="hover:text-kinetic-primary transition">Riwayat</a>
                <i class="ph ph-caret-right text-[10px]"></i>
                <span class="text-slate-500">Detail Pesanan</span>
            </nav>
            <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Lacak Pesanan</h2>
            <div class="flex items-center gap-3">
                <span class="text-sm font-mono font-bold text-slate-400">#SP-20231024-001</span>
                <span class="px-2 py-0.5 bg-red-500/10 text-red-500 border border-red-500/20 rounded text-[10px] font-bold uppercase tracking-wider">Butuh Tindakan</span>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border rounded-xl text-sm font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition shadow-sm">
                <i class="ph ph-printer text-lg"></i> Cetak Bukti
            </button>
            <button class="flex items-center gap-2 px-5 py-2.5 bg-[#10ECE8]/10 text-teal-600 dark:text-kinetic-secondary border border-teal-500/20 rounded-xl text-sm font-bold hover:bg-kinetic-secondary/20 transition">
                <i class="ph ph-headset text-lg"></i> Bantuan CS
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-red-500/10 border border-red-500/20 rounded-2xl p-5 flex gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-red-500/20">
                    <i class="ph-fill ph-warning text-xl"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-red-500 mb-1">Butuh Tindakan Segera</h4>
                    <p class="text-[11px] text-red-400 leading-relaxed">Dokumen pengajuan Anda dikembalikan oleh Kaprodi karena format proposal belum sesuai standar terbaru.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-kinetic-card border border-slate-200 dark:border-kinetic-border rounded-3xl p-8 shadow-sm transition-colors">
                <h3 class="font-heading font-bold text-slate-900 dark:text-white mb-8">Status Progress</h3>
                
                <div class="relative space-y-10">
                    <div class="absolute left-[15px] top-2 bottom-2 w-0.5 bg-slate-100 dark:bg-kinetic-border"></div>

                    <div class="relative flex gap-6">
                        <div class="w-8 h-8 rounded-full bg-teal-500 text-white flex items-center justify-center z-10 shadow-lg shadow-teal-500/20">
                            <i class="ph-bold ph-check text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-teal-600 mb-1 uppercase tracking-widest">24 OKT 2023 • 09:12</p>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Pesanan Diajukan (Submitted)</h4>
                            <p class="text-xs text-slate-500 dark:text-gray-500 leading-relaxed">Peminjaman Ruang Lab Komputer A</p>
                        </div>
                    </div>

                    <div class="relative flex gap-6">
                        <div class="w-8 h-8 rounded-full bg-red-500 text-white flex items-center justify-center z-10 shadow-lg shadow-red-500/20">
                            <i class="ph-bold ph-x text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-red-500 mb-1 uppercase tracking-widest">25 OKT 2023 • 14:00</p>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Revisi Kaprodi</h4>
                            <p class="text-xs text-slate-500 dark:text-gray-500 leading-relaxed">Menunggu perbaikan dokumen dari pemohon</p>
                        </div>
                    </div>

                    <div class="relative flex gap-6">
                        <div class="w-8 h-8 rounded-full bg-slate-100 dark:bg-kinetic-surface border-4 border-white dark:border-kinetic-card ring-2 ring-slate-100 dark:ring-kinetic-border flex items-center justify-center z-10">
                            <div class="w-2 h-2 rounded-full bg-slate-400"></div>
                        </div>
                        <div class="opacity-40">
                            <p class="text-[10px] font-bold text-slate-400 mb-1 uppercase tracking-widest">PENDING</p>
                            <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-1">Persetujuan Dekan</h4>
                            <p class="text-xs text-slate-500 dark:text-gray-500 leading-relaxed">Verifikasi akhir dan penerbitan izin</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-7 space-y-6">
            <div class="relative h-64 rounded-3xl overflow-hidden group shadow-xl">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=1200" alt="Room" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
                <div class="absolute bottom-8 left-8">
                    <span class="px-2 py-1 bg-kinetic-primary/20 backdrop-blur-md text-kinetic-primary border border-kinetic-primary/30 rounded text-[10px] font-bold uppercase mb-3 inline-block">Lab Komputer A</span>
                    <h3 class="text-3xl font-heading font-extrabold text-white">Gedung Science Center</h3>
                    <p class="text-sm text-gray-300">Lantai 3 • Ruang 304</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-kinetic-card border border-slate-200 dark:border-kinetic-border rounded-2xl p-6 transition-colors">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-4">Waktu Peminjaman</p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-calendar text-kinetic-primary text-xl"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Kamis, 2 Nov 2026</p>
                                <p class="text-[10px] text-slate-500">Tanggal Kegiatan</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="ph ph-clock text-kinetic-primary text-xl"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">08:00 - 12:00 WIB</p>
                                <p class="text-[10px] text-slate-500">Durasi: 4 Jam</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-kinetic-card border border-slate-200 dark:border-kinetic-border rounded-2xl p-6 transition-colors">
                    <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-4">Keperluan & Kapasitas</p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <i class="ph ph-users text-kinetic-primary text-xl"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">45 Orang</p>
                                <p class="text-[10px] text-slate-500">Estimasi Peserta</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="ph ph-article text-kinetic-primary text-xl"></i>
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">Workshop UI/UX Design</p>
                                <p class="text-[10px] text-slate-500">Nama Kegiatan</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-slate-100 dark:bg-kinetic-card border border-slate-200 dark:border-kinetic-border rounded-3xl p-8 flex items-center gap-8 transition-colors">
                <div class="w-32 h-32 bg-slate-200 dark:bg-kinetic-surface rounded-2xl flex items-center justify-center border border-slate-300 dark:border-kinetic-border shrink-0 relative overflow-hidden group">
                    <i class="ph-fill ph-lock-key text-4xl text-slate-400 dark:text-gray-600 transition-transform group-hover:scale-110"></i>
                    <div class="absolute inset-0 bg-black/5"></div>
                </div>
                <div class="flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white">E-Sertifikat & Izin Digital</h3>
                        <div class="flex gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                            <span class="w-1.5 h-1.5 rounded-full bg-slate-300 dark:bg-kinetic-border"></span>
                        </div>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-gray-400 leading-relaxed mb-6">
                        Dokumen digital dan QR Code akses pintu akan aktif secara otomatis setelah status pesanan berubah menjadi <span class="text-teal-600 dark:text-kinetic-primary font-bold">"Disetujui"</span>.
                    </p>
                    <button disabled class="flex items-center gap-2 px-6 py-2.5 bg-slate-200 dark:bg-kinetic-surface text-slate-400 dark:text-gray-600 rounded-xl text-xs font-bold border border-slate-300 dark:border-kinetic-border cursor-not-allowed">
                        <i class="ph ph-download-simple"></i> Unduh PDF Izin
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="fixed bottom-8 right-8 z-30">
        <div class="bg-slate-900 dark:bg-white text-white dark:text-slate-900 px-4 py-2 rounded-full shadow-2xl flex items-center gap-3 animate-bounce">
            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
            <span class="text-[10px] font-bold uppercase tracking-tighter">Menunggu Tindakan</span>
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