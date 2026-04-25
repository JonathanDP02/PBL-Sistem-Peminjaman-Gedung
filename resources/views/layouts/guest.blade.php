<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SpaceIn - Sistem Peminjaman Gedung</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --blue-accent: #14B8A6; /* Primary */
            --blue-main: #10ECE8; /* Secondary */
            --bg-calendar: #0A0A0A; 
            --border-color: rgba(255, 255, 255, 0.05); 
            --text-main: #f8fafc; 
            --text-dim: #94a3b8; 
        }
        body {
            background-color: #0A0A0A; 
            overflow-x: hidden; 
            margin: 0; padding: 0;
            font-family: 'Figtree', sans-serif; color: #f8fafc;
        }
        body::before, body::after {
            content: ''; position: fixed; width: 450px; height: 450px;
            border-radius: 50%; filter: blur(120px); z-index: -1;
            animation: melayang 12s infinite ease-in-out alternate;
        }
        body::before { background: #14B8A6; top: -5%; left: -10%; opacity: 0.15;}
        body::after { background: #0DBAF9; bottom: 10%; right: -10%; animation-duration: 15s; animation-delay: -3s; opacity: 0.1;}
        @keyframes melayang { 0% { transform: translate(0px, 0px) scale(1); } 100% { transform: translate(120px, 80px) scale(1.2); } }
        
        .glow-label {
            background-color: transparent; color: #14B8A6; padding: 8px 24px;
            border: 1px solid #14B8A6; border-radius: 30px; font-weight: bold;
            font-size: 1rem; box-shadow: 0 0 20px rgba(20, 184, 166, 0.2);
            text-align: center; display: inline-block;
        }

        .card {
            background-color: rgba(15, 23, 42, 0.4); backdrop-filter: blur(10px);
            border: 1px solid rgba(20, 184, 166, 0.3); border-radius: 12px;
            width: 100%; max-width: 400px; height: 280px; display: flex;
            flex-direction: column; justify-content: center; align-items: center; gap: 24px;
            transition: all 0.3s ease; cursor: pointer;
        }
        .card:hover { transform: translateY(-5px); border-color: #14B8A6; box-shadow: 0 8px 25px rgba(20, 184, 166, 0.15); }
        .card-title { color: #fff; font-size: 1.2rem; font-weight: 600; margin: 0; text-align: center; }
        .card svg { width: 80px; height: 80px; }
        
        .calendar-wrapper {
            background-color: var(--bg-calendar); border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px; width: 100%; box-shadow: 0 0 40px rgba(0,0,0,0.5); overflow-x: auto; 
        }
        .calendar-grid {
            display: grid; grid-template-columns: 100px repeat(7, minmax(120px, 1fr));
            border-collapse: collapse; min-width: 800px; 
        }
        .calendar-grid > div { border-bottom: 1px solid var(--border-color); border-right: 1px solid var(--border-color); padding: 16px; }
        .calendar-grid > div:nth-child(8n) { border-right: none; }
        .col-header { text-align: center; font-size: 0.75rem; font-weight: 600; letter-spacing: 1px; color: var(--text-dim); display: flex; flex-direction: column; gap: 6px; text-transform: uppercase; justify-content: center; }
        .col-header span { font-size: 1.5rem; font-weight: 700; color: var(--text-main); }
        .col-header.active { color: var(--blue-accent); }
        .col-header.active span { color: var(--blue-accent); }
        .col-header.dim span { color: #4a4d54; }
        .time-label { display: flex; align-items: flex-start; justify-content: center; font-size: 0.85rem; font-weight: 600; color: var(--text-dim); padding-top: 24px !important; }
        .weekend-bg { background-color: rgba(255,255,255,0.02); }
        .cell { padding: 12px !important; height: 120px; }
        .slot { width: 100%; height: 100%; border-radius: 8px; padding: 12px; box-sizing: border-box; display: flex; flex-direction: column; gap: 8px; position: relative; cursor: pointer; transition: transform 0.2s; }
        .slot:hover { transform: scale(1.02); }
        .slot-header { display: flex; align-items: center; gap: 6px; font-size: 0.65rem; font-weight: bold; text-transform: uppercase; }
        .slot-title { font-size: 0.85rem; font-weight: bold; margin: 4px 0 0 0; color: var(--text-main); }
        
        .slot.tersedia { border: 1px solid rgba(20, 184, 166, 0.4); background-color: rgba(20, 184, 166, 0.1); }
        .slot.tersedia .slot-header { color: #14B8A6; }
        
        .slot.terisi { border: 1px solid rgba(239, 68, 68, 0.4); background-color: rgba(239, 68, 68, 0.1); }
        .slot.terisi .slot-header { color: #ef4444; }

    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased selection:bg-[#14B8A6] selection:text-white {{ request()->routeIs('login') ? 'overflow-hidden' : '' }}">
    @if(!request()->routeIs('login'))
        @include('header')
    @endif
    {{ $slot }}
</body>
</html>


