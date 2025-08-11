<div>
        {{-- BARRA DE BUSCA EXPANSÍVEL --}}
    <div class="mb-4 flex justify-end">
        {{-- CORREÇÃO: Adicionada uma altura fixa (h-10) ao contêiner para evitar o "pulo" no layout --}}
        <div x-data="{ searchOpen: $wire.get('busca') !== '' }" class="relative flex items-center h-10">
            
            {{-- O campo de busca, que agora aparece e desaparece --}}
            <div x-show="searchOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-x-0"
                 x-transition:enter-end="opacity-100 scale-x-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-x-100"
                 x-transition:leave-end="opacity-0 scale-x-0"
                 @click.away="if($wire.get('busca') === '') searchOpen = false" 
                 class="relative origin-right"
                 style="display: none;">
                
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                </div>
                <input 
                    x-ref="searchInput"
                    type="text" 
                    wire:model.live.debounce.300ms="busca" 
                    placeholder="Buscar..."
                    class="block w-full sm:w-80 rounded-lg border-transparent bg-gray-100 dark:bg-gray-800 pl-10 pr-10 shadow-sm focus:ring-2 focus:ring-blue-500"
                >
                <div x-show="$wire.get('busca') !== ''" x-transition class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <button wire:click="$set('busca', '')" @click="searchOpen = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            {{-- O botão de ícone que fica visível quando a busca está fechada --}}
            <button x-show="!searchOpen" @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" type="button" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
            </button>
        </div>
    </div>

    {{-- TABELA DE CLIENTES --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <button wire:click="sortBy('name')" class="flex items-center gap-1 uppercase">
                            Nome
                            @if($sortField === 'name')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $sortDirection === 'asc' ? 'm5 15l7-7 7 7' : 'm19 9l-7 7-7-7' }}" /></svg>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <button wire:click="sortBy('email')" class="flex items-center gap-1 uppercase">
                            Email
                            @if($sortField === 'email')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $sortDirection === 'asc' ? 'm5 15l7-7 7 7' : 'm19 9l-7 7-7-7' }}" /></svg>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3 uppercase">Telefone</th>
                    <th scope="col" class="px-6 py-3 text-right uppercase">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clientes as $cliente)
                    <tr wire:key="{{ $cliente->id }}" class="border-b bg-white dark:border-gray-700 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            <a href="{{ route('clientes.show', $cliente) }}" class="flex items-center gap-2 hover:underline dark:text-indigo-400">
                                <span>{{ $cliente->name }}</span>
                                @if (!$cliente->is_complete)
                                    <span class="text-yellow-500" title="Cadastro Incompleto">⚠️</span>
                                @endif
                            </a>
                        </td>
                        <td class="px-6 py-4">
                            @if($cliente->email)
                                <a href="mailto:{{ $cliente->email }}" class="text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                                    {{ $cliente->email }}
                                </a>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($cliente->phone)
                                <a href="https://wa.me/{{ preg_replace('/\D/', '', $cliente->phone) }}" target="_blank" class="text-gray-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline">
                                    {{ $cliente->phone }}
                                </a>
                            @else
                                <span class="text-gray-400 dark:text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                           <div class="flex items-center justify-end gap-4">
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Editar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                </a>
                                <form method="POST" action="{{ route('clientes.destroy', $cliente->id) }}" onsubmit="return confirm('Tem certeza que deseja excluir este cliente?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Excluir">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center" colspan="4">Nenhum cliente encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Links de Paginação --}}
    <div class="mt-6">
        {{ $clientes->links() }}
    </div>
</div>
