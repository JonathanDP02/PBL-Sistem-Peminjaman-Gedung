<template x-teleport="body">
<div x-show="modal.show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/60 backdrop-blur-sm"
     x-cloak>
    
    <div x-show="modal.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="transform scale-95"
         x-transition:enter-end="transform scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="transform scale-100"
         x-transition:leave-end="transform scale-95"
         :class="{
             'border-red-200 dark:border-red-900/30': modal.type === 'danger',
             'border-amber-200 dark:border-amber-900/30': modal.type === 'warning',
             'border-emerald-200 dark:border-emerald-900/30': modal.type === 'success',
             'border-blue-200 dark:border-blue-900/30': modal.type === 'info'
         }"
         class="bg-white dark:bg-[#151515] border rounded-3xl w-full max-w-sm p-8 relative shadow-2xl">
        
        <button @click="closeModal()" class="absolute top-6 right-6 text-slate-400 hover:text-red-500 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="mb-6 text-center">
            <div :class="{
                'bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400': modal.type === 'danger',
                'bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400': modal.type === 'warning',
                'bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400': modal.type === 'success',
                'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400': modal.type === 'info'
            }" class="inline-flex h-16 w-16 items-center justify-center rounded-full mb-4">
                <!-- Success Icon -->
                <template x-if="modal.type === 'success'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600 dark:text-emerald-450"><polyline points="20 6 9 17 4 12"/></svg>
                </template>
                <!-- Warning/Danger Icon -->
                <template x-if="modal.type === 'warning' || modal.type === 'danger'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" :class="modal.type === 'danger' ? 'text-red-600 dark:text-red-400' : 'text-amber-600 dark:text-amber-400'"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                </template>
                <!-- Info Icon -->
                <template x-if="modal.type === 'info'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-blue-600 dark:text-blue-400"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                </template>
            </div>
            <h3 class="font-heading text-2xl font-bold text-slate-900 dark:text-white mb-2" x-text="modal.title"></h3>
            <p class="text-sm text-slate-500 dark:text-gray-400" x-text="modal.description"></p>
        </div>

        <div class="flex gap-3 pt-4 border-t border-slate-200 dark:border-[#2A2A2A]">
            <button type="button" 
                    x-show="modal.isConfirm"
                    @click="closeModal()" 
                    class="w-1/2 bg-slate-100 dark:bg-[#1A1A1A] text-slate-700 dark:text-white border border-slate-200 dark:border-[#2A2A2A] hover:bg-slate-200 dark:hover:bg-[#222] font-bold py-3 rounded-xl transition-colors text-sm"
                    x-text="modal.cancelText">
            </button>
            <button type="button" 
                    @click="if(modal.onConfirm) { modal.onConfirm(); } closeModal();" 
                    :class="{
                        'w-1/2': modal.isConfirm,
                        'w-full': !modal.isConfirm,
                        'bg-red-600 hover:bg-red-700 shadow-[0_0_15px_rgba(220,38,38,0.2)]': modal.type === 'danger',
                        'bg-amber-500 hover:bg-amber-600 shadow-[0_0_15px_rgba(245,158,11,0.2)]': modal.type === 'warning',
                        'bg-emerald-600 hover:bg-emerald-700 shadow-[0_0_15px_rgba(16,185,129,0.2)]': modal.type === 'success',
                        'bg-blue-600 hover:bg-blue-700 shadow-[0_0_15px_rgba(37,99,235,0.2)]': modal.type === 'info'
                    }"
                    class="text-white font-bold py-3 rounded-xl transition-colors text-sm"
                    x-text="modal.confirmText">
            </button>
        </div>
    </div>
</div>
</template>
