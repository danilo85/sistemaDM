<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Registrar Pagamento') }}
            </h2>
            <a href="{{ url()->previous() }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($orcamento)
                        {{-- Informações do Orçamento Selecionado --}}
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                            <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-2">
                                Orçamento #{{ $orcamento->numero }}
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-blue-800 dark:text-blue-200">Cliente:</span>
                                    <span class="text-blue-700 dark:text-blue-300">{{ $orcamento->cliente->name }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-blue-800 dark:text-blue-200">Valor Total:</span>
                                    <span class="text-blue-700 dark:text-blue-300">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-blue-800 dark:text-blue-200">Status:</span>
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        {{ $orcamento->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pagamentos.store') }}" class="space-y-6">
                        @csrf

                        {{-- Seleção de Orçamento --}}
                        <div>
                            <label for="orcamento_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Orçamento *
                            </label>
                            @if($orcamento)
                                <input type="hidden" name="orcamento_id" value="{{ $orcamento->id }}">
                                <div class="mt-1 p-3 bg-gray-100 dark:bg-gray-700 rounded-md">
                                    <span class="text-sm text-gray-900 dark:text-gray-100">
                                        #{{ $orcamento->numero }} - {{ $orcamento->titulo }} ({{ $orcamento->cliente->name }})
                                    </span>
                                </div>
                            @else
                                <select name="orcamento_id" id="orcamento_id" required
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Selecione um orçamento</option>
                                    @foreach($orcamentos as $orc)
                                        <option value="{{ $orc->id }}" {{ old('orcamento_id') == $orc->id ? 'selected' : '' }}>
                                            #{{ $orc->numero }} - {{ $orc->titulo }} ({{ $orc->cliente->name }}) - R$ {{ number_format($orc->valor_total, 2, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                            @error('orcamento_id')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Valor Pago --}}
                        <div>
                            <label for="valor_pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Valor Pago *
                            </label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">R$</span>
                                </div>
                                <input type="number" name="valor_pago" id="valor_pago" step="0.01" min="0.01" required
                                       value="{{ old('valor_pago') }}"
                                       class="pl-10 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                       placeholder="0,00">
                            </div>
                            @error('valor_pago')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Data do Pagamento --}}
                        <div>
                            <label for="data_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Data do Pagamento *
                            </label>
                            <input type="date" name="data_pagamento" id="data_pagamento" required
                                   value="{{ old('data_pagamento', date('Y-m-d')) }}"
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @error('data_pagamento')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipo de Pagamento --}}
                        <div>
                            <label for="tipo_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tipo de Pagamento *
                            </label>
                            <select name="tipo_pagamento" id="tipo_pagamento" required
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Selecione o tipo</option>
                                <option value="Dinheiro" {{ old('tipo_pagamento') == 'Dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                                <option value="PIX" {{ old('tipo_pagamento') == 'PIX' ? 'selected' : '' }}>PIX</option>
                                <option value="Transferência" {{ old('tipo_pagamento') == 'Transferência' ? 'selected' : '' }}>Transferência Bancária</option>
                                <option value="Cartão de Crédito" {{ old('tipo_pagamento') == 'Cartão de Crédito' ? 'selected' : '' }}>Cartão de Crédito</option>
                                <option value="Cartão de Débito" {{ old('tipo_pagamento') == 'Cartão de Débito' ? 'selected' : '' }}>Cartão de Débito</option>
                                <option value="Boleto" {{ old('tipo_pagamento') == 'Boleto' ? 'selected' : '' }}>Boleto</option>
                                <option value="Cheque" {{ old('tipo_pagamento') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="Outro" {{ old('tipo_pagamento') == 'Outro' ? 'selected' : '' }}>Outro</option>
                            </select>
                            @error('tipo_pagamento')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Método de Pagamento --}}
                        <div>
                            <label for="metodo_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Método/Conta (Opcional)
                            </label>
                            <select name="metodo_pagamento" id="metodo_pagamento"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Selecione uma conta (opcional)</option>
                                @if($bancos->count() > 0)
                                    <optgroup label="Contas Bancárias">
                                        @foreach($bancos as $banco)
                                            <option value="{{ $banco->nome }}" {{ old('metodo_pagamento') == $banco->nome ? 'selected' : '' }}>
                                                {{ $banco->nome }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if($cartoes->count() > 0)
                                    <optgroup label="Cartões">
                                        @foreach($cartoes as $cartao)
                                            <option value="{{ $cartao->nome }}" {{ old('metodo_pagamento') == $cartao->nome ? 'selected' : '' }}>
                                                {{ $cartao->nome }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                            @error('metodo_pagamento')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Descrição --}}
                        <div>
                            <label for="descricao" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Descrição/Observações (Opcional)
                            </label>
                            <textarea name="descricao" id="descricao" rows="3"
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                      placeholder="Informações adicionais sobre o pagamento...">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Botões --}}
                        <div class="flex items-center justify-end space-x-4">
                            <a href="{{ url()->previous() }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Cancelar
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Registrar Pagamento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>