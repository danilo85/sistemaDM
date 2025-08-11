<div>
    {{-- Seção 1: Cards de Resumo (KPIs) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Total em Projetos</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100 whitespace-nowrap">R$ {{ number_format($totalEmProjetos, 2, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pago</p>
            <p class="mt-1 text-3xl font-semibold text-green-600 dark:text-green-400 whitespace-nowrap">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo Devedor</p>
            <p class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-400 whitespace-nowrap">R$ {{ number_format($saldoDevedor, 2, ',', '.') }}</p>
        </div>
    </div>

    {{-- Seção 2: Filtros e Ações --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            {{-- Filtros de Data --}}
            <div class="flex items-center gap-4">
                <div>
                    <label for="dataInicio" class="text-sm font-medium text-gray-700 dark:text-gray-300">De:</label>
                    <input type="date" id="dataInicio" wire:model.live="dataInicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label for="dataFim" class="text-sm font-medium text-gray-700 dark:text-gray-300">Até:</label>
                    <input type="date" id="dataFim" wire:model.live="dataFim" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>
            {{-- Botão de Gerar PDF --}}
            <div>
                <a href="{{ route('clientes.extrato.pdf', ['cliente' => $cliente->id, 'data_inicio' => $dataInicio, 'data_fim' => $dataFim]) }}" 
                   target="_blank"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg">
                    Gerar Extrato (PDF)
                </a>
            </div>
        </div>
    </div>

    {{-- Seção 3: Tabelas de Histórico --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Tabela de Débitos (Projetos) --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Débitos (Projetos Aprovados)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2">Data</th>
                            <th class="px-4 py-2">Descrição</th>
                            <th class="px-4 py-2 text-right">Valor Total</th>
                            <th class="px-4 py-2 text-right">Falta Pagar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orcamentos as $orcamento)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-2 whitespace-nowrap">{{ optional($orcamento->updated_at)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">{{ $orcamento->titulo }}</td>
                                <td class="px-4 py-2 text-right whitespace-nowrap">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</td>
                                @php
                                    $faltaPagar = $orcamento->valor_total - $orcamento->pagamentos_sum_valor_pago;
                                @endphp
                                <td class="px-4 py-2 text-right font-bold whitespace-nowrap {{ $faltaPagar > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                    R$ {{ number_format($faltaPagar, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">Nenhum projeto no período.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tabela de Créditos (Pagamentos) --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Créditos (Pagamentos Recebidos)</h3>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase">
                        <tr>
                            <th class="px-4 py-2">Data</th>
                            <th class="px-4 py-2">Descrição</th>
                            <th class="px-4 py-2 text-right">Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pagamentos as $pagamento)
                            <tr class="border-b dark:border-gray-700">
                                <td class="px-4 py-2 whitespace-nowrap">{{ optional($pagamento->data_pagamento)->format('d/m/Y') }}</td>
                                <td class="px-4 py-2">Pagamento ref. Orçamento #{{ $pagamento->orcamento->numero }}</td>
                                <td class="px-4 py-2 text-right text-green-600 dark:text-green-400 whitespace-nowrap">R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">Nenhum pagamento no período.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
