<form wire:submit="save" x-data="{ tipo: $wire.entangle('tipo', true) }">
    {{-- Seletor de Tipo (Receita/Despesa) com Animação --}}
    <div class="mb-6">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
        <div class="relative flex items-center justify-center p-1 rounded-lg bg-gray-100 dark:bg-gray-700">
            {{-- Fundo Deslizante --}}
            <div class="absolute left-1 h-full w-1/2 rounded-md transition-all duration-300 ease-in-out"
                 :class="{
                     'bg-green-600 shadow': tipo === 'receita',
                     'bg-red-600 shadow': tipo === 'despesa',
                     'transform translate-x-full': tipo === 'despesa'
                 }">
            </div>
            
            {{-- Botão Receita --}}
            <button type="button" @click="tipo = 'receita'"
                    class="relative w-1/2 py-2 text-center rounded-md transition-colors text-sm font-semibold z-10"
                    :class="tipo === 'receita' ? 'text-white' : 'text-gray-600 dark:text-gray-400'">
                Receita
            </button>

            {{-- Botão Despesa --}}
            <button type="button" @click="tipo = 'despesa'"
                    class="relative w-1/2 py-2 text-center rounded-md transition-colors text-sm font-semibold z-10"
                    :class="tipo === 'despesa' ? 'text-white' : 'text-gray-600 dark:text-gray-400'">
                Despesa
            </button>
            
            {{-- Inputs escondidos para o Livewire --}}
            <input type="radio" id="tipo-receita-toggle" value="receita" wire:model.live="tipo" class="hidden">
            <input type="radio" id="tipo-despesa-toggle" value="despesa" wire:model.live="tipo" class="hidden">
        </div>
    </div>

    {{-- Campos do Formulário --}}
    <div class="space-y-6">
        <div>
            <label for="descricao" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
            <input type="text" wire:model="descricao" id="descricao" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
            @error('descricao') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="valor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor (R$)</label>
                <x-money-input wire:model="valor" name="valor" />
                @error('valor') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="data" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data</label>
                <x-date-picker wire:model="data" name="data" id="data" value="{{ $data }}" />
                @error('data') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="categoria_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoria</label>
                <select wire:model="categoria_id" id="categoria_id" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione...</option>
                    @foreach($categoriasFiltradas as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                    @endforeach
                </select>
                @error('categoria_id') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="conta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Conta / Cartão</label>
                <select wire:model="conta" id="conta" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione...</option>
                    <optgroup label="Contas Bancárias">
                        @foreach ($bancos as $banco)
                            <option value="Banco_{{ $banco->id }}">{{ $banco->nome }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Cartões de Crédito">
                        @foreach ($cartoes as $cartao)
                            <option value="CartaoCredito_{{ $cartao->id }}">{{ $cartao->nome }}</option>
                        @endforeach
                    </optgroup>
                </select>
                @error('conta') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
            </div>
        </div>
        
        <div class="pt-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Frequência</label>
            <div class="flex flex-wrap gap-4">
                <div>
                    <input type="radio" id="tipoUnico" value="unico" wire:model.live="launchType" class="hidden peer">
                    <label for="tipoUnico" class="inline-flex items-center justify-between w-full p-3 rounded-lg cursor-pointer transition-colors text-gray-500 bg-white border border-gray-200 peer-checked:bg-blue-600 peer-checked:text-white hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700 dark:peer-checked:bg-blue-500">
                        <div class="block"><div class="w-full text-sm font-semibold">Único</div></div>
                    </label>
                </div>
                <div>
                    <input type="radio" id="tipoParcelado" value="parcelado" wire:model.live="launchType" class="hidden peer">
                    <label for="tipoParcelado" class="inline-flex items-center justify-between w-full p-3 rounded-lg cursor-pointer transition-colors text-gray-500 bg-white border border-gray-200 peer-checked:bg-green-600 peer-checked:text-white hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700 dark:peer-checked:bg-green-500">
                        <div class="block"><div class="w-full text-sm font-semibold">Parcelado</div></div>
                    </label>
                </div>
                <div>
                    <input type="radio" id="tipoRecorrente" value="recorrente" wire:model.live="launchType" class="hidden peer">
                    <label for="tipoRecorrente" class="inline-flex items-center justify-between w-full p-3 rounded-lg cursor-pointer transition-colors text-gray-500 bg-white border border-gray-200 peer-checked:bg-purple-600 peer-checked:text-white hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700 dark:peer-checked:bg-purple-500">
                        <div class="block"><div class="w-full text-sm font-semibold">Recorrente</div></div>
                    </label>
                </div>
            </div>
        </div>

        @if($launchType === 'parcelado')
        <div class="pt-2">
            <label for="parcela_total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Parcelas</label>
            <input type="number" wire:model="parcela_total" id="parcela_total" min="2" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
            @error('parcela_total') <p class="mt-2 text-sm text-red-600 dark:text-red-500">{{ $message }}</p> @enderror
        </div>
        @endif
        
        @if($launchType === 'recorrente')
        <div class="pt-2">
            <label for="frequency" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Repetir a cada:</label>
            <select wire:model="frequency" id="frequency" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                <option value="monthly">Mês</option>
                <option value="yearly">Ano</option>
            </select>
        </div>
        @endif
    </div>

    <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
        <a href="{{ route('transacoes.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Cancelar
        </a>
        <button type="submit" 
                class="font-medium rounded-lg text-sm px-5 py-2.5 text-white transition-colors"
                :class="{
                    'bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800': tipo === 'receita',
                    'bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-700 dark:focus:ring-red-800': tipo === 'despesa'
                }">
            Salvar Lançamento
        </button>
    </div>
</form>
