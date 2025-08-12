<div class="kanban-container" x-data="kanbanBoard()" x-init="init()">
    <!-- Header com Filtros -->
    <div class="bg-white shadow-sm border-b border-gray-200 p-4 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <!-- Título e Métricas -->
            <div class="flex items-center gap-6">
                <h1 class="text-2xl font-bold text-gray-900">Kanban de Trabalhos</h1>
                
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-600">{{ $metricas['total'] }} trabalhos</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                        <span class="text-gray-600">{{ $metricas['em_andamento'] }} em andamento</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-gray-600">{{ $metricas['trabalhos_atrasados'] }} atrasados</span>
                    </div>
                </div>
            </div>

            <!-- Controles -->
            <div class="flex items-center gap-3">
                <button 
                    wire:click="alternarAgrupamento"
                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                    </svg>
                    {{ $agrupadoPorCliente ? 'Agrupar por Status' : 'Agrupar por Cliente' }}
                </button>
                
                <button 
                    wire:click="limparFiltros"
                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Limpar Filtros
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- Busca -->
            <div class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="filtros.busca"
                    placeholder="Buscar trabalhos..."
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <!-- Cliente -->
            <select 
                wire:model.live="filtros.cliente_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Todos os clientes</option>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}">{{ $cliente->name }}</option>
                @endforeach
            </select>

            <!-- Categoria -->
            <select 
                wire:model.live="filtros.categoria_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Todas as categorias</option>
                @foreach($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->name }}</option>
                @endforeach
            </select>

            <!-- Responsável -->
            <select 
                wire:model.live="filtros.responsavel_id"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Todos os responsáveis</option>
                @foreach($responsaveis as $responsavel)
                    <option value="{{ $responsavel->id }}">{{ $responsavel->name }}</option>
                @endforeach
            </select>

            <!-- Prazo -->
            <select 
                wire:model.live="filtros.prazo"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            >
                <option value="">Todos os prazos</option>
                <option value="vencido">Vencidos</option>
                <option value="hoje">Vencem hoje</option>
                <option value="semana">Vencem esta semana</option>
                <option value="mes">Vencem este mês</option>
            </select>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div wire:loading.flex class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center">
        <div class="bg-white rounded-lg p-6 flex items-center gap-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
            <span class="text-gray-700">Carregando...</span>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="relative">
        <!-- Botões de Navegação -->
        <button 
            @click="scrollKanban('left')"
            class="scroll-button absolute left-2 top-1/2 transform -translate-y-1/2 z-10 bg-white shadow-lg rounded-full p-2 hover:bg-gray-50 border border-gray-200"
            x-show="canScrollLeft"
            x-transition
            title="Navegar para esquerda (←)"
        >
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        
        <button 
            @click="scrollKanban('right')"
            class="scroll-button absolute right-2 top-1/2 transform -translate-y-1/2 z-10 bg-white shadow-lg rounded-full p-2 hover:bg-gray-50 border border-gray-200"
            x-show="canScrollRight"
            x-transition
            title="Navegar para direita (→)"
        >
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        
        <div 
            class="kanban-board overflow-x-auto scroll-smooth"
            x-ref="kanbanContainer"
            @scroll="updateScrollButtons()"
        >
            <div class="flex gap-6 min-w-max pb-6">
            @foreach($statusColunas as $status => $coluna)
                <div class="kanban-column bg-gray-50 rounded-lg p-4 min-w-80 max-w-80">
                    <!-- Header da Coluna -->
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $coluna['cor'] }}"></div>
                            <h3 class="font-semibold text-gray-900">{{ $coluna['titulo'] }}</h3>
                            <span class="bg-gray-200 text-gray-700 text-xs px-2 py-1 rounded-full">
                                {{ count($trabalhosPorStatus[$status] ?? []) }}
                            </span>
                        </div>
                        
                        @if($coluna['permite_adicionar'] ?? false)
                            <button class="text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Lista de Trabalhos -->
                    <div 
                        class="kanban-cards space-y-3 min-h-32"
                        data-status="{{ $status }}"
                        x-ref="column_{{ $status }}"
                    >
                        @foreach($trabalhosPorStatus[$status] ?? [] as $trabalho)
                            <livewire:work-card 
                                :trabalho="$trabalho" 
                                :key="'work-card-' . $trabalho->id"
                            />
                        @endforeach
                    </div>
                </div>
            @endforeach
            </div>
        </div>
    </div>

    <!-- Modal de Trabalho -->
    <div 
        x-show="$wire.trabalhoSelecionado"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        style="display: none;"
    >
        <div 
            class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-hidden"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-95"
            @click.stop
        >
            @if($trabalhoSelecionado)
                <livewire:work-modal :trabalho-id="$trabalhoSelecionado" :key="'work-modal-' . $trabalhoSelecionado" />
            @endif
        </div>
    </div>


