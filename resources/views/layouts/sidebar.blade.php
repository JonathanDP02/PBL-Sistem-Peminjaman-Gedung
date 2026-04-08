<aside class="w-64 bg-white dark:bg-kinetic-bg border-r border-slate-200 dark:border-kinetic-border flex flex-col justify-between shrink-0 z-20 transition-colors duration-300">
    <div>
        <div class="p-6 h-20 flex items-center">
            <h1 class="font-heading font-extrabold text-2xl tracking-tight text-slate-900 dark:text-white">Space<span class="text-kinetic-primary">.in</span></h1>
        </div>
        <div class="px-6 mb-4">
            <p class="text-[10px] font-bold tracking-widest text-slate-400 dark:text-gray-500 uppercase mb-4">Enterprise Booking</p>
            <nav class="space-y-1">
                <!-- Menu untuk User -->
                @if(Auth::user()->role->name === 'User')
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('dashboard') ? 'ph-fill' : '' }} ph-squares-four text-lg"></i> Dashboard
                    </a>
                    <a href="{{ route('cari-ruangan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('cari-ruangan') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('cari-ruangan') ? 'ph-fill' : '' }} ph-magnifying-glass text-lg"></i> Cari Ruangan
                    </a>
                    <a href="{{ route('jadwal-saya') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('jadwal-saya') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('jadwal-saya') ? 'ph-fill' : '' }} ph-calendar-blank text-lg"></i> Jadwal Saya
                    </a>
                    <a href="{{ route('riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('riwayat') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('riwayat') ? 'ph-fill' : '' }} ph-clock-counter-clockwise text-lg"></i> Riwayat
                    </a>
                @endif

                <!-- Menu untuk SuperAdmin/Admin_Unit -->
                @if(Auth::user()->role->name === 'Approver')
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('dashboard') ? 'ph-fill' : '' }} ph-squares-four text-lg"></i> Dashboard
                    </a>
                    <a href="{{ route('fasilitas') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('fasilitas') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('fasilitas') ? 'ph-fill' : '' }} ph-buildings text-lg"></i> Fasilitas
                    </a>
                    <a href="{{ route('unit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('unit') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('unit') ? 'ph-fill' : '' }} ph-door text-lg"></i> Unit
                    </a>
                    <a href="{{ route('kelola-user') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('akun') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('kelola-user') ? 'ph-fill' : '' }} ph-user-gear text-lg"></i> Akun
                    </a>
                @endif

                <!-- Menu untuk Approver -->
                @if(Auth::user()->role->name === 'SuperAdmin' || Auth::user()->role->name === 'Admin_Unit')
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('dashboard') ? 'ph-fill' : '' }} ph-squares-four text-lg"></i> Dashboard
                    </a>
                    <a href="{{ route('meja-kerja') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('meja-kerja') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('meja-kerja') ? 'ph-fill' : '' }} ph-desk text-lg"></i> Meja Kerja
                    </a>
                    <a href="{{ route('riwayat') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg font-medium text-sm transition {{ request()->routeIs('approver.riwayat') ? 'bg-teal-50 text-teal-700 dark:bg-kinetic-primary/10 dark:text-kinetic-secondary border border-teal-100 dark:border-kinetic-primary/20' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-kinetic-surface border border-slate-200 dark:border-kinetic-border' }}">
                        <i class="ph {{ request()->routeIs('riwayat') ? 'ph-fill' : '' }} ph-clock-counter-clockwise text-lg"></i> Riwayat
                    </a>
                @endif
            </nav>
        </div>
    </div>
    
    <div class="p-6 border-t border-slate-200 dark:border-kinetic-border">
        <!-- THEME TOGGLE BUTTON -->
        <button id="themeToggleBtn" class="w-full flex items-center text-slate-600 justify-between px-3 py-2.5 mb-4 rounded-lg bg-slate-100 dark:bg-kinetic-surface dark:text-gray-400 hover:text-slate-900 dark:hover:text-white transition border border-slate-200 dark:border-kinetic-border">
            <span class="text-sm font-medium flex items-center gap-2 text-slate-700 dark:text-gray-300">
                <i id="themeIconMoon" class="ph ph-moon"></i>
                <i id="themeIconSun" class="ph ph-sun hidden"></i>
                <span id="themeToggleText">Mode Gelap</span>
            </span>
            <!-- Custom Switch UI -->
            <div class="w-8 h-4 bg-slate-300 dark:bg-kinetic-border rounded-full relative transition-colors">
                <div class="absolute left-0.5 top-0.5 w-3 h-3 bg-white dark:bg-kinetic-primary rounded-full transition-transform dark:translate-x-3.5"></div>
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