<x-guest-layout>
<section class="py-32 relative min-h-screen" x-data="roomSelection()">
    <div class="w-full max-w-[1200px] mx-auto px-4">
        
        <!-- STEP 1: PILIH GEDUNG -->
        <div x-show="!selectedBuilding" class="w-full flex flex-col gap-10 animate-[fadeIn_0.5s_ease-out]">
            <div class="text-left w-full flex items-center mb-6">
                <svg class="w-6 h-6 text-[#14B8A6] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                <h2 class="text-3xl font-bold text-white">Gedung <span class="text-[#14B8A6]">Tersedia</span></h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
                <!-- Gedung Loop -->
                <template x-for="(b, index) in buildings" :key="b.id">
                    <div @click="selectBuilding(b.id)" class="group relative overflow-hidden rounded-2xl bg-[#0a0a0a] border border-[#14B8A6]/20 cursor-pointer transition-all duration-300 hover:border-[#14B8A6]/60 hover:shadow-[0_0_30px_rgba(20,184,166,0.15)] flex h-[350px]">
                        <div class="absolute inset-0 transition-transform duration-700 group-hover:scale-105 bg-gradient-to-br from-[#0c2423] to-[#0a0a0a]" x-bind:style="index === 0 ? 'background-image: radial-gradient(circle at 80% 20%, rgba(20, 184, 166, 0.15) 0%, transparent 50%);' : ''"></div>
                        
                        <div class="relative z-10 p-8 flex flex-col justify-end w-full h-full bg-gradient-to-t from-[#050505] to-transparent">
                            <span x-show="index === 0" class="inline-block px-3 py-1 mb-4 text-xs font-semibold text-[#14B8A6] bg-[#14B8A6]/10 border border-[#14B8A6]/30 rounded-full w-max">LOKASI UTAMA</span>
                            <h3 class="text-3xl font-bold text-white mb-3 group-hover:text-[#10ECE8] transition-colors" x-text="b.building_name"></h3>
                            <p class="text-gray-400 text-sm mb-6 max-w-md" x-text="b.location || 'Fasilitas terbaik kampus'"></p>
                            <button class="w-max px-6 py-2.5 bg-white/10 text-white font-semibold rounded-lg group-hover:bg-[#14B8A6] group-hover:text-black transition">
                                Lihat Ruangan
                            </button>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- STEP 2: DAFTAR RUANGAN DI GEDUNG -->
        <div x-show="selectedBuilding" style="display: none;" class="w-full flex-col gap-8 animate-[fadeIn_0.3s_ease-out]">
            <button @click="selectedBuilding = null" class="w-max text-[#10ECE8] hover:text-white flex items-center gap-2 mb-6 transition px-4 py-2 border border-[#14B8A6]/30 rounded-lg hover:bg-[#14B8A6]/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Pilihan Gedung
            </button>
            
            <div class="mb-8">
                <h2 class="text-4xl font-bold text-white mb-3">Daftar Ruang di <span class="text-[#14B8A6]" x-text="selectedBuilding ? selectedBuilding.building_name : ''"></span></h2>
                <p class="text-gray-400">Pilih salah satu dari ruangan yang tersedia untuk melihat detail dan memesan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 w-full">
                <!-- Ruangan Terkait -->
                <template x-for="r in selectedBuilding ? selectedBuilding.rooms : []" :key="r.id">
                    <div class="bg-[#111] border border-white/5 p-6 rounded-2xl hover:border-[#14B8A6]/50 hover:bg-[#151515] transition cursor-pointer group relative overflow-hidden flex flex-col h-full">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-[#14B8A6] to-[#0DBAF9] opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="p-3 bg-[#1a1a1a] rounded-lg text-[#14B8A6] w-max mb-6 group-hover:bg-[#14B8A6]/10 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        
                        <h3 class="text-xl font-bold text-white mb-2 group-hover:text-[#10ECE8] transition" x-text="r.room_name"></h3>
                        <p class="text-sm text-gray-500 mb-6 flex-grow" x-text="r.description || 'Ruangan ini tersedia untuk peminjaman akademik maupun non-akademik.'"></p>
                        
                        <div class="flex items-center justify-between mt-auto">
                            <span class="text-[10px] font-bold text-gray-500" x-text="'KAPASITAS: ' + r.capacity + ' ORANG'"></span>
                            <a href="{{ route('login') }}" class="text-[#14B8A6] p-2 rounded-full group-hover:bg-[#14B8A6]/10 transition">
                                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </template>

                <!-- Bantuan Card -->
                <div class="bg-gradient-to-br from-[#0c2423] to-[#0A0A0A] border border-[#14B8A6]/20 p-6 rounded-2xl flex flex-col justify-center gap-4 h-full">
                    <h3 class="text-xl font-bold text-[#14B8A6]">Butuh Bantuan?</h3>
                    <p class="text-xs text-gray-400">Tim Sarpras siap membantu instalasi perangkat di ruangan pilihan Anda.</p>
                    <div class="mt-auto flex justify-between items-center w-full">
                        <span class="text-xs text-gray-500">Call Support</span>
                        <div class="w-10 h-10 rounded-full bg-[#14B8A6] text-black flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>

<script>
function roomSelection() {
    return {
        buildings: @json($buildings),
        selectedBuilding: null,
        selectBuilding(id) {
            this.selectedBuilding = this.buildings.find(b => b.id === id);
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
}
</script>
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</x-guest-layout>



