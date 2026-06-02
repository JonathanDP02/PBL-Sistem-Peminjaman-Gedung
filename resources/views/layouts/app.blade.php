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

        <!-- Smooth Transitions (View Transitions API & CSS Animation) -->
        <style>
            @view-transition {
                navigation: auto; /* Enable native MPA transitions (Chrome/Edge) */
            }

            /* Content Entrance Animation */
            main {
                animation: contentFadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1) forwards;
            }

            @keyframes contentFadeIn {
                from {
                    opacity: 0;
                    transform: translateY(8px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            /* Optional: Customizing View Transitions */
            ::view-transition-old(root) {
                animation: fadeOut 0.3s ease-out forwards;
            }
            ::view-transition-new(root) {
                animation: fadeIn 0.3s ease-in forwards;
            }

            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            @keyframes fadeOut {
                from { opacity: 1; }
                to { opacity: 0; }
            }
        </style>
    </head>
    <body class="flex h-screen overflow-hidden antialiased bg-slate-50 dark:bg-kinetic-bg text-slate-900 dark:text-white transition-colors duration-300">
        @include('layouts.sidebar')

        <div x-data="{ 
            notificationsOpen: false, 
            detailModalOpen: false, 
            selectedNotification: null,
            userRole: '{{ Auth::user()->role?->name ?? '' }}',
            openDetail(notif) {
                // Jika ada booking_id, langsung arahkan ke halaman detail booking sesuai role
                if (notif.booking_id) {
                    if (this.userRole === 'Penyetuju') {
                        window.location.href = `/approver/approvals/${notif.booking_id}`;
                    } else {
                        window.location.href = `/peminjam/detail/${notif.booking_id}`;
                    }
                    return;
                }
                
                // Fallback jika tidak ada booking_id (tetap modal)
                this.selectedNotification = notif;
                this.detailModalOpen = true;
                this.notificationsOpen = false;
            }
        }" class="flex-1 flex flex-col h-screen overflow-hidden">
            @include('layouts.header')

            <main class="flex-1 overflow-y-auto relative transition-colors duration-300">
                {{ $slot }}
            </main>

        <!-- Script Tema Gelap/Terang & Progress Bar -->
        <script>
            // Theme Logic
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
                localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
                syncThemeUI();
            });

            // Smooth Page Loading Progress Bar
            const startLoading = () => {
                if (document.getElementById('page-loading-bar')) return;
                
                const nprogress = document.createElement('div');
                nprogress.style.position = 'fixed';
                nprogress.style.top = '0';
                nprogress.style.left = '0';
                nprogress.style.height = '3px';
                nprogress.style.background = 'linear-gradient(90deg, #14B8A6 0%, #10ECE8 50%, #0DBAF9 100%)';
                nprogress.style.zIndex = '9999';
                nprogress.style.width = '0%';
                nprogress.style.transition = 'width 0.3s ease-out, opacity 0.3s ease-in-out';
                nprogress.id = 'page-loading-bar';
                document.body.appendChild(nprogress);
                
                // Add a glowing effect
                nprogress.style.boxShadow = '0 0 10px rgba(20, 184, 166, 0.5)';
                
                setTimeout(() => {
                    if (document.getElementById('page-loading-bar')) {
                        document.getElementById('page-loading-bar').style.width = '85%';
                    }
                }, 10);
            };

            window.addEventListener('beforeunload', startLoading);

            // Instant.page-like Pre-fetching for ultra-smooth navigation
            let prefetchTimeout;
            const prefetch = (url) => {
                if (!url || url.includes('#') || url.includes('logout') || url.includes('download')) return;
                const link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = url;
                document.head.appendChild(link);
            };

            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('mouseenter', (e) => {
                    const href = link.getAttribute('href');
                    prefetchTimeout = setTimeout(() => prefetch(href), 80);
                });
                link.addEventListener('mouseleave', () => clearTimeout(prefetchTimeout));
                
                link.addEventListener('click', (e) => {
                    const href = link.getAttribute('href');
                    if (href && href.startsWith('/') && !href.startsWith('#') && !link.hasAttribute('download') && !e.ctrlKey && !e.metaKey) {
                        // Fade out content area for extra smoothness
                        const main = document.querySelector('main');
                        if (main) {
                            main.style.opacity = '0.4';
                            main.style.filter = 'blur(1px)';
                            main.style.transition = 'all 0.3s ease-out';
                        }
                        startLoading();
                    }
                });
            });
        </script>

        </script>
        


        @stack('modals')
        @stack('scripts')
    </body>
</html>