<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Usando o componente de cabeçalho padronizado --}}
            <x-page-header>
                <x-slot:title>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('cartoes.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white" title="Voltar para a lista">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <div>
                            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                                Extrato do Cartão
                            </h2>
                            <p class="text-sm text-gray-500">{{ $cartao->nome }}</p>
                        </div>
                    </div>
                </x-slot:title>
                <x-slot:actions>
                    <a href="{{ route('cartoes.edit', $cartao) }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
                        Editar Cartão
                    </a>
                </x-slot:actions>
            </x-page-header>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900 dark:text-gray-100">

                    {{-- Navegação de Mês Aprimorada --}}
                    <div class="flex justify-between items-center mb-6">
                        <a href="{{ route('cartoes.extrato', ['cartao' => $cartao->id, 'ano' => $dataAtual->copy()->subMonth()->year, 'mes' => $dataAtual->copy()->subMonth()->month]) }}"
                           class="p-2 text-gray-500 bg-white rounded-full hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 shadow-sm border dark:border-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        
                        <div class="flex items-center gap-2 text-center">
                            <h3 class="text-xl font-semibold capitalize text-gray-800 dark:text-gray-200">
                                {{ $tituloDoMesCompleto }}
                            </h3>
                            <a href="{{ route('cartoes.extrato', $cartao->id) }}" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Ir para o mês atual">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </a>
                        </div>
                        
                        <a href="{{ route('cartoes.extrato', ['cartao' => $cartao->id, 'ano' => $dataAtual->copy()->addMonth()->year, 'mes' => $dataAtual->copy()->addMonth()->month]) }}"
                           class="p-2 text-gray-500 bg-white rounded-full hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 shadow-sm border dark:border-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>

                    {{-- Card de Resumo da Fatura --}}
                    <div class="mb-6 flex items-center justify-between rounded-lg bg-gray-50 p-4 shadow-sm dark:bg-gray-900/50 border dark:border-gray-700">
                        <div class="flex items-center gap-4">
                            @if($cartao->bandeira_path)
                                <img src="{{ $cartao->bandeira_path }}" alt="Bandeira {{ $cartao->bandeira }}" class="h-10 w-16 object-contain rounded-md bg-white p-1 shadow-md">
                            @endif
                            <div>
                                <span class="block text-lg font-bold text-gray-900 dark:text-gray-200">{{ $cartao->nome }}</span>
                                <span class="block text-sm text-gray-500 dark:text-gray-400">Fatura de {{ $nomeDoMes }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="block text-sm text-red-700 dark:text-red-400">Total da Fatura</span>
                            <span class="text-2xl font-bold text-red-600 dark:text-red-500 whitespace-nowrap">R$ {{ number_format($totalFatura, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- Tabela de Lançamentos --}}
                    <div class="relative overflow-x-auto ">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Data</th>
                                    <th scope="col" class="px-6 py-3">Descrição</th>
                                    <th scope="col" class="px-6 py-3">Categoria</th>
                                    <th scope="col" class="px-6 py-3 text-right">Valor</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transacoes as $transacao)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($transacao->data)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $transacao->descricao }}
                                            @if ($transacao->parcela_total)
                                                <span class="ml-2 px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                                                    {{ $transacao->parcela_atual }} / {{ $transacao->parcela_total }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">{{ $transacao->categoria->nome ?? 'Sem Categoria' }}</td>
                                        <td class="px-6 py-4 text-right font-mono text-lg font-semibold text-red-500">
                                            <span class="whitespace-nowrap">- R$ {{ number_format($transacao->valor, 2, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800">
                                        <td colspan="4" class="px-6 py-4 text-center">
                                            Nenhum gasto encontrado para este cartão neste mês.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
