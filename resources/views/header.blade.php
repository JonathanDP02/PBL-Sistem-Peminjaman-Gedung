<nav class="fixed w-full z-50 top-0 start-0 border-b border-white/10 bg-black/90 backdrop-blur-md shadow-sm">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4 sm:px-6 lg:px-8">
        <div class="flex items-center">
            <a href="/">
                <x-application-logo class="h-8 w-auto" />
            </a>
        </div>
        <div class="flex items-center gap-5">
            <a href="{{ route('welcome') }}" class="text-white hover:text-[#5EEAD4] font-semibold text-sm px-4 py-2 transition-colors">
                Home
            </a>
            <a href="{{ route('ruangan') }}" class="text-white hover:text-[#5EEAD4] font-semibold text-sm px-4 py-2 transition-colors">
                Ruangan
            </a>
            <a href="{{ route('login') }}" class="text-white hover:text-[#5EEAD4] bg-white/10 hover:bg-white/20 border border-white/20 font-semibold rounded-lg text-sm px-5 py-2.5 transition-all backdrop-blur-sm">
                Masuk
            </a>
        </div>
    </div>
</nav>