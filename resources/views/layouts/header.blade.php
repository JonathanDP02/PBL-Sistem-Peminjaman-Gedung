<header class="h-20 px-8 flex items-center justify-between border-b border-slate-200 dark:border-kinetic-border/50 shrink-0 z-50 bg-white/50 dark:bg-transparent backdrop-blur-sm relative">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
        {{ getPageTitle() }}
    </h2>
    <div class="flex items-center gap-4">
        @if(Auth::check() && in_array(Auth::user()->role->name, ['Approver', 'User']))
        <!-- Notification Bell & Dropdown -->
        <div class="relative">
            <button @click="notificationsOpen = !notificationsOpen" @click.away="notificationsOpen = false" class="relative p-2 text-slate-500 dark:text-gray-400 hover:text-slate-900 dark:hover:text-white transition focus:outline-none">
                <i class="ph ph-bell text-xl"></i>
                @if(isset($globalNotifications) && $globalNotifications->count() > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-kinetic-primary rounded-full"></span>
                @endif
            </button>

            <!-- Dropdown Menu -->
            <div x-show="notificationsOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-80 bg-white dark:bg-kinetic-card border border-slate-200 dark:border-kinetic-border rounded-2xl shadow-xl z-[60] overflow-hidden"
                 style="display: none;">
                
                <div class="p-4 border-b border-slate-100 dark:border-kinetic-border flex justify-between items-center">
                    <h3 class="font-bold text-sm text-slate-900 dark:text-white">Notifikasi</h3>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">{{ isset($globalNotifications) ? $globalNotifications->count() : 0 }} Baru</span>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    @forelse($globalNotifications ?? [] as $log)
                        @php
                            $isApproved = $log->action === 'APPROVED';
                            $isRejected = $log->action === 'REJECTED';
                            $isRevised = $log->action === 'REVISED';
                        @endphp
                        <div @click="openDetail({{ json_encode($log) }})" class="p-4 border-b border-slate-50 dark:border-kinetic-border/50 hover:bg-slate-50 dark:hover:bg-kinetic-surface transition cursor-pointer group">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0 border {{ $isApproved ? 'bg-teal-50 text-teal-600 border-teal-100' : ($isRejected ? 'bg-red-50 text-red-500 border-red-100' : 'bg-blue-50 text-blue-500 border-blue-100') }}">
                                    <i class="ph-fill {{ $isApproved ? 'ph-check' : ($isRejected ? 'ph-x' : 'ph-info') }}"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-xs font-bold text-slate-900 dark:text-white mb-0.5 group-hover:text-kinetic-primary transition">
                                        {{ $log->action }}
                                    </p>
                                    <p class="text-[10px] text-slate-500 dark:text-gray-400 line-clamp-1 italic">
                                        Booking #{{ $log->booking_id }} • {{ $log->booking->room->room_name ?? '' }}
                                    </p>
                                    <p class="text-[9px] text-slate-400 dark:text-gray-500 mt-1 uppercase font-bold tracking-tighter">
                                        {{ $log->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <i class="ph ph-bell-slash text-3xl text-slate-300 dark:text-gray-600 mb-2"></i>
                            <p class="text-xs text-slate-500">Tidak ada notifikasi</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('riwayat') }}" class="block p-3 text-center text-[10px] font-bold text-teal-600 dark:text-kinetic-primary hover:bg-slate-50 dark:hover:bg-kinetic-surface transition uppercase tracking-widest">
                    Lihat Semua Riwayat
                </a>
            </div>
        </div>
        <div class="h-6 w-px bg-slate-200 dark:bg-kinetic-border mx-2"></div>
        @endif
        <div class="hidden sm:flex sm:items-center sm:ms-6">
            @auth
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-2 text-xs font-bold tracking-wider text-slate-500 dark:text-gray-400 uppercase hover:text-slate-900 dark:hover:text-white transition">
                            {{ Auth::user()->role?->name }} <i class="ph-fill ph-caret-down text-[10px]"></i>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            @else
                <a href="{{ route('login') }}" class="text-sm font-semibold text-[#14B8A6] hover:text-[#10ECE8] transition">
                    Login
                </a>
            @endauth
        </div>
        <!-- Hamburger -->
        <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</header>