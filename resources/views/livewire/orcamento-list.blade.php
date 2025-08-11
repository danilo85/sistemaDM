<div 
    x-data="{
        selectedIds: [],
        selectedTotal: 0,

        toggleSelection(id, valor, event) {
            // Previne que o clique duplo também ative o link de ver detalhes
            event.preventDefault(); 
            event.stopPropagation();

            const trElement = event.currentTarget;
            const index = this.selectedIds.indexOf(id);

            if (index > -1) {
                // Se já está selecionado, remove
                this.selectedIds.splice(index, 1);
                this.selectedTotal -= parseFloat(valor);
                trElement.classList.remove('!bg-blue-100', 'dark:!bg-blue-900/50');
            } else {
                // Se não está, adiciona
                this.selectedIds.push(id);
                this.selectedTotal += parseFloat(valor);
                trElement.classList.add('!bg-blue-100', 'dark:!bg-blue-900/50');
            }
        },
        // NOVA FUNÇÃO PARA LIMPAR A SELEÇÃO
        clearSelection() {
            this.selectedIds = [];
            this.selectedTotal = 0;
            // Remove a classe de todos os elementos que possam estar selecionados
            document.querySelectorAll('.!bg-blue-100').forEach(el => {
                el.classList.remove('!bg-blue-100', 'dark:!bg-blue-900/50');
            });
        }
    }"