</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
function kanbanBoard() {
    return {
        sortables: [],
        canScrollLeft: false,
        canScrollRight: false,
        
        init() {
            // Inicializar estado dos botões de scroll
            this.$nextTick(() => {
                this.updateScrollButtons();
            });
            this.initSortable();
            
            // Adicionar navegação por teclado
            document.addEventListener('keydown', (e) => {
                // Verificar se não está em um input/textarea
                if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                
                if (e.key === 'ArrowLeft' && this.canScrollLeft) {
                    e.preventDefault();
                    this.scrollKanban('left');
                } else if (e.key === 'ArrowRight' && this.canScrollRight) {
                    e.preventDefault();
                    this.scrollKanban('right');
                }
            });
            
            // Escutar eventos do Livewire
            Livewire.on('recarregarKanban', () => {
                this.$nextTick(() => {
                    this.destroySortables();
                    this.initSortable();
                });
            });
            
            // Escutar evento de atualização do kanban
            Livewire.on('kanbanAtualizado', () => {
                // Aguardar o próximo tick do DOM
                this.$nextTick(() => {
                    // Reaplica as classes de transição
                    document.querySelectorAll('.work-card').forEach(card => {
                        card.style.transition = 'transform 0.2s ease';
                    });
                    
                    // Reinicializar sortables após atualização
                    this.destroySortables();
                    this.initSortable();
                });
            });
            

        },
        
        initSortable() {
            const columns = document.querySelectorAll('.kanban-cards');
            
            columns.forEach(column => {
                const sortable = new Sortable(column, {
                    group: 'kanban',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    
                    onEnd: (evt) => {
                        const trabalhoId = evt.item.dataset.trabalhoId;
                        const novoStatus = evt.to.dataset.status;
                        const novaPosicao = evt.newIndex;
                        const statusAnterior = evt.from.dataset.status;
                        
                        // Adicionar feedback visual imediato
                        evt.item.style.opacity = '0.7';
                        
                        // Se mudou de coluna (status)
                        if (novoStatus !== statusAnterior) {
                            @this.moverTrabalho(trabalhoId, novoStatus, novaPosicao).then(() => {
                                // Restaurar opacidade após sucesso
                                evt.item.style.opacity = '1';
                            }).catch(() => {
                                // Em caso de erro, reverter posição
                                evt.item.style.opacity = '1';
                            });
                        } else {
                            // Apenas mudou posição na mesma coluna
                            @this.atualizarPosicao(trabalhoId, novaPosicao).then(() => {
                                evt.item.style.opacity = '1';
                            }).catch(() => {
                                evt.item.style.opacity = '1';
                            });
                        }
                    },
                    
                    onMove: (evt) => {
                        // Validar se o movimento é permitido
                        const trabalhoId = evt.dragged.dataset.trabalhoId;
                        const novoStatus = evt.to.dataset.status;
                        
                        // Aqui você pode adicionar validações específicas
                        return true;
                    }
                });
                
                this.sortables.push(sortable);
            });
        },
        
        destroySortables() {
            this.sortables.forEach(sortable => {
                if (sortable) {
                    sortable.destroy();
                }
            });
            this.sortables = [];
        },
        
        scrollKanban(direction) {
            const container = this.$refs.kanbanContainer;
            const scrollAmount = 320; // Largura de uma coluna + gap
            
            if (direction === 'left') {
                container.scrollBy({
                    left: -scrollAmount,
                    behavior: 'smooth'
                });
            } else {
                container.scrollBy({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }
        },
        
        updateScrollButtons() {
            const container = this.$refs.kanbanContainer;
            if (!container) return;
            
            this.canScrollLeft = container.scrollLeft > 0;
            this.canScrollRight = container.scrollLeft < (container.scrollWidth - container.clientWidth);
        }
    }
}
</script>
@endpush

@push('styles')
<style>
.kanban-container {
    min-height: calc(100vh - 200px);
}

.kanban-board {
    padding: 0 1rem;
}

.kanban-column {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
}

.kanban-cards {
    min-height: 100px;
}

.sortable-ghost {
    opacity: 0.5;
    background: #e2e8f0;
    border: 2px dashed #cbd5e0;
}

.sortable-chosen {
    transform: rotate(5deg);
}

.sortable-drag {
    opacity: 0.8;
    transform: rotate(5deg);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

/* Scrollbar personalizada */
.kanban-board {
    scroll-behavior: smooth;
}

.kanban-board::-webkit-scrollbar {
    height: 8px;
}

.kanban-board::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 4px;
}

.kanban-board::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

.kanban-board::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Botões de navegação */
.scroll-button {
    transition: all 0.2s ease;
    backdrop-filter: blur(4px);
}

.scroll-button:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Scroll suave para mobile */
@media (max-width: 768px) {
    .kanban-board {
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
    }
    
    .kanban-column {
        scroll-snap-align: start;
        min-width: 300px;
    }
}

/* Animações */
.kanban-cards > * {
    transition: transform 0.2s ease;
}

.kanban-cards > *:hover {
    transform: translateY(-2px);
}

/* Responsivo */
@media (max-width: 768px) {
    .kanban-column {
        min-width: 280px;
    }
}
</style>
@endpush