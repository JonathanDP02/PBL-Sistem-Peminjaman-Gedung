<x-app-layout title="Organisasi Unit">
    <div class="relative px-8 pt-4 pb-8 space-y-6 z-10 flex flex-col min-h-full transition-colors duration-300"
         x-data="unitManager()"
         x-init="init()">

        {{-- Flash / Error Alerts --}}
        <div x-show="flashMessage" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 p-4 rounded-xl text-sm font-bold flex items-center gap-3">
            <i class="ph-fill ph-check-circle text-xl shrink-0"></i>
            <span x-text="flashMessage"></span>
        </div>
        <div x-show="errorMessage" x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="bg-red-500/10 border border-red-500/20 text-red-600 dark:text-red-400 p-4 rounded-xl text-sm font-bold flex items-center gap-3">
            <i class="ph-fill ph-warning text-xl shrink-0"></i>
            <span x-text="errorMessage"></span>
        </div>

        {{-- Page Header --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
            <div>
                <span class="px-2 py-1 bg-teal-50 dark:bg-kinetic-primary/10 text-teal-700 dark:text-kinetic-primary border border-teal-200 dark:border-kinetic-primary/20 rounded text-[10px] font-bold uppercase tracking-wider mb-3 inline-block">
                    Master Data
                </span>
                <h2 class="font-heading text-3xl font-extrabold text-slate-900 dark:text-white mb-2">Organisasi Unit</h2>
                <p class="text-sm text-slate-500 dark:text-gray-400 max-w-xl leading-relaxed">
                    Kelola struktur hierarki mulai dari Pusat, Jurusan, hingga Organisasi. Klik ikon <i class="ph ph-caret-right"></i> untuk membuka cabang.
                </p>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            {{-- Left: Hierarchical Tree --}}
            <div class="lg:col-span-8 bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 md:p-8 shadow-sm dark:shadow-none flex flex-col">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-heading text-lg font-bold text-slate-900 dark:text-white">Struktur Hierarki</h3>
                    <button @click="loadUnits()" title="Refresh data"
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-kinetic-primary hover:bg-kinetic-primary/10 transition">
                        <i class="ph-bold ph-arrows-clockwise text-lg" :class="{ 'animate-spin': loading && units.length > 0 }"></i>
                    </button>
                </div>

                {{-- Loading skeleton --}}
                <div x-show="loading && units.length === 0" class="space-y-4">
                    <template x-for="i in 3" :key="i">
                        <div class="animate-pulse bg-slate-100 dark:bg-[#1A1A1A] rounded-xl h-16"></div>
                    </template>
                </div>

                {{-- Empty state --}}
                <div x-show="!loading && units.length === 0" class="p-8 text-center text-slate-500 italic border border-dashed border-slate-300 dark:border-[#2A2A2A] rounded-xl">
                    <i class="ph ph-tree-structure text-4xl text-slate-300 dark:text-gray-600 mb-3 block"></i>
                    Belum ada struktur organisasi. Silakan buat Unit Pusat terlebih dahulu.
                </div>

                {{-- Tree --}}
                <div class="space-y-4 relative ml-2" x-show="units.length > 0">
                    <template x-for="pusat in units" :key="pusat.id">
                        <div>
                            {{-- Pusat Row --}}
                            <div class="relative bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between border-l-4 border-l-kinetic-primary shadow-sm dark:shadow-none z-10">
                                <div class="flex items-center gap-3">
                                    <button @click="toggleNode(pusat.id)"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                                            :class="(pusat.children && pusat.children.length > 0)
                                                ? 'text-kinetic-primary hover:bg-kinetic-primary/10 cursor-pointer'
                                                : 'text-slate-300 dark:text-gray-700 cursor-default'">
                                        <i class="ph-bold text-sm transition-transform duration-200"
                                           :class="isOpen(pusat.id) ? 'ph-caret-down' : 'ph-caret-right'"></i>
                                    </button>
                                    <i class="ph-fill ph-bank text-2xl text-kinetic-primary"></i>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5" x-text="pusat.unit_name"></h4>
                                        <p class="text-[10px] text-slate-500 uppercase tracking-wider">
                                            Pusat &bull; <span x-text="pusat.description || 'Unit Utama'"></span>
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <span class="px-2.5 py-1 bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] text-slate-500 dark:text-gray-400 rounded-md text-[10px] font-bold"
                                          x-text="(pusat.children ? pusat.children.length : 0) + ' Sub'"></span>
                                    <button @click="editUnit(pusat)"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-kinetic-primary/10 text-slate-400 hover:text-kinetic-primary transition"
                                            title="Edit unit">
                                        <i class="ph ph-pencil-simple text-sm"></i>
                                    </button>
                                    <button @click="deleteUnit(pusat)"
                                            class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-red-500/10 text-slate-400 hover:text-red-500 transition"
                                            title="Hapus unit">
                                        <i class="ph ph-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Children of Pusat (Jurusan OR Organisasi langsung di bawah Pusat) --}}
                            <div x-show="isOpen(pusat.id) && pusat.children && pusat.children.length > 0"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="relative ml-8 pb-2 mt-3">
                                <div class="absolute -left-6 top-0 bottom-4 w-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>

                                <template x-for="child in (pusat.children || [])" :key="child.id">
                                    <div class="relative mb-3">
                                        <div class="absolute -left-6 top-6 w-6 h-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>

                                        {{-- Jurusan Row (jika level === 'Jurusan') --}}
                                        <div x-show="child.level === 'Jurusan'"
                                             class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-4 flex items-center justify-between z-10 relative">
                                            <div class="flex items-center gap-3">
                                                <button @click="toggleNode(child.id)"
                                                        class="w-7 h-7 flex items-center justify-center rounded-lg transition"
                                                        :class="(child.children && child.children.length > 0)
                                                            ? 'text-cyan-500 hover:bg-cyan-500/10 cursor-pointer'
                                                            : 'text-slate-300 dark:text-gray-700 cursor-default'">
                                                    <i class="ph-bold text-sm"
                                                       :class="isOpen(child.id) ? 'ph-caret-down' : 'ph-caret-right'"></i>
                                                </button>
                                                <i class="ph-fill ph-buildings text-xl text-cyan-500"></i>
                                                <div>
                                                    <h4 class="text-sm font-bold text-slate-900 dark:text-white mb-0.5" x-text="child.unit_name"></h4>
                                                    <p class="text-[10px] text-slate-500 uppercase tracking-wider">
                                                        Jurusan &bull; <span x-text="child.description || 'Sub Unit'"></span>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                <span class="px-2.5 py-1 bg-slate-100 dark:bg-[#222] text-slate-500 dark:text-gray-400 rounded-md text-[10px] font-bold"
                                                      x-text="'+' + (child.children ? child.children.length : 0) + ' Org'"></span>
                                                <button @click="editUnit(child)"
                                                        class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-kinetic-primary/10 text-slate-400 hover:text-kinetic-primary transition">
                                                    <i class="ph ph-pencil-simple text-sm"></i>
                                                </button>
                                                <button @click="deleteUnit(child)"
                                                        class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-red-500/10 text-slate-400 hover:text-red-500 transition">
                                                    <i class="ph ph-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Organisasi Row langsung di bawah Pusat (jika level === 'Organisasi') --}}
                                        <div x-show="child.level === 'Organisasi'"
                                             class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-3 flex items-center justify-between z-10 relative group">
                                            <div class="flex items-center gap-3">
                                                <button @click="toggleNode(child.id)"
                                                        class="w-6 h-6 flex items-center justify-center rounded-md transition"
                                                        :class="(child.children && child.children.length > 0)
                                                            ? 'text-teal-500 hover:bg-teal-500/10 cursor-pointer'
                                                            : 'text-slate-300 dark:text-gray-700 cursor-default'">
                                                    <i class="ph-bold text-xs"
                                                       :class="isOpen(child.id) ? 'ph-caret-down' : 'ph-caret-right'"></i>
                                                </button>
                                                <span class="w-2 h-2 rounded-full bg-teal-500 dark:bg-kinetic-primary shrink-0"></span>
                                                <div>
                                                    <h4 class="text-xs font-bold text-slate-700 dark:text-gray-300 group-hover:text-kinetic-primary transition-colors" x-text="child.unit_name"></h4>
                                                    <p class="text-[9px] text-slate-400 dark:text-gray-600 uppercase tracking-wider" x-text="child.description ? 'Organisasi (Pusat) • ' + child.description : 'Organisasi (Pusat)'"></p>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-1.5 relative z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <button @click="editUnit(child)"
                                                        class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-kinetic-primary/10 text-slate-400 hover:text-kinetic-primary transition">
                                                    <i class="ph ph-pencil-simple text-xs"></i>
                                                </button>
                                                <button @click="deleteUnit(child)"
                                                        class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-red-500/10 text-slate-400 hover:text-red-500 transition">
                                                    <i class="ph ph-trash text-xs"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Sub-children (Organisasi di bawah Jurusan, atau Sub-Org di bawah Org) --}}
                                        <div x-show="isOpen(child.id) && child.children && child.children.length > 0"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 -translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             class="ml-10 mt-2 relative">
                                            <div class="absolute -left-6 top-0 bottom-4 w-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>

                                            <template x-for="org in (child.children || [])" :key="org.id">
                                                <div class="relative mb-3">
                                                    <div class="absolute -left-6 top-6 w-6 h-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>

                                                    {{-- Organisasi Row --}}
                                                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl p-3 flex items-center justify-between z-10 relative group">
                                                        <div class="flex items-center gap-3">
                                                            <button @click="toggleNode(org.id)"
                                                                    class="w-6 h-6 flex items-center justify-center rounded-md transition"
                                                                    :class="(org.children && org.children.length > 0)
                                                                        ? 'text-teal-500 hover:bg-teal-500/10 cursor-pointer'
                                                                        : 'text-slate-300 dark:text-gray-700 cursor-default'">
                                                                <i class="ph-bold text-xs"
                                                                   :class="isOpen(org.id) ? 'ph-caret-down' : 'ph-caret-right'"></i>
                                                            </button>
                                                            <span class="w-2 h-2 rounded-full bg-teal-500 dark:bg-kinetic-primary shrink-0"></span>
                                                            <div>
                                                                <h4 class="text-xs font-bold text-slate-700 dark:text-gray-300 group-hover:text-kinetic-primary transition-colors" x-text="org.unit_name"></h4>
                                                                <p class="text-[9px] text-slate-400 dark:text-gray-600 uppercase tracking-wider" x-text="org.description ? 'Organisasi • ' + org.description : 'Organisasi'"></p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-1.5 relative z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <button @click="editUnit(org)"
                                                                    class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-kinetic-primary/10 text-slate-400 hover:text-kinetic-primary transition">
                                                                <i class="ph ph-pencil-simple text-xs"></i>
                                                            </button>
                                                            <button @click="deleteUnit(org)"
                                                                    class="w-6 h-6 flex items-center justify-center rounded-md hover:bg-red-500/10 text-slate-400 hover:text-red-500 transition">
                                                                <i class="ph ph-trash text-xs"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    {{-- Sub-Organisasi Level --}}
                                                    <div x-show="isOpen(org.id) && org.children && org.children.length > 0"
                                                         x-transition:enter="transition ease-out duration-200"
                                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                                         x-transition:enter-end="opacity-100 translate-y-0"
                                                         class="ml-8 mt-2 relative">
                                                        <div class="absolute -left-6 top-0 bottom-4 w-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>

                                                        <template x-for="subOrg in (org.children || [])" :key="subOrg.id">
                                                            <div class="relative flex items-center justify-between py-2 group">
                                                                <div class="absolute -left-6 top-1/2 w-6 h-px bg-slate-200 dark:bg-[#2A2A2A] z-0"></div>
                                                                <div class="flex items-center gap-3 relative z-10">
                                                                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 group-hover:scale-150 transition-transform shrink-0"></span>
                                                                    <div>
                                                                        <h4 class="text-[11px] font-bold text-slate-600 dark:text-gray-400 group-hover:text-kinetic-primary transition-colors" x-text="subOrg.unit_name"></h4>
                                                                        <p class="text-[8px] text-slate-400 dark:text-gray-600 uppercase tracking-wider" x-text="subOrg.description ? 'Sub-Organisasi • ' + subOrg.description : 'Sub-Organisasi'"></p>
                                                                    </div>
                                                                </div>
                                                                <div class="flex items-center gap-1.5 relative z-10 opacity-0 group-hover:opacity-100 transition-opacity">
                                                                    <button @click="editUnit(subOrg)"
                                                                            class="w-5 h-5 flex items-center justify-center rounded-md hover:bg-kinetic-primary/10 text-slate-400 hover:text-kinetic-primary transition">
                                                                        <i class="ph ph-pencil-simple text-[10px]"></i>
                                                                    </button>
                                                                    <button @click="deleteUnit(subOrg)"
                                                                            class="w-5 h-5 flex items-center justify-center rounded-md hover:bg-red-500/10 text-slate-400 hover:text-red-500 transition">
                                                                        <i class="ph ph-trash text-[10px]"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Right: Form Panel --}}
            <div class="lg:col-span-4 space-y-5" data-form-panel>

                <div class="bg-slate-50 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-6 md:p-8 shadow-sm dark:shadow-none">
                    {{-- Form Header --}}
                    <div class="flex items-center gap-4 mb-7">
                        <div class="w-10 h-10 rounded-xl bg-white dark:bg-[#222] border border-slate-200 dark:border-[#333] flex items-center justify-center text-kinetic-primary shrink-0">
                            <i class="ph-bold text-xl" :class="formMode === 'edit' ? 'ph-pencil-simple' : 'ph-plus-square'"></i>
                        </div>
                        <div>
                            <h3 class="font-heading font-bold text-slate-900 dark:text-white"
                                x-text="formMode === 'edit' ? 'Edit Unit' : 'Tambah Unit'"></h3>
                            <p class="text-[10px] text-slate-500 dark:text-gray-400 leading-tight mt-0.5"
                               x-text="formMode === 'edit' ? 'Perbarui informasi unit yang dipilih' : 'Tambahkan unit baru ke dalam hierarki'"></p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        {{-- Nama Unit --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nama Unit</label>
                            <input type="text" x-model="form.unit_name"
                                   placeholder="Contoh: Jurusan Teknologi Informasi"
                                   class="w-full bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors">
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Kode / Deskripsi</label>
                            <input type="text" x-model="form.description"
                                   placeholder="Contoh: JTI-01 (opsional)"
                                   class="w-full bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl px-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-gray-600 focus:outline-none focus:border-kinetic-primary transition-colors">
                        </div>

                        {{-- Level Selector --}}
                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Tipe / Level Unit</label>
                            <div class="grid grid-cols-3 gap-2">
                                {{-- Pusat --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="level" value="Pusat"
                                           x-model="form.level" @change="onLevelChange()"
                                           class="peer hidden">
                                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] peer-checked:bg-teal-50 dark:peer-checked:bg-[#1A2624] peer-checked:border-kinetic-primary rounded-xl py-3 flex flex-col items-center justify-center gap-1 transition-colors">
                                        <i class="ph-fill ph-bank text-slate-400 text-lg transition-colors" :class="form.level === 'Pusat' ? 'text-kinetic-primary' : ''"></i>
                                        <span class="text-[10px] font-bold text-slate-600 dark:text-gray-400" :class="form.level === 'Pusat' ? 'text-slate-900 dark:text-white' : ''">Pusat</span>
                                    </div>
                                </label>
                                {{-- Jurusan --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="level" value="Jurusan"
                                           x-model="form.level" @change="onLevelChange()"
                                           class="peer hidden">
                                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] peer-checked:bg-teal-50 dark:peer-checked:bg-[#1A2624] peer-checked:border-kinetic-primary rounded-xl py-3 flex flex-col items-center justify-center gap-1 transition-colors">
                                        <i class="ph-fill ph-buildings text-slate-400 text-lg transition-colors" :class="form.level === 'Jurusan' ? 'text-kinetic-primary' : ''"></i>
                                        <span class="text-[10px] font-bold text-slate-600 dark:text-gray-400" :class="form.level === 'Jurusan' ? 'text-slate-900 dark:text-white' : ''">Jurusan</span>
                                    </div>
                                </label>
                                {{-- Organisasi --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="level" value="Organisasi"
                                           x-model="form.level" @change="onLevelChange()"
                                           class="peer hidden">
                                    <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] peer-checked:bg-teal-50 dark:peer-checked:bg-[#1A2624] peer-checked:border-kinetic-primary rounded-xl py-3 flex flex-col items-center justify-center gap-1 transition-colors">
                                        <i class="ph-fill ph-users-three text-slate-400 text-lg transition-colors" :class="form.level === 'Organisasi' ? 'text-kinetic-primary' : ''"></i>
                                        <span class="text-[10px] font-bold text-slate-600 dark:text-gray-400" :class="form.level === 'Organisasi' ? 'text-slate-900 dark:text-white' : ''">Organisasi</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Parent Dropdown (hidden for Pusat) --}}
                        <div x-show="form.level !== 'Pusat'"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0">
                            <label class="block text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">
                                Unit Induk
                                <span x-text="form.level === 'Jurusan' ? '(Pilih Pusat)' : '(Pilih Pusat atau Jurusan)'"></span>
                            </label>
                            <div class="relative">
                                <select x-model="form.parent_id"
                                        class="w-full bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-xl pl-4 pr-10 py-3.5 text-sm text-slate-900 dark:text-white focus:outline-none focus:border-kinetic-primary transition-colors appearance-none cursor-pointer">
                                    <option value="">-- Pilih Unit Induk --</option>
                                    <template x-for="p in filteredParents" :key="p.id">
                                        <option :value="String(p.id)" x-text="p.unit_name"></option>
                                    </template>
                                </select>
                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-xs"></i>
                            </div>
                            <p x-show="filteredParents.length === 0" class="text-[10px] text-amber-500 mt-1.5">
                                <i class="ph ph-warning"></i>
                                Belum ada unit induk yang tersedia. Buat terlebih dahulu.
                            </p>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-3 flex gap-3">
                            <button x-show="formMode === 'edit'" @click="cancelEdit()"
                                    class="flex-1 bg-slate-200 dark:bg-[#2A2A2A] hover:bg-slate-300 dark:hover:bg-[#333] text-slate-700 dark:text-white font-bold py-3 rounded-xl transition-colors text-sm">
                                Batal
                            </button>
                            <button @click="submitForm()" :disabled="loading"
                                    class="flex-1 bg-kinetic-primary hover:bg-teal-400 disabled:opacity-60 disabled:cursor-not-allowed text-slate-900 font-bold py-3 rounded-xl transition-colors shadow-[0_0_15px_rgba(20,184,166,0.2)] flex items-center justify-center gap-2 text-sm">
                                <i class="ph ph-spinner animate-spin text-base" x-show="loading"></i>
                                <i class="ph ph-floppy-disk text-base" x-show="!loading"></i>
                                <span x-text="loading ? 'Menyimpan...' : (formMode === 'edit' ? 'Simpan Perubahan' : 'Tambah Unit')"></span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Live Sync Status --}}
                <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-5 shadow-sm dark:shadow-none flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Live Sync Status</p>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">Database Terhubung</h4>
                    </div>
                    <div class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-kinetic-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-kinetic-primary"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 flex flex-col shadow-sm dark:shadow-none transition-colors">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] flex items-center justify-center text-kinetic-primary mb-5">
                    <i class="ph-fill ph-bank text-xl"></i>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1" x-text="stats.pusat">{{ $totalPusat }}</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Total Pusat</p>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 flex flex-col shadow-sm dark:shadow-none transition-colors">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] flex items-center justify-center text-cyan-500 mb-5">
                    <i class="ph-fill ph-buildings text-xl"></i>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1" x-text="stats.jurusan">{{ $totalJurusan }}</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Total Jurusan</p>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 flex flex-col shadow-sm dark:shadow-none transition-colors">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] flex items-center justify-center text-blue-400 mb-5">
                    <i class="ph-fill ph-users-three text-xl"></i>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1" x-text="stats.organisasi">{{ $totalOrganisasi }}</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Total Organisasi</p>
            </div>
            <div class="bg-white dark:bg-[#151515] border border-slate-200 dark:border-[#2A2A2A] rounded-2xl p-6 flex flex-col shadow-sm dark:shadow-none transition-colors">
                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] flex items-center justify-center text-slate-400 dark:text-gray-300 mb-5">
                    <i class="ph-fill ph-stack text-xl"></i>
                </div>
                <h2 class="font-heading text-4xl font-extrabold text-slate-900 dark:text-white mb-1" x-text="stats.total">{{ $totalUnit }}</h2>
                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Total Keseluruhan</p>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div x-show="showDeleteModal"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm"
             @click.self="showDeleteModal = false">
            <div class="bg-white dark:bg-[#1A1A1A] border border-slate-200 dark:border-[#2A2A2A] rounded-3xl p-8 max-w-sm w-full mx-4 shadow-2xl"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">
                <div class="w-14 h-14 rounded-2xl bg-red-500/10 flex items-center justify-center mb-5 mx-auto">
                    <i class="ph-fill ph-warning text-3xl text-red-500"></i>
                </div>
                <h3 class="font-heading text-xl font-bold text-slate-900 dark:text-white mb-2 text-center">Hapus Unit?</h3>
                <p class="text-sm text-slate-500 dark:text-gray-400 mb-6 text-center leading-relaxed">
                    Unit "<span x-text="deletingUnit ? deletingUnit.unit_name : ''" class="font-bold text-slate-800 dark:text-white"></span>" akan dihapus secara permanen. 
                    <span class="text-red-500 font-medium block mt-2">Peringatan: Seluruh data yang berkaitan dengan unit ini (pengguna, posisi, ruangan, alur kerja, dan pemesanan) akan ikut terhapus secara permanen. Hal ini dapat merusak jalannya sistem jika terdapat keterkaitan penting.</span>
                </p>
                <div class="flex gap-3">
                    <button @click="showDeleteModal = false"
                            class="flex-1 py-3 rounded-xl border border-slate-200 dark:border-[#2A2A2A] text-slate-600 dark:text-gray-400 font-bold hover:bg-slate-50 dark:hover:bg-[#222] transition text-sm">
                        Batal
                    </button>
                    <button @click="confirmDelete()" :disabled="loading"
                            class="flex-1 py-3 rounded-xl bg-red-500 hover:bg-red-600 disabled:opacity-60 text-white font-bold transition flex items-center justify-center gap-2 text-sm">
                        <i class="ph ph-spinner animate-spin" x-show="loading"></i>
                        <span x-text="loading ? 'Menghapus...' : 'Ya, Hapus'"></span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        function unitManager() {
            return {
                // State
                loading: false,
                flashMessage: null,
                errorMessage: null,
                showDeleteModal: false,
                deletingUnit: null,

                // Form
                formMode: 'add',
                editingUnit: null,
                form: { unit_name: '', description: '', parent_id: '', level: 'Pusat' },

                // Data
                units: [],
                allUnits: [],
                filteredParents: [],
                openNodes: {},

                // Stats (server-side initial values)
                stats: {
                    pusat: {{ $totalPusat }},
                    jurusan: {{ $totalJurusan }},
                    organisasi: {{ $totalOrganisasi }},
                    total: {{ $totalUnit }},
                },

                async init() {
                    await this.loadUnits();
                },

                async loadUnits() {
                    this.loading = true;
                    try {
                        const resp = await fetch('/admin/api/units', {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });
                        const json = await resp.json();
                        if (json.success) {
                            const flat = json.data || [];
                            this.allUnits = flat;
                            this.units = this.buildTree(flat);
                            this.updateStats(flat);
                        }
                    } catch (e) {
                        console.error('Load units failed:', e);
                    } finally {
                        this.loading = false;
                    }
                },

                buildTree(flat) {
                    const map = {};
                    flat.forEach(u => {
                        map[u.id] = { ...u, children: [] };
                        // Initialize open state (preserve existing toggle state)
                        if (this.openNodes[u.id] === undefined) {
                            this.openNodes[u.id] = true;
                        }
                    });
                    const roots = [];
                    flat.forEach(u => {
                        if (u.parent_id && map[u.parent_id]) {
                            map[u.parent_id].children.push(map[u.id]);
                        } else if (!u.parent_id) {
                            roots.push(map[u.id]);
                        }
                    });
                    return roots;
                },

                updateStats(flat) {
                    this.stats.pusat = flat.filter(u => u.level === 'Pusat').length;
                    this.stats.jurusan = flat.filter(u => u.level === 'Jurusan').length;
                    this.stats.organisasi = flat.filter(u => u.level === 'Organisasi').length;
                    this.stats.total = flat.length;
                },

                toggleNode(id) {
                    this.openNodes[id] = !(this.openNodes[id] ?? true);
                },

                isOpen(id) {
                    return this.openNodes[id] ?? true;
                },

                onLevelChange() {
                    this.form.parent_id = '';
                    const excludeId = this.editingUnit ? this.editingUnit.id : null;
                    if (this.form.level === 'Jurusan') {
                        this.filteredParents = this.allUnits.filter(u => u.level === 'Pusat' && u.id !== excludeId);
                    } else if (this.form.level === 'Organisasi') {
                        // Parents can be: Pusat (Level 1), Jurusan (Level 2), or Organisasi Level-3 (not sub-org)
                        this.filteredParents = this.allUnits.filter(u => {
                            if (u.id === excludeId) return false;
                            if (u.level === 'Pusat') return true;
                            if (u.level === 'Jurusan') return true;
                            if (u.level === 'Organisasi') {
                                // Find parent of u — if u's parent is also Organisasi, it's a Level-4 Sub-Org (exclude)
                                const parent = this.allUnits.find(p => p.id === u.parent_id);
                                return !parent || parent.level !== 'Organisasi';
                            }
                            return false;
                        });
                    } else {
                        this.filteredParents = [];
                    }
                },

                editUnit(unit) {
                    this.formMode = 'edit';
                    this.editingUnit = unit;
                    this.form = {
                        unit_name: unit.unit_name,
                        description: unit.description || '',
                        parent_id: unit.parent_id ? String(unit.parent_id) : '',
                        level: unit.level,
                    };
                    this.onLevelChange();
                    // Restore correct parent_id after onLevelChange reset it
                    if (unit.parent_id) {
                        this.form.parent_id = String(unit.parent_id);
                    }
                    // Scroll to form panel
                    const panel = document.querySelector('[data-form-panel]');
                    if (panel) panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                },

                cancelEdit() {
                    this.formMode = 'add';
                    this.editingUnit = null;
                    this.form = { unit_name: '', description: '', parent_id: '', level: 'Pusat' };
                    this.filteredParents = [];
                    this.clearMessages();
                },

                async submitForm() {
                    // Validation
                    if (!this.form.unit_name.trim()) {
                        this.showError('Nama unit tidak boleh kosong.');
                        return;
                    }
                    if (!this.form.level) {
                        this.showError('Level unit wajib dipilih.');
                        return;
                    }
                    if (this.form.level !== 'Pusat' && !this.form.parent_id) {
                        this.showError('Unit ' + this.form.level + ' harus memilih unit induk terlebih dahulu.');
                        return;
                    }

                    this.loading = true;
                    this.clearMessages();

                    const isEdit = this.formMode === 'edit';
                    const url = isEdit ? `/admin/api/units/${this.editingUnit.id}` : '/admin/api/units';
                    const method = isEdit ? 'PUT' : 'POST';

                    try {
                        const resp = await fetch(url, {
                            method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                unit_name: this.form.unit_name.trim(),
                                description: this.form.description.trim() || null,
                                parent_id: this.form.parent_id || null,
                                level: this.form.level,
                            }),
                        });

                        const json = await resp.json();

                        if (json.success) {
                            this.showSuccess(json.message || 'Unit berhasil disimpan.');
                            this.cancelEdit();
                            await this.loadUnits();
                        } else {
                            const errors = json.errors
                                ? Object.values(json.errors).flat().join(', ')
                                : json.message || 'Terjadi kesalahan.';
                            this.showError(errors);
                        }
                    } catch (e) {
                        this.showError('Gagal terhubung ke server.');
                    } finally {
                        this.loading = false;
                    }
                },

                deleteUnit(unit) {
                    this.deletingUnit = unit;
                    this.showDeleteModal = true;
                },

                async confirmDelete() {
                    if (!this.deletingUnit) return;
                    this.loading = true;
                    try {
                        const resp = await fetch(`/admin/api/units/${this.deletingUnit.id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                        });
                        const json = await resp.json();
                        this.showDeleteModal = false;
                        this.deletingUnit = null;
                        if (json.success) {
                            this.showSuccess(json.message || 'Unit berhasil dihapus.');
                            await this.loadUnits();
                        } else {
                            this.showError(json.message || 'Gagal menghapus unit.');
                        }
                    } catch (e) {
                        this.showDeleteModal = false;
                        this.showError('Gagal menghapus unit.');
                    } finally {
                        this.loading = false;
                    }
                },

                showSuccess(msg) {
                    this.flashMessage = msg;
                    this.errorMessage = null;
                    setTimeout(() => { this.flashMessage = null; }, 5000);
                },

                showError(msg) {
                    this.errorMessage = msg;
                    this.flashMessage = null;
                    setTimeout(() => { this.errorMessage = null; }, 7000);
                },

                clearMessages() {
                    this.flashMessage = null;
                    this.errorMessage = null;
                },
            };
        }
    </script>
</x-app-layout>