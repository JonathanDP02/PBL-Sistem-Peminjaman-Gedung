<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="flex h-screen overflow-hidden antialiased bg-slate-50 dark:bg-kinetic-bg text-slate-900 dark:text-white transition-colors duration-300">
        @include('layouts.sidebar')

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            @include('layouts.header')

            <main class="flex-1 overflow-y-auto relative transition-colors duration-300">
                {{ $slot }}
            </main>
        </div>

        <!-- Script Tema Gelap/Terang (Tetap di sini karena dipakai di semua halaman) -->
        <script>
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const html = document.documentElement;
            const themeToggleText = document.getElementById('themeToggleText');
            const themeIconMoon = document.getElementById('themeIconMoon');
            const themeIconSun = document.getElementById('themeIconSun');

            const syncThemeUI = () => {
                const isDark = html.classList.contains('dark');
                if (themeToggleText) themeToggleText.textContent = isDark ? 'Mode Terang' : 'Mode Gelap';
                if (themeIconMoon) themeIconMoon.classList.toggle('hidden', isDark);
                if (themeIconSun) themeIconSun.classList.toggle('hidden', !isDark);
            };

            if (localStorage.theme === 'dark') {
                html.classList.add('dark');
            } else {
                html.classList.remove('dark');
            }
            syncThemeUI();

            themeToggleBtn.addEventListener('click', () => {
                html.classList.toggle('dark');
                
                if (html.classList.contains('dark')) {
                    localStorage.theme = 'dark';
                } else {
                    localStorage.theme = 'light';
                }
                syncThemeUI();
            });
        </script>

        <!-- KUNCI JAWABANNYA DI SINI: Tempat untuk memunculkan @push('scripts') -->
        @stack('scripts')
        
    </body>
</html>