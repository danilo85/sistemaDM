<div x-data="{ searchOpen: $wire.busca !== '' }" class="flex items-center gap-2">
    {{-- Campo de busca expansível --}}
    <div class="relative" x-show="searchOpen" x-transition>
        <input type="text" 
               wire:model.live.debounce.300ms="busca" 
               placeholder="Buscar por título..." 
               class="w-64 h-8 py-1 px-3 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 pr-10 text-sm"
               x-ref="searchInput">
        {{-- Botão para limpar busca --}}
        <button wire:click="clearSearch" 
                class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                x-show="$wire.busca !== ''"
                type="button">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    
    {{-- Botão de ícone para expandir/recolher busca --}}
    <button @click="searchOpen = !searchOpen; if(searchOpen) { $nextTick(() => $refs.searchInput.focus()) } else { $wire.clearSearch() }"
            class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
            type="button">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
        </svg>
    </button>
</div>