<form wire:submit="save">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Campo Nome --}}
            <div>
                <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome do Cartão (Ex: NuBank Ultravioleta)</label>
                <input type="text" wire:model="nome" id="nome" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" required>
                @error('nome') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Campo Bandeira --}}
            <div>
                <label for="bandeira" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bandeira</label>
                <select wire:model="bandeira" id="bandeira" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione uma bandeira</option>
                    @foreach($bandeirasDisponiveis as $bandeiraOption)
                        <option value="{{ $bandeiraOption }}">{{ $bandeiraOption }}</option>
                    @endforeach
                </select>
                @error('bandeira') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="limite_credito" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Limite de Crédito (R$)</label>
            <x-money-input wire:model="limite_credito" name="limite_credito" id="limite_credito" />
            @error('limite_credito') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Dia do Fechamento --}}
            <div>
                <label for="dia_fechamento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dia do Fechamento</label>
                <input type="number" wire:model="dia_fechamento" id="dia_fechamento" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" required min="1" max="31">
                @error('dia_fechamento') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Dia do Vencimento --}}
            <div>
                <label for="dia_vencimento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dia do Vencimento</label>
                <input type="number" wire:model="dia_vencimento" id="dia_vencimento" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" required min="1" max="31">
                @error('dia_vencimento') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Botões de Ação --}}
    <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
        <a href="{{ route('cartoes.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Cancelar
        </a>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            {{ $cartao->exists ? 'Atualizar Cartão' : 'Salvar Cartão' }}
        </button>
    </div>
</form>
