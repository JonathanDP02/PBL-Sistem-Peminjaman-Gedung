<x-app-layout title="Manajemen Akun">
    <div class="relative px-8 pt-4 pb-8 space-y-6 z-10 flex flex-col min-h-full transition-colors duration-300">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
            <div>
                <h2 class="font-heading text-3xl md:text-4xl font-extrabold text-slate-900 dark:text-white mb-2">Manajemen Akun</h2>
                <p class="text-sm text-slate-500 dark:text-gray-400 max-w-xl leading-relaxed">
                    Kelola seluruh otorisasi pengguna, peran akses, dan status keanggotaan dalam ekosistem Space.in Enterprise.
                </p>
            </div>
            <button onclick="openUserModal()" class="bg-kinetic-primary hover:bg-teal-400 text-slate-900 font-bold px-5 py-2.5 rounded-xl flex items-center gap-2 transition shadow-[0_0_15px_rgba(20,184,166,0.3)] shrink-0">
                <i class="ph-bold ph-user-plus text-lg"></i> Tambah Pengguna Baru
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Total Pengguna</p>
                <div class="flex items-end gap-2">
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">2,842</h2>
                    <span class="text-xs font-bold text-teal-600 dark:text-kinetic-primary mb-0.5">+12%</span>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Super Admin</p>
                <div class="flex items-end gap-2">
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">5</h2>
                    <span class="text-xs font-medium text-slate-500 dark:text-gray-400 mb-0.5">Global</span>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Unit Admin</p>
                <div class="flex items-end gap-2">
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">48</h2>
                    <span class="text-xs font-medium text-slate-500 dark:text-gray-400 mb-0.5">Tersebar</span>
                </div>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex flex-col transition-colors">
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Aktif Sekarang</p>
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-kinetic-primary animate-pulse"></span>
                    <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white leading-none">156</h2>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl shadow-sm dark:shadow-none overflow-hidden transition-colors">
            
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[900px]">
                    <thead>
                        <tr class="border-b border-slate-200 dark:border-[#2A2A2A] bg-slate-50/50 dark:bg-[#111]">
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Nama Lengkap</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">ID/NIM/NIP</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Email</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Unit</th>
                            <th class="px-6 py-5 text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Role</th>
                        </tr>
                    </thead>
                    
                    <tbody class="divide-y divide-slate-100 dark:divide-[#1E1E1E]">
                        
                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group cursor-pointer">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-teal-50 dark:bg-[#0D2A27] text-teal-600 dark:text-kinetic-primary flex items-center justify-center text-sm font-bold border border-teal-100 dark:border-teal-900/50">
                                        AS
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Aditya Saputra</h4>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-gray-300 font-medium">19051204044</td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-gray-400">aditya.sap@univ.ac.id</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 dark:bg-[#222] text-slate-600 dark:text-gray-300 rounded-full text-xs font-medium border border-slate-200 dark:border-[#333]">
                                    FTI - Informatika
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-teal-600 dark:text-kinetic-primary">
                                    <i class="ph-fill ph-shield-star text-base"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider">Super Admin</span>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group cursor-pointer">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full overflow-hidden border border-slate-200 dark:border-[#333]">
                                        <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80&w=100" alt="Avatar" class="w-full h-full object-cover">
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Rina Wijaya</h4>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-gray-300 font-medium">19780512200501</td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-gray-400">rina.wijaya@univ.ac.id</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 dark:bg-[#222] text-slate-600 dark:text-gray-300 rounded-full text-xs font-medium border border-slate-200 dark:border-[#333]">
                                    Biro Kemahasiswaan
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-cyan-600 dark:text-kinetic-tertiary">
                                    <i class="ph-fill ph-users text-base"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider">Unit Admin</span>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group cursor-pointer">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 dark:bg-[#222] text-slate-500 dark:text-gray-400 flex items-center justify-center text-sm font-bold border border-slate-200 dark:border-[#333]">
                                        BP
                                    </div>
                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white">Budi Pratama</h4>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 dark:text-gray-300 font-medium">21051204012</td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-gray-400">budi.p@student.univ.ac.id</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-100 dark:bg-[#222] text-slate-600 dark:text-gray-300 rounded-full text-xs font-medium border border-slate-200 dark:border-[#333]">
                                    FTI - Sistem Informasi
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-slate-500 dark:text-gray-500">
                                    <i class="ph-fill ph-graduation-cap text-base"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider">Mahasiswa</span>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-slate-50 dark:hover:bg-[#1A1A1A] transition-colors group cursor-pointer opacity-60 hover:opacity-100">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-[#2A1515] text-red-500 flex items-center justify-center text-sm font-bold border border-red-100 dark:border-red-900/30">
                                        SN
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-700 dark:text-gray-400">Siti Nurhaliza</h4>
                                        <span class="text-[10px] text-red-500 dark:text-red-400">(Nonaktif)</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-gray-500 font-medium">21051204099</td>
                            <td class="px-6 py-4 text-sm text-slate-400 dark:text-gray-600">siti.nur@student.univ.ac.id</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-slate-50 dark:bg-[#1A1A1A] text-slate-500 dark:text-gray-500 rounded-full text-xs font-medium border border-slate-200 dark:border-[#2A2A2A]">
                                    FEB - Manajemen
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-1.5 text-slate-400 dark:text-gray-600">
                                    <i class="ph-fill ph-graduation-cap text-base"></i>
                                    <span class="text-xs font-bold uppercase tracking-wider">Mahasiswa</span>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 dark:border-[#2A2A2A] px-6 py-4 flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-[#151515]">
                <p class="text-xs text-slate-500 dark:text-gray-400">
                    Menampilkan <span class="font-bold text-slate-900 dark:text-white">1-10</span> dari <span class="font-bold text-slate-900 dark:text-white">2,842</span> pengguna
                </p>
                <div class="flex items-center gap-1 text-sm">
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-[#222] transition-colors">
                        <i class="ph-bold ph-caret-left"></i>
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-kinetic-primary text-slate-900 font-bold transition-colors">
                        1
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-[#222] font-medium transition-colors">
                        2
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-600 dark:text-gray-300 hover:bg-slate-100 dark:hover:bg-[#222] font-medium transition-colors">
                        3
                    </button>
                    <button class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-[#222] transition-colors">
                        <i class="ph-bold ph-caret-right"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>
    @include('admin.modal-tambah-user')
</x-app-layout>