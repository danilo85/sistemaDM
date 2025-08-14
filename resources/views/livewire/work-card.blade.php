<div 
    class="work-card rounded-lg shadow-sm border p-4 cursor-pointer hover:shadow-md transition-all duration-200 {{ $this->corCard }}"
    data-trabalho-id="{{ $trabalho->id }}"
    wire:click="abrirDetalhes"
>
    <!-- Header do Card -->
    <div class="flex items-start justify-between mb-3">
        <div class="flex-1 min-w-0">
            <h4 class="text-sm font-semibold text-gray-900 whitespace-nowrap overflow-hidden text-ellipsis" title="{{ $trabalho->titulo }}">
                {{ $trabalho->titulo }}
            </h4>
            <p class="text-xs text-gray-500 mt-1">
                {{ $trabalho->cliente->name ?? 'Cliente não definido' }}
            </p>
        </div>
        
        <!-- Menu de Ações -->
        <div class="relative" x-data="{ open: false }">
            <button 
                @click.stop="open = !open"
                class="text-gray-400 hover:text-gray-600 p-1 rounded"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
            </button>
            
            <div 
                x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 top-8 w-48 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-10"
                style="display: none;"
            >
                <button 
                    wire:click.stop="enviarLembrete"
                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    Enviar Lembrete
                </button>
                
                <!-- Divisor -->
                <div class="border-t border-gray-100 my-1"></div>
                
                <!-- Opções de Cores -->
                <div class="px-4 py-2">
                    <p class="text-xs text-gray-500 mb-2">Cor do Card:</p>
                    <div class="flex gap-2">
                        <button 
                            wire:click.stop="alterarCor('bg-blue-50 border-blue-200')"
                            class="w-6 h-6 rounded bg-blue-50 border-2 border-blue-200 hover:scale-110 transition-transform"
                            title="Azul"
                        ></button>
                        <button 
                            wire:click.stop="alterarCor('bg-green-50 border-green-200')"
                            class="w-6 h-6 rounded bg-green-50 border-2 border-green-200 hover:scale-110 transition-transform"
                            title="Verde"
                        ></button>
                        <button 
                            wire:click.stop="alterarCor('bg-purple-50 border-purple-200')"
                            class="w-6 h-6 rounded bg-purple-50 border-2 border-purple-200 hover:scale-110 transition-transform"
                            title="Roxo"
                        ></button>
                        <button 
                            wire:click.stop="alterarCor('bg-pink-50 border-pink-200')"
                            class="w-6 h-6 rounded bg-pink-50 border-2 border-pink-200 hover:scale-110 transition-transform"
                            title="Rosa"
                        ></button>
                    </div>
                </div>
                
                @if($trabalho->status === 'finalizado')
                    <button 
                        wire:click.stop="criarLancamentoReceita"
                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        Lançar Receita
                    </button>
                    
                    @if($trabalho->pronto_para_portfolio)
                        <button 
                            wire:click.stop="ativarNoPortfolio"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            Ativar no Portfólio
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Descrição -->
    @if($trabalho->descricao)
        <p class="text-xs text-gray-600 mb-3 line-clamp-2">
            {{ Str::limit($trabalho->descricao, 80) }}
        </p>
    @endif

    <!-- Informações do Trabalho -->
    <div class="space-y-2 mb-3">
        <!-- Responsável -->
        @if($trabalho->responsavel)
            <div class="flex items-center gap-2 text-xs">
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                <span class="text-gray-600">{{ $trabalho->responsavel->name }}</span>
            </div>
        @endif

        <!-- Categoria -->
        @if($trabalho->categoria)
            <div class="flex items-center gap-2 text-xs">
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span class="text-gray-600">{{ $trabalho->categoria->name }}</span>
            </div>
        @endif

        <!-- Valor -->
        @if($trabalho->valor_total)
            <div class="flex items-center gap-2 text-xs">
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-gray-600 font-medium">R$ {{ number_format($trabalho->valor_total, 2, ',', '.') }}</span>
            </div>
        @endif

        <!-- Prazo -->
        @if($trabalho->prazo_entrega)
            <div class="flex items-center gap-2 text-xs">
                <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="text-gray-600 {{ $trabalho->prazo_vencido ? 'text-red-600 font-medium' : '' }}">
                    {{ $trabalho->prazo_entrega->format('d/m/Y') }}
                </span>
            </div>
        @endif
    </div>

    <!-- Indicadores -->
    <div class="flex items-center justify-between">
        <!-- Badges de Status -->
        <div class="flex items-center gap-1">
            <!-- Indicador de Pagamento -->
            @if(isset($this->indicadores['pagamento']))
                <div class="w-2 h-2 bg-green-500 rounded-full" title="{{ $this->indicadores['pagamento']['tooltip'] ?? 'Pagamento' }}"></div>
            @endif

            <!-- Indicador de Arquivos -->
            @if(isset($this->indicadores['arquivos']))
                <div class="flex items-center gap-1 text-xs text-gray-500">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    <span>{{ $this->indicadores['arquivos']['contador'] ?? 0 }}</span>
                </div>
            @endif

            <!-- Indicador de Comentários -->
            @if(isset($this->indicadores['comentarios']))
                <div class="flex items-center gap-1 text-xs text-gray-500">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    <span>{{ $this->indicadores['comentarios']['contador'] ?? 0 }}</span>
                </div>
            @endif

            <!-- Indicador de Portfólio -->
            @if(isset($this->indicadores['portfolio']))
                <div class="w-2 h-2 bg-purple-500 rounded-full" title="{{ $this->indicadores['portfolio']['tooltip'] ?? 'Portfólio' }}"></div>
            @endif
        </div>

        <!-- Alertas -->
        <div class="flex items-center gap-1">
            <!-- Alerta de Prazo -->
            @if($trabalho->alerta_prazo)
                <div class="w-2 h-2 bg-red-500 rounded-full animate-pulse" title="Prazo vencido"></div>
            @endif

            <!-- Alerta de Orçamento -->
            @if($trabalho->alerta_orcamento)
                <div class="w-2 h-2 bg-yellow-500 rounded-full animate-pulse" title="Orçamento pendente"></div>
            @endif

            <!-- Alerta de Retrabalho -->
            @if($trabalho->contador_retrabalho > 0)
                <div class="flex items-center gap-1 text-xs text-orange-600">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>{{ $trabalho->contador_retrabalho }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Barra de Progresso (se aplicável) -->
    @if($trabalho->status === 'em_producao' && $trabalho->progresso_percentual)
        <div class="mt-3">
            <div class="flex items-center justify-between text-xs text-gray-500 mb-1">
                <span>Progresso</span>
                <span>{{ $trabalho->progresso_percentual }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-1.5">
                <div 
                    class="bg-blue-500 h-1.5 rounded-full transition-all duration-300"
                    style="width: {{ $trabalho->progresso_percentual }}%"
                ></div>
            </div>
        </div>
    @endif

    <!-- Footer com Data de Última Movimentação -->
    @if($trabalho->data_ultima_movimentacao)
        <div class="mt-3 pt-2 border-t border-gray-100">
            <p class="text-xs text-gray-400">
                Atualizado {{ $trabalho->data_ultima_movimentacao->diffForHumans() }}
            </p>
        </div>
    @endif
</div>

@push('styles')
<style>
.work-card {
    transition: all 0.2s ease;
}

.work-card:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Animação para alertas */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Cores por status */
.work-card[data-status="backlog"] {
    border-left: 4px solid #6b7280;
}

.work-card[data-status="em_producao"] {
    border-left: 4px solid #3b82f6;
}

.work-card[data-status="em_revisao"] {
    border-left: 4px solid #f59e0b;
}

.work-card[data-status="finalizado"] {
    border-left: 4px solid #10b981;
}

.work-card[data-status="publicado"] {
    border-left: 4px solid #8b5cf6;
}
</style>
@endpush