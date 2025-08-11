<div x-data="{ open: false }" class="relative inline-block text-left">

    {{-- O Badge de Status, que também é um botão para abrir o menu --}}
    <div>
        <button @click="open = !open" type="button" class="inline-flex items-center">
            <x-status-badge :status="$orcamento->status" />
            <svg class="ml-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
    <div>
        
    </div>
    {{-- O menu dropdown que abre e fecha --}}
    <div x-show="open"
         x-transition
         @click.outside="open = false"
         style="display: none;"
         class="absolute z-10 mt-2 w-56 origin-top-left rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-700">
        <div class="py-1" role="menu" aria-orientation="vertical">
            <a href="#" wire:click.prevent="mudarStatus('Analisando')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Analisando</a>
            <a href="#" wire:click.prevent="mudarStatus('Aprovado')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Aprovado</a>
            <a href="#" wire:click.prevent="mudarStatus('Rejeitado')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Rejeitado</a>
            <a href="#" wire:click.prevent="mudarStatus('Finalizado')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Finalizado</a>
            <a href="#" wire:click.prevent="mudarStatus('Pago')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Pago</a>
        </div>
    </div>
</div>