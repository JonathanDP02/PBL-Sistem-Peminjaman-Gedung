<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SpaceIn - Sistem Peminjaman Gedung</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* --- VARIABEL WARNA BARU (SEMUA BIRU) --- */
        :root {
            /* Warna Aksen Utama - Biru Cerah (pengganti Cyan) */
            --blue-accent: #38bdf8;
            /* Warna Tombol & Teks Sorotan Hero - Biru Standar */
            --blue-main: #3b82f6;
            
            /* Warna Status Kalender */
            --confirmed-blue: #0ea5e9;
            --pending-blue: #7dd3fc;
            --locked-red: #ef4444; /* Pertahankan Merah untuk fungsi penting */
            
            --bg-calendar: #0f172a; 
            --border-color: rgba(255, 255, 255, 0.1); 
            --text-main: #f8fafc; 
            --text-dim: #94a3b8; 
        }

        /* --- TEMA & BACKGROUND UTAMA --- */
        body {
            background-color: #0f172a; 
            overflow-x: hidden; 
            margin: 0;
            padding: 0;
            font-family: 'Figtree', sans-serif;
            color: #f8fafc;
        }

        body::before, body::after {
            content: '';
            position: fixed; 
            width: 450px;
            height: 450px;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            animation: melayang 12s infinite ease-in-out alternate;
        }

        /* Lampu fixed tetap biru dan ungu, serasi dengan tema */
        body::before { background: var(--blue-main); top: -5%; left: -10%; opacity: 0.5;}
        body::after { background: #8b5cf6; bottom: 10%; right: -10%; animation-duration: 15s; animation-delay: -3s; opacity: 0.4;}

        @keyframes melayang {
            0% { transform: translate(0px, 0px) scale(1); }
            100% { transform: translate(120px, 80px) scale(1.2); }
        }
        
        .bg-grid-pattern {
            background-image: radial-gradient(rgba(255, 255, 255, 0.15) 1px, transparent 1px);
            background-size: 24px 24px;
        }

        /* --- CUSTOM CSS KOMPONEN --- */
        .custom-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 50px;
            width: 100%;
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 0 20px;
        }

        /* Label Judul Menyala - SEKARANG BIRU */
        .glow-label {
            background-color: var(--blue-accent);
            color: #000000;
            padding: 12px 32px;
            border-radius: 30px;
            font-weight: bold;
            font-size: 1.2rem;
            /* Update RGBA shadow agar biru */
            box-shadow: 0 0 35px 5px rgba(56, 189, 248, 0.3);
            letter-spacing: 0.5px;
            text-align: center;
            display: inline-block;
        }

        /* --- SECTION KARTU GEDUNG - SEKARANG BIRU --- */
        .grid-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 60px 180px; 
            width: 100%;
            justify-items: center;
        }

        .card {
            background-color: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(10px);
            border: 2px solid var(--blue-accent);
            border-radius: 12px;
            width: 100%;
            max-width: 400px; 
            height: 280px; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 24px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-5px);
            /* Update RGBA shadow agar biru */
            box-shadow: 0 8px 25px rgba(56, 189, 248, 0.15);
        }

        .card-title {
            color: var(--blue-accent);
            font-size: 1.2rem;
            font-weight: 600;
            margin: 0;
            text-align: center;
        }

        .card svg {
            width: 80px;
            height: 80px;
        }

        /* --- SECTION KALENDER - SEKARANG AKSEN BIRU --- */
        .calendar-wrapper {
            background-color: var(--bg-calendar);
            border: 1px solid var(--blue-accent);
            border-radius: 16px;
            width: 100%;
            /* Update RGBA shadow agar biru */
            box-shadow: 0 0 50px 0 rgba(56, 189, 248, 0.15);
            overflow-x: auto; 
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: 100px repeat(7, minmax(120px, 1fr));
            border-collapse: collapse;
            min-width: 800px; 
        }

        .calendar-grid > div {
            border-bottom: 1px solid var(--border-color);
            border-right: 1px solid var(--border-color);
            padding: 16px;
        }

        .calendar-grid > div:nth-child(8n) {
            border-right: none;
        }

        .col-header {
            text-align: center;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1px;
            color: var(--text-dim);
            display: flex;
            flex-direction: column;
            gap: 6px;
            text-transform: uppercase;
            justify-content: center;
        }

        .col-header span {
            font-size: 1.2rem;
            color: var(--text-main);
        }

        /* Update header aktif ke aksen biru cerah */
        .col-header.active { color: var(--blue-accent); }
        .col-header.active span { color: var(--blue-accent); }
        .col-header.dim span { color: #4a4d54; }

        .time-label {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-main);
            padding-top: 24px !important;
        }
        .time-label.dim {
            color: var(--text-dim);
            align-items: center;
            padding-top: 16px !important;
        }

        .break-row {
            grid-column: 2 / -1;
            display: flex;
            align-items: center;
            background-color: #1a1d22;
            font-size: 0.75rem;
            font-weight: bold;
            letter-spacing: 3px;
            color: var(--text-dim);
            padding-left: 24px !important;
        }

        .weekend-bg { background-color: #0f1114; }

        .cell {
            padding: 12px !important;
            height: 130px;
        }

        .slot {
            width: 100%;
            height: 100%;
            border-radius: 8px;
            padding: 12px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            gap: 8px;
            position: relative;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .slot:hover { transform: scale(1.02); }

        .slot-header {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.65rem;
            font-weight: bold;
            text-transform: uppercase;
        }

        .slot-title {
            font-size: 0.85rem;
            font-weight: bold;
            margin: 4px 0 0 0;
            color: var(--text-main);
        }

        .slot-desc {
            font-size: 0.65rem;
            color: var(--text-dim);
            margin: 0;
            line-height: 1.4;
        }

        /* --- UPDATE STATUS SLOT KALENDER --- */
        
        /* Tipe: Slot Kosong (Update warna aksen) */
        .slot.empty { border: 1px dashed rgba(56, 189, 248, 0.2); background-color: transparent; }
        .slot.empty .slot-header { color: var(--blue-accent); }
        .slot.empty .dot { width: 6px; height: 6px; background-color: var(--blue-accent); border-radius: 50%; }
        /* Update warna icon add */
        .icon-add { position: absolute; bottom: 12px; right: 12px; stroke: rgba(56, 189, 248, 0.5); }

        /* Tipe: Locked (Pertahankan MERAH untuk fungsi penting) */
        .slot.locked { border: 1px solid rgba(239, 68, 68, 0.5); background-color: rgba(239, 68, 68, 0.1); }
        .slot.locked .slot-header { color: var(--locked-red); }

        /* Tipe: Pending Approval (Gunakan Biru Muda) */
        .slot.pending { border: 1px solid rgba(125, 211, 252, 0.4); background-color: rgba(125, 211, 252, 0.08); }
        .slot.pending .slot-header { color: var(--pending-blue); }

        /* Tipe: Confirmed (Gunakan Biru Utama) */
        .slot.confirmed { border: 1px solid rgba(14, 165, 233, 0.5); background-color: rgba(14, 165, 233, 0.15); }
        .slot.confirmed .slot-header { color: var(--confirmed-blue); }

        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: 1fr; 
                gap: 40px; 
            }
        }
    </style>
