<tr class="border-b bg-white dark:border-gray-700 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
        {{-- NOME DO AUTOR AGORA É UM LINK PARA O PORTFÓLIO --}}
        <a href="{{ route('autores.show', $autor) }}" class="flex items-center gap-2 hover:underline text-indigo-600 dark:text-indigo-400">
            <span>{{ $autor->name }}</span>
            @if (!$autor->is_complete)
                <span class="ml-2 text-yellow-500" title="Cadastro Incompleto">⚠️</span>
            @endif
        </a>
    </td>
    <td class="px-6 py-4">{{ $autor->email }}</td>
    <td class="px-6 py-4">{{ $autor->contact }}</td>
    <td class="px-6 py-4 text-right">
        {{-- Menu de Ações com 3 pontinhos --}}
        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button @click="open = !open" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" /></svg>
            </button>
            <div x-show="open" x-transition style="display: none;" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-700">
                <div class="py-1">
                    <button 
                        wire:click="$parent.showPortfolio({{ $autor->id }})"
                        class="bg-blue-500 hover:bg-blue-600 text-white p-2 rounded-full transition-colors duration-200"
                        title="Ver Portfólio"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                    
                    <a 
                        href="{{ route('autores.edit', $autor) }}"
                        class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-full transition-colors duration-200"
                        title="Editar"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    
                    <button 
                        wire:click="confirmDelete({{ $autor->id }})"
                        class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-full transition-colors duration-200"
                        title="Excluir"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </td>
</tr>
