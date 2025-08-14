<x-app-layout>
    {{-- Barra com título ocupando 100% da largura --}}
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white w-full">
        <div class="px-4 sm:px-6 lg:px-8 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold">Cartões de Crédito</h1>
                    <p class="text-blue-100 mt-1 text-sm">Gerencie seus cartões de crédito e acompanhe limites e vencimentos</p>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-8 h-8 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Grid de Cards Responsivo --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                @forelse ($cartoes as $cartao)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        {{-- Bloco Principal Centralizado --}}
                        <div class="p-4 flex items-center justify-center">
                            <div class="flex items-center gap-3">
                                {{-- Logo da Bandeira --}}
                                <div class="flex-shrink-0">
                                    @if($cartao->bandeira_path)
                                        <img src="{{ $cartao->bandeira_path }}" alt="Bandeira" class="h-8 w-12 object-contain">
                                    @else
                                        <div class="h-8 w-12 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                {{-- Nome e Informações --}}
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $cartao->nome }}</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Cartão de Crédito</p>
                                </div>
                            </div>
                        </div>

                        {{-- Valores com Design Aprimorado --}}
                        <div class="px-4 pb-4 space-y-3">
                            {{-- Limite de Crédito --}}
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                    <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Limite de Crédito</div>
                                </div>
                                <div class="text-xl font-bold text-blue-800 dark:text-blue-200">
                                    R$ {{ number_format($cartao->limite_credito, 2, ',', '.') }}
                                </div>
                            </div>

                            {{-- Saldo Disponível (calculado) --}}
                            @php
                                $saldoDisponivel = $cartao->limite_credito; // Aqui você pode calcular o saldo real baseado nas transações
                                $isPositivo = $saldoDisponivel >= 0;
                            @endphp
                            <div class="bg-gradient-to-r {{ $isPositivo ? 'from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20' : 'from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20' }} rounded-lg p-2">
                                <div class="flex items-center gap-2 mb-1">
                                    @if($isPositivo)
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                        <div class="text-sm font-medium text-green-700 dark:text-green-300">Saldo Disponível</div>
                                    @else
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                        </svg>
                                        <div class="text-sm font-medium text-red-700 dark:text-red-300">Saldo Disponível</div>
                                    @endif
                                </div>
                                <div class="text-xl font-bold {{ $isPositivo ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                    R$ {{ number_format($saldoDisponivel, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        {{-- Informações de Datas --}}
                        <div class="px-4 pb-4">
                            <div class="grid grid-cols-2 gap-2 text-xs">
                                <div class="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div class="font-medium text-gray-700 dark:text-gray-300">Fechamento</div>
                                    <div class="text-gray-900 dark:text-white font-semibold">Dia {{ $cartao->dia_fechamento }}</div>
                                </div>
                                <div class="text-center p-2 bg-gray-50 dark:bg-gray-700 rounded">
                                    <div class="font-medium text-gray-700 dark:text-gray-300">Vencimento</div>
                                    <div class="text-gray-900 dark:text-white font-semibold">Dia {{ $cartao->dia_vencimento }}</div>
                                </div>
                            </div>
                        </div>

                        {{-- Ações --}}
                        <div class="px-4 pb-4">
                            <div class="flex items-stretch gap-2">
                                <a href="{{ route('cartoes.extrato', $cartao->id) }}" class="flex-1 bg-green-100 hover:bg-green-200 dark:bg-green-900/30 dark:hover:bg-green-900/50 text-green-700 dark:text-green-300 px-3 py-2 rounded-md text-xs font-medium text-center transition-colors flex flex-col items-center justify-center" title="Extrato">
                                    <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Extrato
                                </a>
                                <a href="{{ route('cartoes.edit', ['cartao' => $cartao->id]) }}" class="flex-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-3 py-2 rounded-md text-xs font-medium text-center transition-colors flex flex-col items-center justify-center" title="Editar">
                                    <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                    Editar
                                </a>
                                <form method="POST" action="{{ route('cartoes.destroy', ['cartao' => $cartao->id]) }}" onsubmit="return confirm('Tem certeza que deseja excluir este cartão?')" class="flex-1">
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
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum cartão encontrado</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando um novo cartão de crédito.</p>
                            <div class="mt-6">
                                <a href="{{ route('cartoes.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Novo Cartão
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- BOTÃO DE AÇÃO FLUTUANTE (FAB) --}}
    <a href="{{ route('cartoes.create') }}" title="Novo Cartão"
       class="fixed bottom-6 right-6 inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform duration-200 ease-in-out hover:scale-110">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
    </a>
</x-app-layout>