</head>
<body class="antialiased selection:bg-blue-500 selection:text-white">
    
    <nav class="fixed w-full z-50 top-0 start-0 border-b border-white/10 bg-[#0f172a]/80 backdrop-blur-md shadow-sm">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4 sm:px-6 lg:px-8">
            <a href="#" class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-blue-600/30">
                    S
                </div>
                <span class="self-center text-2xl font-extrabold whitespace-nowrap text-white tracking-tight">SpaceIn.</span>
            </a>
            
            <div class="flex items-center space-x-3 md:space-x-4">
                <a href="{{ route('login') }}" class="text-white hover:text-white font-semibold text-sm px-4 py-2 transition-colors">
                    Masuk
                </a>
                <a href="{{ route('register') }}" class="text-white bg-white/10 hover:bg-white/20 border border-white/20 font-semibold rounded-lg text-sm px-5 py-2.5 transition-all backdrop-blur-sm">
                    Daftar
                </a>
            </div>
        </div>
    </nav>

    <section class="relative pt-32 pb-12 lg:pt-40 lg:pb-24 flex items-center justify-center text-center">
        <div class="absolute inset-0 bg-grid-pattern opacity-40 -z-10"></div>
        
        <div class="max-w-screen-xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col items-center">
            <div class="max-w-3xl mx-auto w-full">
                
                <div class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 border border-white/30 text-white text-sm font-semibold mb-8 backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    Sistem Peminjaman Gedung
                </div>
                
                <h1 class="mb-6 text-4xl font-extrabold leading-tight text-white sm:text-5xl tracking-wide">
                    Reservasi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Gedung</span> & Fasilitas Kini Lebih Mudah.
                </h1>
                
                <p class="mb-10 text-base font-medium text-slate-400 sm:text-lg leading-relaxed max-w-2xl mx-auto">
                    SpaceIn mengotomatisasi alur birokrasi peminjaman ruang kelas, aula, dan lab. Cek ketersediaan ruangan secara real-time dan ajukan peminjaman tanpa repot.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-10 py-3.5 text-base font-bold text-white transition-all bg-blue-600 rounded-xl hover:bg-blue-500 shadow-lg shadow-blue-600/30 hover:-translate-y-1">
                        Mulai Pinjam Sekarang
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <section class="py-16 relative">
        <div class="custom-container">
            <div class="glow-label">Gedung yang kami sediakan</div>

            <div class="grid-container">
                <div class="card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="square" stroke-linejoin="miter">
                        <rect x="5" y="4" width="7" height="16" />
                        <line x1="5" y1="8" x2="12" y2="8" />
                        <line x1="5" y1="12" x2="12" y2="12" />
                        <line x1="5" y1="16" x2="12" y2="16" />
                        <rect x="12" y="8" width="7" height="12" />
                        <rect x="14" y="12" width="3" height="4" />
                    </svg>
                    <p class="card-title">Gedung Sipil</p>
                </div>

                <div class="card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="square" stroke-linejoin="miter">
                        <rect x="5" y="4" width="7" height="16" />
                        <line x1="5" y1="8" x2="12" y2="8" />
                        <line x1="5" y1="12" x2="12" y2="12" />
                        <line x1="5" y1="16" x2="12" y2="16" />
                        <rect x="12" y="8" width="7" height="12" />
                        <rect x="14" y="12" width="3" height="4" />
                    </svg>
                    <p class="card-title">Gedung Mesin</p>
                </div>

                <div class="card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="square" stroke-linejoin="miter">
                        <rect x="5" y="4" width="7" height="16" />
                        <line x1="5" y1="8" x2="12" y2="8" />
                        <line x1="5" y1="12" x2="12" y2="12" />
                        <line x1="5" y1="16" x2="12" y2="16" />
                        <rect x="12" y="8" width="7" height="12" />
                        <rect x="14" y="12" width="3" height="4" />
                    </svg>
                    <p class="card-title">Aula Pertamina</p>
                </div>

                <div class="card">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="square" stroke-linejoin="miter">
                        <rect x="5" y="4" width="7" height="16" />
                        <line x1="5" y1="8" x2="12" y2="8" />
                        <line x1="5" y1="12" x2="12" y2="12" />
                        <line x1="5" y1="16" x2="12" y2="16" />
                        <rect x="12" y="8" width="7" height="12" />
                        <rect x="14" y="12" width="3" height="4" />
                    </svg>
                    <p class="card-title">Graha Polinema</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 relative mb-20">
        <div class="custom-container">
            <div class="glow-label">Jadwal Booking</div>

            <div class="calendar-wrapper">
                <div class="calendar-grid">
                    
                    <div class="col-header time-label" style="padding-top:16px!important">WAKTU</div>
                    <div class="col-header">SENIN<br><span>16</span></div>
                    <div class="col-header active">SELASA<br><span>17</span></div>
                    <div class="col-header">RABU<br><span>18</span></div>
                    <div class="col-header">KAMIS<br><span>19</span></div>
                    <div class="col-header">JUMAT<br><span>20</span></div>
                    <div class="col-header dim weekend-bg">SABTU<br><span>21</span></div>
                    <div class="col-header dim weekend-bg">MINGGU<br><span>22</span></div>

                    <div class="time-label">08:00</div>
                    <div class="cell">
                        <div class="slot empty">
                            <div class="slot-header"><div class="dot"></div> SLOT KOSONG</div>
                            <svg class="icon-add" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        </div>
                    </div>
                    <div class="cell">
                        <div class="slot locked">
                            <div class="slot-header">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                LOCKED BY IT
                            </div>
                            <p class="slot-title">Lab<br>Komputer A</p>
                            <p class="slot-desc">Maintenance<br>Mingguan</p>
                        </div>
                    </div>
                    <div class="cell">
                        <div class="slot empty">
                            <div class="slot-header"><div class="dot"></div> SLOT KOSONG</div>
                            <svg class="icon-add" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        </div>
                    </div>
                    <div class="cell">
                        <div class="slot pending">
                            <div class="slot-header">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 8 10"/></svg>
                                PENDING APPROVAL
                            </div>
                            <p class="slot-title">Ruang<br>Diskusi 04</p>
                            <p class="slot-desc">Proyek Akhir<br>Semester</p>
                        </div>
                    </div>
                    <div class="cell">
                        <div class="slot empty">
                            <div class="slot-header"><div class="dot"></div> SLOT KOSONG</div>
                            <svg class="icon-add" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        </div>
                    </div>
                    <div class="cell weekend-bg"></div>
                    <div class="cell weekend-bg"></div>

                    <div class="time-label">10:00</div>
                    <div class="cell">
                        <div class="slot confirmed">
                            <div class="slot-header" style="justify-content: space-between;">
                                <span>CONFIRMED</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            </div>
                            <p class="slot-title">Aula Utama</p>
                            <p class="slot-desc">Seminar Nasional<br>AI</p>
                        </div>
                    </div>
                    <div class="cell"></div>
                    <div class="cell">
                        <div class="slot empty">
                            <div class="slot-header"><div class="dot"></div> SLOT KOSONG</div>
                        </div>
                    </div>
                    <div class="cell"></div>
                    <div class="cell"></div>
                    <div class="cell weekend-bg"></div>
                    <div class="cell weekend-bg"></div>

                    <div class="time-label dim">12:00</div>
                    <div class="break-row">ISTIRAHAT & PEMBERSIHAN RUANGAN</div>

                    <div class="time-label">13:00</div>
                    <div class="cell"></div>
                    <div class="cell">
                        <div class="slot empty">
                            <div class="slot-header"><div class="dot"></div> SLOT KOSONG</div>
                        </div>
                    </div>
                    <div class="cell"></div>
                    <div class="cell"></div>
                    <div class="cell">
                        <div class="slot confirmed">
                            <div class="slot-header" style="justify-content: space-between;">
                                <span>CONFIRMED</span>
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            </div>
                            <p class="slot-title">Ruang Rapat<br>02</p>
                            <p class="slot-desc">Rapat Koordinasi</p>
                        </div>
                    </div>
                    <div class="cell weekend-bg"></div>
                    <div class="cell weekend-bg"></div>

                </div>
            </div>
        </div>
    </section>

</body>
</html>