>
    {{-- BARRA SUPERIOR COM TOTAIS E BUSCA --}}
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        {{-- CARDS DE TOTAIS --}}
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="p-4 bg-blue-50 dark:bg-blue-900/50 rounded-lg text-center">
                <span class="text-sm text-blue-800 dark:text-blue-300 block">Total a Receber (Ativos)</span>
                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400 whitespace-nowrap">R$ {{ number_format($totalAReceber, 2, ',', '.') }}</span>
            </div>
            
            {{-- CARD DE SOMA SELECIONADA COM BOTÃO DE LIMPAR --}}
            <div x-show="selectedTotal > 0" x-transition class="flex items-center gap-2 p-4 bg-indigo-50 dark:bg-indigo-900/50 rounded-lg text-center">
                <div>
                    <span class="text-sm text-indigo-800 dark:text-indigo-300 block">Soma Selecionada</span>
                    <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 whitespace-nowrap" x-text="`R$ ${selectedTotal.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`"></span>
                </div>
                {{-- BOTÃO DE LIMPAR --}}
                <button @click="clearSelection()" class="p-2 rounded-full text-indigo-500 hover:bg-indigo-100 dark:hover:bg-indigo-900" title="Limpar Seleção">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    </div>
        
    {{-- BARRA DE BUSCA EXPANSÍVEL --}}
    <div class="mb-4 flex justify-end">
        {{-- CORREÇÃO: Adicionada uma altura fixa (h-10) ao contêiner para evitar o "pulo" no layout --}}
        <div x-data="{ searchOpen: $wire.get('busca') !== '' }" class="relative flex items-center h-10">
            
            {{-- O campo de busca, que agora aparece e desaparece --}}
            <div x-show="searchOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-x-0"
                 x-transition:enter-end="opacity-100 scale-x-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-x-100"
                 x-transition:leave-end="opacity-0 scale-x-0"
                 @click.away="if($wire.get('busca') === '') searchOpen = false" 
                 class="relative origin-right"
                 style="display: none;">
                
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                </div>
                <input 
                    x-ref="searchInput"
                    type="text" 
                    wire:model.live.debounce.300ms="busca" 
                    placeholder="Buscar..."
                    class="block w-full sm:w-80 rounded-lg border-transparent bg-gray-100 dark:bg-gray-800 pl-10 pr-10 shadow-sm focus:ring-2 focus:ring-blue-500"
                >
                <div x-show="$wire.get('busca') !== ''" x-transition class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <button wire:click="$set('busca', '')" @click="searchOpen = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
            </div>

            {{-- O botão de ícone que fica visível quando a busca está fechada --}}
            <button x-show="!searchOpen" @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" type="button" class="p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
            </button>
        </div>
    </div>
    </div>

    {{-- BOTÕES DE FILTRO DE STATUS COM NOVO ESTILO --}}
    <div class="mb-4 flex flex-wrap items-center gap-2">
        @php
            // Estilos para botões INATIVOS
            $statusColors = [
                'todos' => 'bg-gray-200 text-gray-800 hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600',
                'ativos' => 'bg-blue-100 text-blue-800 hover:bg-blue-200 dark:bg-blue-900/70 dark:text-blue-300',
                'Analisando' => 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900/70 dark:text-yellow-300',
                'Aprovado' => 'bg-green-100 text-green-800 hover:bg-green-200 dark:bg-green-900/70 dark:text-green-300',
                'Rejeitado' => 'bg-red-100 text-red-800 hover:bg-red-200 dark:bg-red-900/70 dark:text-red-300',
                'Finalizado' => 'bg-gray-100 text-gray-800 hover:bg-gray-200 dark:bg-gray-700/80 dark:text-gray-300',
                'Pago' => 'bg-purple-100 text-purple-800 hover:bg-purple-200 dark:bg-purple-900/70 dark:text-purple-300',
            ];
            // Estilos para botões ATIVOS
            $activeStatusColors = [
                'todos' => 'bg-gray-500 text-white',
                'ativos' => 'bg-blue-600 text-white',
                'Analisando' => 'bg-yellow-500 text-white',
                'Aprovado' => 'bg-green-600 text-white',
                'Rejeitado' => 'bg-red-600 text-white',
                'Finalizado' => 'bg-gray-600 text-white',
                'Pago' => 'bg-purple-600 text-white',
            ];
        @endphp

        <button wire:click="setFiltroStatus('ativos')" class="px-3 py-1 text-sm font-semibold rounded-full transition-colors {{ $filtroStatus === 'ativos' ? $activeStatusColors['ativos'] : $statusColors['ativos'] }}">
            Ativos
        </button>
        <button wire:click="setFiltroStatus('')" class="px-3 py-1 text-sm font-semibold rounded-full transition-colors {{ $filtroStatus === '' ? $activeStatusColors['todos'] : $statusColors['todos'] }}">
            Todos
        </button>
        <div class="h-4 border-l border-gray-300 dark:border-gray-600 mx-2"></div>
        @foreach($statusDisponiveis as $status)
            <button wire:click="setFiltroStatus('{{ $status }}')" class="px-3 py-1 text-sm font-semibold rounded-full transition-colors {{ $filtroStatus === $status ? ($activeStatusColors[$status] ?? '') : ($statusColors[$status] ?? '') }}">
                {{ $status }}
            </button>
        @endforeach
    </div>

    {{-- TABELA DE ORÇAMENTOS --}}
    <div class="overflow-x-auto ">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="bg-gray-50 text-xs uppercase text-gray-700 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <button wire:click="sortBy('cliente_name')" class="flex items-center gap-1 uppercase">
                            Cliente
                            @if($sortField === 'cliente_name')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $sortDirection === 'asc' ? 'm5 15l7-7 7 7' : 'm19 9l-7 7-7-7' }}" /></svg>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3">Título</th>
                    <th scope="col" class="px-6 py-3">
                        <button wire:click="sortBy('status')" class="flex items-center gap-1 uppercase">
                            Status
                            @if($sortField === 'status')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $sortDirection === 'asc' ? 'm5 15l7-7 7 7' : 'm19 9l-7 7-7-7' }}" /></svg>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3 text-right">
                        <button wire:click="sortBy('valor_total')" class="flex items-center gap-1 ml-auto uppercase">
                            Valor
                            @if($sortField === 'valor_total')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $sortDirection === 'asc' ? 'm5 15l7-7 7 7' : 'm19 9l-7 7-7-7' }}" /></svg>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <button wire:click="sortBy('created_at')" class="flex items-center gap-1 uppercase">
                            Data
                            @if($sortField === 'created_at')
                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="{{ $sortDirection === 'asc' ? 'm5 15l7-7 7 7' : 'm19 9l-7 7-7-7' }}" /></svg>
                            @endif
                        </button>
                    </th>
                    <th scope="col" class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orcamentos as $orcamento)
                    @livewire('orcamento-row', ['orcamento' => $orcamento], key($orcamento->id))
                @empty
                    <tr>
                        <td class="px-6 py-4 text-center" colspan="6">Nenhum orçamento encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Links de Paginação --}}
    <div class="mt-6">
        {{ $orcamentos->links() }}
    </div>
</div>
