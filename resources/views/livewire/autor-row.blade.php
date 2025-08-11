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
                    <a href="{{ route('autores.edit', $autor->id) }}" class="text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Editar</a>
                    <button wire:click="deleteAutor" wire:confirm="Tem certeza que deseja excluir este autor?" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600">Excluir</button>
                </div>
            </div>
        </div>
    </td>
</tr>
