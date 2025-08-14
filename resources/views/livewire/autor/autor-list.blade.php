<div>
    {{-- CABEÇALHO COM TÍTULO E BUSCA --}}
    <div class="mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            {{-- TÍTULO E SUBTÍTULO À ESQUERDA --}}
            <div class="flex-1 min-w-0">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight mb-2">
                    Gestão de Autores
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Gerencie seus autores e informações de contato
                </p>
            </div>
            
            {{-- BUSCA DE AUTORES À DIREITA --}}
            <div class="flex-shrink-0 w-full lg:w-80">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" 
                           wire:model.live.debounce.300ms="search"
                           placeholder="Buscar autores..."
                           class="block w-full pl-10 pr-10 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:focus:ring-green-400 sm:text-sm">
                    {{-- Botão X para limpar busca --}}
                    @if($search)
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                            <button wire:click="$set('search', '')" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>



    {{-- GRID DE CARDS --}}
    @if($autores->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                @foreach($autores as $autor)
                    @php
                        $cores = [
                            'white' => 'bg-white border-gray-200 dark:bg-gray-800 dark:border-gray-600',
                            'blue' => 'bg-blue-50 border-blue-200 dark:bg-blue-900/20 dark:border-blue-700',
                            'green' => 'bg-green-50 border-green-200 dark:bg-green-900/20 dark:border-green-700',
                            'pink' => 'bg-pink-50 border-pink-200 dark:bg-pink-900/20 dark:border-pink-700',
                            'yellow' => 'bg-yellow-50 border-yellow-200 dark:bg-yellow-900/20 dark:border-yellow-700',
                            'orange' => 'bg-orange-50 border-orange-200 dark:bg-orange-900/20 dark:border-orange-700'
                        ];
                        $corClasse = $cores[$autor->cor] ?? $cores['white'];
                    @endphp
                    <div wire:key="{{ $autor->id }}" class="{{ $corClasse }} rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                        {{-- CABEÇALHO DO CARD --}}
                        <div class="p-6 pb-4">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3 flex-1 min-w-0">
                                    {{-- LOGO/AVATAR --}}
                                    <div class="flex-shrink-0">
                                        @if($autor->logo)
                                            <img src="{{ Storage::url($autor->logo) }}" alt="{{ $autor->name }}" class="w-12 h-12 rounded-full object-cover border-2 border-white dark:border-gray-600 shadow-sm">
                                        @else
                                            <div class="w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center border-2 border-white dark:border-gray-600 shadow-sm">
                                                <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    {{-- NOME DO AUTOR --}}
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white truncate">
                                        <a href="{{ route('autores.show', $autor) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                            {{ $autor->name }}
                                        </a>
                                    </h3>
                                </div>
                                <div class="flex items-center gap-2 ml-2">
                                    @if (!$autor->is_complete)
                                        <span class="text-yellow-500" title="Cadastro Incompleto">⚠️</span>
                                    @endif
                                    {{-- MENU DE CORES --}}
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
                                            </svg>
                                        </button>
                                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 top-8 z-20 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 min-w-[120px]">
                                            <div class="px-3 py-2 text-xs font-medium text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-600">
                                                Cores
                                            </div>
                                            <div class="p-2">
                                                <div class="flex gap-1">
                                                    <button wire:click="updateCor({{ $autor->id }}, 'white')" @click="open = false" class="w-6 h-6 rounded-full bg-white border border-gray-300 hover:scale-110 transition-transform {{ $autor->cor === 'white' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : '' }}" title="Branco"></button>
                                                    <button wire:click="updateCor({{ $autor->id }}, 'blue')" @click="open = false" class="w-6 h-6 rounded-full bg-blue-500 hover:scale-110 transition-transform {{ $autor->cor === 'blue' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : '' }}" title="Azul"></button>
                                                    <button wire:click="updateCor({{ $autor->id }}, 'green')" @click="open = false" class="w-6 h-6 rounded-full bg-green-500 hover:scale-110 transition-transform {{ $autor->cor === 'green' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : '' }}" title="Verde"></button>
                                                    <button wire:click="updateCor({{ $autor->id }}, 'yellow')" @click="open = false" class="w-6 h-6 rounded-full bg-yellow-500 hover:scale-110 transition-transform {{ $autor->cor === 'yellow' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : '' }}" title="Amarelo"></button>
                                                    <button wire:click="updateCor({{ $autor->id }}, 'pink')" @click="open = false" class="w-6 h-6 rounded-full bg-pink-500 hover:scale-110 transition-transform {{ $autor->cor === 'pink' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : '' }}" title="Rosa"></button>
                                                    <button wire:click="updateCor({{ $autor->id }}, 'orange')" @click="open = false" class="w-6 h-6 rounded-full bg-orange-500 hover:scale-110 transition-transform {{ $autor->cor === 'orange' ? 'ring-2 ring-gray-400 dark:ring-gray-300' : '' }}" title="Laranja"></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- EMAIL --}}
                            <div class="mb-3">
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    @if($autor->email)
                                        <a href="mailto:{{ $autor->email }}" class="text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:underline truncate">
                                            {{ $autor->email }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">Não informado</span>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- CONTATO --}}
                            <div class="mb-4">
                                <div class="flex items-center gap-2 text-sm">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    @if($autor->contact)
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $autor->contact) }}" target="_blank" class="text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-green-400 hover:underline">
                                            {{ $autor->contact }}
                                        </a>
                                    @else
                                        <span class="text-gray-400 dark:text-gray-500">Não informado</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        {{-- AÇÕES DO CARD --}}
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 rounded-b-lg border-t border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-center gap-4">
                                <a href="{{ route('autores.show', $autor) }}" class="flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors" title="Ver Portfólio">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                
                                <a href="{{ route('autores.edit', $autor) }}" class="flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-md transition-colors" title="Editar">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z" />
                                    </svg>
                                </a>
                                
                                <button wire:click="deleteAutor({{ $autor->id }})" wire:confirm="Tem certeza que deseja excluir este autor?" class="flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Excluir">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
        </div>
    @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum autor encontrado</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    @if($search)
                        Nenhum autor corresponde aos critérios de busca.
                    @else
                        Comece adicionando um novo autor.
                    @endif
                </p>
                @if(!$search)
                    <div class="mt-6">
                        <a href="{{ route('autores.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Adicionar Autor
                        </a>
                    </div>
                @endif
            </div>
    @endif

    {{-- PAGINAÇÃO --}}
    @if($autores->hasPages())
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            {{ $autores->links() }}
        </div>
    @endif
</div>
