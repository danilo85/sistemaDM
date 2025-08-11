@php
    // Define um valor padrão para isDetalheFatura, caso não seja passado
    $isDetalheFatura = $isDetalheFatura ?? false;
@endphp
<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
    <td class="px-6 py-4">{{ \Carbon\Carbon::parse($transacao->data)->format('d/m/Y') }}</td>
    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        {{ $transacao->descricao }}
        
        {{-- CORREÇÃO: Lógica para exibir o badge de Recorrente --}}
        @if ($transacao->parcela_total)
            <span class="ml-2 px-2 py-1 text-xs font-semibold text-blue-800 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
                {{ $transacao->parcela_atual }} / {{ $transacao->parcela_total }}
            </span>
        @elseif ($transacao->is_recurring)
            <span class="ml-2 px-2 py-1 text-xs font-semibold text-purple-800 bg-purple-100 rounded-full dark:bg-purple-900 dark:text-purple-300">
                Recorrente
            </span>
        @endif
    </td>
    <td class="px-6 py-4">{{ $transacao->categoria->nome ?? 'Sem Categoria' }}</td>
    <td class="px-6 py-4 text-right text-lg font-semibold {{ $transacao->tipo === 'receita' ? 'text-green-500' : 'text-red-500' }}">
        <span class="whitespace-nowrap">
            {{ $transacao->tipo === 'receita' ? '+ ' : '- ' }} R$ {{ number_format($transacao->valor, 2, ',', '.') }}
        </span>
    </td>
    <td class="px-6 py-4 text-center">
        @if(!$isDetalheFatura)
            <button wire:click="togglePago" class="px-3 py-1 text-xs font-bold rounded-full {{ $transacao->pago ? 'bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-300' }}">
                {{ $transacao->pago ? ($transacao->tipo === 'receita' ? 'Recebido' : 'Pago') : ($transacao->tipo === 'receita' ? 'Receber' : 'Pagar') }}
            </button>
        @else
            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $transacao->pago ? 'bg-green-200 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-300' }}">
                {{ $transacao->pago ? 'Pago' : 'Pendente' }}
            </span>
        @endif
    </td>
    <td class="px-6 py-4 text-right">
        <div x-data="{ confirmingDelete: false }" class="flex items-center justify-end gap-4">
            <a href="{{ route('transacoes.create', ['from' => $transacao->id]) }}" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Duplicar"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg></a>
            <a href="{{ route('transacoes.edit', $transacao) }}" class="text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-500" title="Editar"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg></a>
            <button @click="confirmingDelete = true" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Excluir"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
            <template x-teleport="body">
                <div x-show="confirmingDelete" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
                    <div @click.away="confirmingDelete = false" class="w-full max-w-md p-6 bg-white dark:bg-gray-800 rounded-lg shadow-xl">
                        <h2 class="text-lg font-semibold text-center text-gray-900 dark:text-white">Confirmação de Exclusão</h2>
                        <p class="mt-2 text-sm text-center text-gray-600 dark:text-gray-400">@if(!empty($transacao->compra_id)) Esta é uma transação parcelada. Como deseja prosseguir? @else Tem certeza que deseja excluir este lançamento? @endif</p>
                        @if(!empty($transacao->compra_id))
                            <div class="mt-6 space-y-3">
                                <button wire:click="deleteAll" @click="confirmingDelete = false" class="w-full justify-center text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5">Excluir Todas as Parcelas</button>
                                <button wire:click="deleteSingle" @click="confirmingDelete = false" class="w-full justify-center text-gray-900 bg-yellow-400 hover:bg-yellow-500 font-medium rounded-lg text-sm px-5 py-2.5">Excluir Apenas Esta ({{ $transacao->parcela_atual }}/{{ $transacao->parcela_total }})</button>
                                <button @click="confirmingDelete = false" class="w-full justify-center text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">Cancelar</button>
                            </div>
                        @else
                            <div class="mt-6 flex justify-center gap-4">
                                <button @click="confirmingDelete = false" class="w-full justify-center text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600">Cancelar</button>
                                <button wire:click="deleteSingle" @click="confirmingDelete = false" class="text-white bg-red-600 hover:bg-red-700 font-medium rounded-lg text-sm px-5 py-2.5">Sim, Excluir</button>
                            </div>
                        @endif
                    </div>
                </div>
            </template>
        </div>
    </td>
</tr>
