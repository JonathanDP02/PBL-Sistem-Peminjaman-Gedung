<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Welcome Card -->
    <div class="bg-gray-900 rounded-lg p-8 shadow-2xl">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-white mb-2">Selamat Datang</h1>
            <p class="text-sm text-gray-400">Masuk untuk mengakses dan memesan ruang akademik Anda.</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email / NIM / NIP -->
            <div>
                <label for="email" class="block text-xs uppercase font-semibold text-gray-400 mb-2">Email / NIM / NIP</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="username"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition"
                    placeholder="Masukkan identitas Anda"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs uppercase font-semibold text-gray-400 mb-2">Password</label>
                <input 
                    id="password" 
                    type="password" 
                    name="password" 
                    required 
                    autocomplete="current-password"
                    class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:border-cyan-400 focus:outline-none focus:ring-1 focus:ring-cyan-400 transition"
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        class="w-4 h-4 rounded bg-gray-800 border border-gray-700 text-cyan-400 focus:ring-cyan-400 cursor-pointer" 
                        name="remember"
                    >
                    <span class="text-sm text-gray-300">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a 
                        href="{{ route('password.request') }}" 
                        class="text-sm text-cyan-400 hover:text-cyan-300 transition"
                    >
                        Lupa Password?
                    </a>
                @endif
            </div>

            <!-- Admin Unit Note -->
            <div class="bg-gray-800 border border-gray-700 rounded-lg p-3 flex gap-3">
                <div class="text-cyan-400 flex-shrink-0 mt-0.5">ℹ️</div>
                <p class="text-xs text-gray-300">
                    Sistem tertutup. Akun dibuat oleh <span class="font-semibold">Admin Unit</span> atau melalui <span class="font-semibold">SSO Kampus</span>.
                </p>
            </div>

            <!-- Login Button -->
            <button 
                type="submit"
                class="w-full bg-cyan-400 hover:bg-cyan-500 text-gray-900 font-semibold py-3 px-4 rounded-lg transition duration-200 active:scale-95"
            >
                Masuk
            </button>
        </form>
    </div>
</x-guest-layout>
