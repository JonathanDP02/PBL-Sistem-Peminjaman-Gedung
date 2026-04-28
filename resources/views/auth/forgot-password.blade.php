<x-guest-layout>
    <!-- Container utama di-lock persis 1 layar (h-screen) dan dihilangkan scrollnya (overflow-hidden) -->
    <div class="h-screen w-full overflow-hidden flex items-center justify-center relative">
        <div class="w-full max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 items-center z-10 scale-95 md:scale-100 origin-center">
            
            <!-- Bagian Kiri: Tulisan dan Branding -->
            <div class="order-1 flex flex-col justify-center gap-2">
                <a href="{{ route('login') }}">
                    <button class="w-max text-[#10ECE8] hover:text-white flex items-center gap-2 mb-6 transition px-4 py-2 border border-[#14B8A6]/30 rounded-lg hover:bg-[#14B8A6]/10">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Kembali
                    </button>
                </a>
                <div class="flex items-center">
                    <x-application-logo class="-ml-2 h-20 w-auto" />
                </div>
                <p class="text-sm lg:text-lg text-slate-400 leading-relaxed mb-6 lg:mb-10 max-w-lg hidden sm:block">
                    Sistem informasi reservasi fasilitas terpadu. Kami menyederhanakan alur birokrasi peminjaman ruang kelas, aula, dan laboratorium yang sebelumnya memakan waktu.
                </p>

                <div class="hidden sm:flex items-center gap-4 mt-4 lg:mt-8 pt-4 lg:pt-8 border-t border-white/5">
                    <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-[#14B8A6]/10 border border-[#14B8A6]/30 flex justify-center items-center">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6 text-[#14B8A6]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-white">Pemulihan Password Aman</div>
                        <div class="text-xs text-slate-500">Verifikasi melalui email terdaftar</div>
                    </div>
                </div>
            </div>

            <!-- Bagian Kanan: Form Lupa Password -->
            <div class="order-2 w-full max-w-md mx-auto lg:ml-auto lg:mr-0 z-10">
                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <div class="bg-[#111] border border-white/5 hover:border-[#14B8A6]/20 transition rounded-3xl p-6 sm:p-8 lg:p-10 shadow-[0_0_50px_rgba(0,0,0,0.4)] backdrop-blur-sm">
                    <div class="text-left mb-6 lg:mb-8 flex flex-col">
                        <h2 class="text-2xl lg:text-3xl font-extrabold text-white mb-1 lg:mb-2 tracking-tight">Lupa Password?</h2>
                        <p class="text-xs lg:text-sm text-slate-400">Kami akan mengirimkan link reset ke email Anda.</p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}" class="space-y-4 lg:space-y-5">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-[10px] lg:text-[11px] uppercase tracking-wider font-bold text-[#14B8A6] mb-1.5 hover:text-[#10ECE8] transition-colors cursor-pointer">Email Terdaftar</label>
                            <input 
                                id="email" 
                                type="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="email"
                                class="w-full px-4 py-3 bg-[#0a0a0a] border border-white/10 rounded-xl text-white text-sm focus:border-[#14B8A6] focus:outline-none focus:ring-1 focus:ring-[#14B8A6] transition placeholder-slate-600"
                                placeholder="nama@example.com"
                            />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Info Box -->
                        <div class="hidden lg:flex bg-[#14B8A6]/5 border border-[#14B8A6]/20 rounded-xl p-4 gap-3 mt-4">
                            <div class="text-[#14B8A6] shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <p class="text-[10px] text-slate-400 leading-relaxed">
                                Link reset password berlaku selama <span class="font-semibold text-white">60 menit</span>. Periksa folder <span class="font-semibold text-white">Spam</span> jika tidak menerima email.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-6 lg:mt-8 pt-4 lg:pt-6 border-t border-white/5">
                            <button 
                                type="submit" 
                                class="px-6 lg:px-8 py-2.5 lg:py-3 bg-gradient-to-r from-[#14B8A6] to-[#10ECE8] hover:from-[#10ECE8] hover:to-[#14B8A6] text-white font-bold text-sm lg:text-base rounded-xl transition-all shadow-lg hover:shadow-[#14B8A6]/30 hover:shadow-xl"
                            >
                                Kirim Link Reset
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
