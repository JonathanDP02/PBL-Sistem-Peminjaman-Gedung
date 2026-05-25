<nav class="fixed w-full z-50 top-0 start-0 border-b border-slate-200/50 dark:border-white/10 bg-white/80 dark:bg-black/90 backdrop-blur-md shadow-sm transition-colors duration-300">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4 sm:px-6 lg:px-8">
        <div class="flex items-center">
            <a href="/">
                <x-application-logo class="h-8 w-auto text-slate-900 dark:text-white" />
            </a>
        </div>
        <div class="flex items-center gap-5">
            <a href="{{ route('welcome') }}" class="text-white hover:text-[#5EEAD4] font-semibold text-sm px-4 py-2 transition-colors">
                <!-- Home -->
                Beranda
            </a>
            <a href="{{ route('ruangan') }}" class="text-slate-700 dark:text-white hover:text-[#14B8A6] dark:hover:text-[#5EEAD4] font-semibold text-sm px-3 py-2 transition-colors">
                Ruangan
            </a>
            <a href="{{ route('booking.scan') }}" class="text-slate-700 dark:text-white hover:text-[#14B8A6] dark:hover:text-[#5EEAD4] font-semibold text-sm px-3 py-2 transition-colors">
                Scan Surat
            </a>

            <!-- Theme Toggle Button -->
            <button id="guestThemeToggleBtn" class="p-2.5 rounded-lg text-slate-500 dark:text-gray-400 hover:bg-slate-100 dark:hover:bg-white/10 transition-all focus:outline-none" title="Ubah Tema">
                <i id="guestThemeIconMoon" class="ph ph-moon text-lg"></i>
                <i id="guestThemeIconSun" class="ph ph-sun text-lg hidden"></i>
            </button>

            <a href="{{ route('login') }}" class="text-slate-700 dark:text-white hover:text-white hover:bg-[#14B8A6] bg-slate-100 dark:bg-white/10 hover:dark:bg-white/20 border border-slate-200 dark:border-white/20 font-semibold rounded-lg text-sm px-4 py-2 transition-all backdrop-blur-sm">
                Masuk
            </a>
        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggleBtn = document.getElementById('guestThemeToggleBtn');
        const html = document.documentElement;
        const themeIconMoon = document.getElementById('guestThemeIconMoon');
        const themeIconSun = document.getElementById('guestThemeIconSun');

        const syncThemeUI = () => {
            const isDark = html.classList.contains('dark');
            if (themeIconMoon) themeIconMoon.classList.toggle('hidden', isDark);
            if (themeIconSun) themeIconSun.classList.toggle('hidden', !isDark);
        };

        // Sync initially
        syncThemeUI();

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', () => {
                html.classList.toggle('dark');
                localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
                syncThemeUI();
            });
        }
    });
</script>