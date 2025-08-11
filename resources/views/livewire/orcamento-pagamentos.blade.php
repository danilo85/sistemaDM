<div class="space-y-6">
    {{-- Card Principal: Formulário e Resumo --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-6">
            {{-- Coluna da Esquerda: Formulário de Pagamento --}}
            <div class="lg:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    {{ $pagamentoEmEdicao ? 'Editar Pagamento' : 'Registrar Novo Pagamento' }}
                </h3>
                
                <form wire:submit="salvarPagamento">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        {{-- Valor --}}
                        <div>
                            <label for="valor-pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor (R$)</label>
                            <x-money-input name="valor" wire:model="valor" id="valor-pago" />
                            @error('valor') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Data --}}
                        <div>
                            <label for="data-pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data</label>
                             <x-date-picker name="data_pagamento" wire:model="data_pagamento" id="data-pagamento" :value="$data_pagamento" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500"/>
                            @error('data_pagamento') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Tipo de Pagamento --}}
                        <div>
                            <label for="tipo_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Forma de Pagamento</label>
                            <select wire:model="tipo_pagamento" id="tipo_pagamento" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                                <option value="PIX">PIX</option>
                                <option value="Transferência">Transferência</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Pagamento Final">Pagamento Final</option>
                                <option value="Entrada">Entrada</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        {{-- Conta --}}
                        <div>
                            <label for="conta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Creditar na Conta</label>
                            <select wire:model="conta_id" id="conta_id" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione...</option>
                                <optgroup label="Contas Bancárias">
                                    @foreach ($bancos as $banco)
                                        <option value="Banco_{{ $banco->id }}">{{ $banco->nome }}</option>
                                    @endforeach
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    {{-- Botões de Ação --}}
                    <div class="mt-6 flex items-center justify-end gap-4 pt-4 border-t dark:border-gray-700">
                        @if($pagamentoEmEdicao)
                            <button type="button" wire:click="cancelarEdicao" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancelar Edição</button>
                        @endif
                        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition-colors">
                            <span wire:loading.remove wire:target="salvarPagamento">{{ $pagamentoEmEdicao ? 'Atualizar Pagamento' : 'Registrar Pagamento' }}</span>
                            <span wire:loading wire:target="salvarPagamento">Salvando...</span>
                        </button>
                    </div>
                </form>
            </div>

            {{-- Coluna da Direita: Resumo Financeiro --}}
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6 space-y-6 border dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Resumo Financeiro</h3>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total do Orçamento</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Pago</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">R$ {{ number_format($totalPago, 2, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Restante</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">R$ {{ number_format($restante, 2, ',', '.') }}</p>
                </div>
                @if($restante > 0 && !$pagamentoEmEdicao)
                    <button wire:click="quitarOrcamento" wire:confirm="Tem a certeza que deseja quitar o valor restante de R$ {{ number_format($restante, 2, ',', '.') }}?" class="w-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 font-semibold py-2 px-4 rounded-lg hover:bg-green-200 dark:hover:bg-green-800">
                        Quitar Orçamento
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Histórico de Pagamentos --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Histórico de Pagamentos</h3>
        <div class="space-y-3">
            @forelse($pagamentos as $pagamento)
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-full">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold dark:text-gray-200">R$ {{ number_format($pagamento->valor_pago, 2, ',', '.') }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $pagamento->tipo_pagamento }} em {{ $pagamento->data_pagamento->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button wire:click="generateReceipt({{ $pagamento->id }})" title="Gerar Recibo" class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </button>
                        <button wire:click="editarPagamento({{ $pagamento->id }})" title="Editar" class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="excluirPagamento({{ $pagamento->id }})" wire:confirm="Tem a certeza?" title="Excluir" class="text-gray-500 hover:text-red-600 dark:hover:text-red-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-sm text-center text-gray-500 p-4">Nenhum pagamento registado.</div>
            @endforelse
        </div>
    </div>
</div>
