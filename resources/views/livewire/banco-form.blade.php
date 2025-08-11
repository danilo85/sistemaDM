<form wire:submit="save">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Campo Nome --}}
            <div>
                <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome da Conta</label>
                <select wire:model="nome" id="nome" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione um banco</option>
                    @foreach($bancosDisponiveis as $bancoNome)
                        <option value="{{ $bancoNome }}">{{ $bancoNome }}</option>
                    @endforeach
                </select>
                @error('nome')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo Tipo de Conta --}}
            <div>
                <label for="tipo_conta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Conta</label>
                <select wire:model="tipo_conta" id="tipo_conta" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="Corrente">Corrente</option>
                    <option value="Poupança">Poupança</option>
                    <option value="Investimentos">Investimentos</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
        </div>
        
        {{-- Campo Saldo Inicial --}}
        <div>
            <label for="saldo_inicial" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Saldo Inicial (R$)</label>
            <x-money-input wire:model="saldo_inicial" name="saldo_inicial" id="saldo_inicial" />
            @error('saldo_inicial') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Botões de Ação --}}
    <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
        <a href="{{ route('bancos.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Cancelar
        </a>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            {{-- Texto do botão dinâmico --}}
            {{ $banco->exists ? 'Atualizar Conta' : 'Salvar Conta' }}
        </button>
    </div>
</form>
