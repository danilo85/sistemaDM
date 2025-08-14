<x-app-layout>
    {{-- Barra com título ocupando 100% da largura --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white w-full">
        <div class="px-4 sm:px-6 lg:px-8 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold">Categorias Financeiras</h1>
                    <p class="text-blue-100 mt-1 text-sm">Gerencie as categorias para organizar suas transações financeiras</p>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-8 h-8 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- GRID DE CARDS --}}
    @if($categorias->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 mb-8 max-w-6xl mx-auto">
            @foreach($categorias as $categoria)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                    {{-- CABEÇALHO DO CARD --}}
                    <div class="p-4 md:p-6 pb-3 md:pb-4">
                        {{-- LAYOUT MOBILE: Ícone centralizado acima --}}
                        <div class="block md:hidden">
                            {{-- ÍCONE CENTRALIZADO --}}
                            <div class="flex justify-center mb-3">
                                <div class="w-12 h-12 @if($categoria->tipo === 'receita') bg-green-100 dark:bg-green-900/30 @else bg-red-100 dark:bg-red-900/30 @endif flex items-center justify-center rounded-full">
                                    @if($categoria->tipo === 'receita')
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            {{-- NOME E TIPO CENTRALIZADOS --}}
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 whitespace-nowrap overflow-hidden text-ellipsis max-w-full">
                                    {{ $categoria->nome }}
                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($categoria->tipo === 'receita') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                    @endif">
                                    {{ ucfirst($categoria->tipo) }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- LAYOUT DESKTOP: Ícone ao lado --}}
                        <div class="hidden md:block">
                            {{-- BLOCO CENTRALIZADO COM ÍCONE, NOME E TIPO --}}
                            <div class="flex justify-center mb-4">
                                <div class="flex items-center gap-4">
                                    {{-- ÍCONE/AVATAR --}}
                                    <div class="flex-shrink-0">
                                        <div class="w-16 h-16 @if($categoria->tipo === 'receita') bg-green-100 dark:bg-green-900/30 @else bg-red-100 dark:bg-red-900/30 @endif flex items-center justify-center rounded-full border-2 border-white dark:border-gray-600">
                                            @if($categoria->tipo === 'receita')
                                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                                </svg>
                                            @else
                                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- NOME E TIPO --}}
                                    <div class="">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1 whitespace-nowrap overflow-hidden text-ellipsis max-w-full">
                                            {{ $categoria->nome }}
                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($categoria->tipo === 'receita') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @endif">
                                            {{ ucfirst($categoria->tipo) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- INFORMAÇÕES ADICIONAIS COM DESIGN APRIMORADO --}}
                        <div class="text-center space-y-4 mb-4">
                            {{-- TIPO DE CATEGORIA --}}
                            <div class="@if($categoria->tipo === 'receita') bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-green-200 dark:border-green-700 @else bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-red-200 dark:border-red-700 @endif rounded-lg p-2 border">
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    @if($categoria->tipo === 'receita')
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <div class="text-sm font-medium text-green-700 dark:text-green-300">Categoria de Receita</div>
                                    @else
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                        <div class="text-sm font-medium text-red-700 dark:text-red-300">Categoria de Despesa</div>
                                    @endif
                                </div>
                                <div class="text-xl font-bold @if($categoria->tipo === 'receita') text-green-800 dark:text-green-200 @else text-red-800 dark:text-red-200 @endif">
                                    {{ ucfirst($categoria->tipo) }}
                                </div>
                            </div>
                        </div>
                    </div>
                            
                        {{-- Ações --}}
                        <div class="px-4 pb-4">
                            <div class="flex items-stretch gap-2">
                                <a href="{{ route('categorias.edit', $categoria->id) }}" class="flex-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-3 py-2 rounded-md text-xs font-medium text-center transition-colors flex flex-col items-center justify-center" title="Editar">
                                    <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('categorias.destroy', $categoria->id) }}" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full h-full bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 px-3 py-2 rounded-md text-xs font-medium transition-colors flex flex-col items-center justify-center" title="Excluir">
                                        <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma categoria encontrada</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece adicionando uma nova categoria financeira.</p>
                    <div class="mt-6">
                        <a href="{{ route('categorias.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Adicionar Categoria
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- BOTÃO DE AÇÃO FLUTUANTE (FAB) --}}
    <a href="{{ route('categorias.create') }}" title="Nova Categoria"
       class="fixed bottom-6 right-6 inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform duration-200 ease-in-out hover:scale-110">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
    </a>
</x-app-layout>
