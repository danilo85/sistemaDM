<div class="tab-content p-6">
    <div class="space-y-4">
        @if($trabalho->historico->count() > 0)
            <!-- Timeline do Histórico -->
            <div class="relative">
                @foreach($trabalho->historico->sortByDesc('created_at') as $index => $item)
                    <div class="relative flex items-start space-x-3 {{ !$loop->last ? 'pb-6' : '' }}">
                        <!-- Linha da Timeline -->
                        @if(!$loop->last)
                            <div class="absolute left-4 top-8 -ml-px h-full w-0.5 bg-gray-200"></div>
                        @endif
                        
                        <!-- Ícone do Status -->
                        <div class="relative flex h-8 w-8 items-center justify-center rounded-full {{ $item->status_cor_fundo }} ring-8 ring-white">
                            @if($item->status_novo === 'backlog')
                                <svg class="h-4 w-4 {{ $item->status_cor_texto }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                 </svg>
                            @elseif($item->status_novo === 'em_producao')
                                <svg class="h-4 w-4 {{ $item->status_cor_texto }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                 </svg>
                            @elseif($item->status_novo === 'em_revisao')
                                <svg class="h-4 w-4 {{ $item->status_cor_texto }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                 </svg>
                            @elseif($item->status_novo === 'finalizado')
                                <svg class="h-4 w-4 {{ $item->status_cor_texto }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @elseif($item->status_novo === 'publicado')
                                <svg class="h-4 w-4 {{ $item->status_cor_texto }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                </svg>
                            @else
                                <svg class="h-4 w-4 {{ $item->status_cor_texto }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            @endif
                        </div>
                        
                        <!-- Conteúdo do Item -->
                        <div class="min-w-0 flex-1">
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <!-- Header do Item -->
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center space-x-2">
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $item->descricao_movimentacao }}
                                        </span>
                                        
                                        @if($item->status_anterior && $item->status_novo)
                                            <div class="flex items-center space-x-1 text-xs">
                                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded">
                                                    {{ $item->status_anterior_label }}
                                                </span>
                                                <svg class="h-3 w-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                                </svg>
                                                <span class="px-2 py-1 {{ $item->status_novo_classe }} rounded">
                                                    {{ $item->status_novo_label }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <time class="text-xs text-gray-500" datetime="{{ $item->created_at->toISOString() }}">
                                        {{ $item->created_at->format('d/m/Y H:i') }}
                                    </time>
                                </div>
                                
                                <!-- Usuário Responsável -->
                                <div class="flex items-center space-x-2 mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="h-6 w-6 rounded-full bg-gray-300 flex items-center justify-center">
                                            <span class="text-xs font-medium text-gray-700">
                                                {{ substr($item->usuario->name, 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                    <span class="text-sm text-gray-600">{{ $item->usuario->name }}</span>
                                </div>
                                
                                <!-- Observações -->
                                @if($item->observacoes)
                                    <div class="bg-gray-50 rounded-md p-3 mb-3">
                                        <p class="text-sm text-gray-700">{{ $item->observacoes }}</p>
                                    </div>
                                @endif
                                
                                <!-- Dados Adicionais -->
                                @if($item->dados_adicionais && count($item->dados_adicionais) > 0)
                                    <div class="border-t border-gray-100 pt-3">
                                        <details class="group">
                                            <summary class="flex items-center justify-between cursor-pointer text-sm text-gray-600 hover:text-gray-900">
                                                <span>Dados adicionais</span>
                                                <svg class="h-4 w-4 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </summary>
                                            
                                            <div class="mt-2 space-y-1">
                                                @foreach($item->dados_adicionais as $chave => $valor)
                                                    <div class="flex justify-between text-xs">
                                                        <span class="font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $chave)) }}:</span>
                                                        <span class="text-gray-700">{{ is_array($valor) ? json_encode($valor) : $valor }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </details>
                                    </div>
                                @endif
                                
                                <!-- Tempo Decorrido -->
                                <div class="flex items-center justify-between text-xs text-gray-400 mt-3 pt-2 border-t border-gray-100">
                                    <span>{{ $item->created_at->diffForHumans() }}</span>
                                    
                                    @if(!$loop->last)
                                        @php
                                            $proximoItem = $trabalho->historico->sortByDesc('created_at')->values()[$index + 1] ?? null;
                                            $tempoNaEtapa = $proximoItem ? $item->created_at->diffInHours($proximoItem->created_at) : null;
                                        @endphp
                                        
                                        @if($tempoNaEtapa)
                                            <span>
                                                {{ $tempoNaEtapa < 24 ? $tempoNaEtapa . 'h' : round($tempoNaEtapa / 24, 1) . ' dias' }} na etapa anterior
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Resumo do Histórico -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Resumo do Histórico
                </h4>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-lg font-bold text-blue-600">{{ $trabalho->historico->count() }}</div>
                        <div class="text-xs text-blue-700">Movimentações</div>
                    </div>
                    
                    <div>
                        <div class="text-lg font-bold text-blue-600">{{ $trabalho->dias_em_andamento }}</div>
                        <div class="text-xs text-blue-700">Dias em andamento</div>
                    </div>
                    
                    <div>
                        <div class="text-lg font-bold text-blue-600">{{ $trabalho->contador_retrabalho }}</div>
                        <div class="text-xs text-blue-700">Retrabalhos</div>
                    </div>
                    
                    <div>
                        @php
                            $ultimaMovimentacao = $trabalho->historico->sortByDesc('created_at')->first();
                            $tempoUltimaMovimentacao = $ultimaMovimentacao ? $ultimaMovimentacao->created_at->diffInDays(now()) : 0;
                        @endphp
                        <div class="text-lg font-bold text-blue-600">{{ $tempoUltimaMovimentacao }}</div>
                        <div class="text-xs text-blue-700">Dias sem movimento</div>
                    </div>
                </div>
            </div>
        @else
            <!-- Estado Vazio -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum histórico encontrado</h3>
                <p class="mt-1 text-sm text-gray-500">
                    As movimentações do trabalho aparecerão aqui conforme forem realizadas.
                </p>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
/* Cores para diferentes status no histórico */
.status-backlog {
    @apply bg-gray-100 text-gray-800;
}

.status-em-producao {
    @apply bg-blue-100 text-blue-800;
}

.status-em-revisao {
    @apply bg-yellow-100 text-yellow-800;
}

.status-finalizado {
    @apply bg-green-100 text-green-800;
}

.status-publicado {
    @apply bg-purple-100 text-purple-800;
}

/* Animação suave para o details */
details[open] summary ~ * {
    animation: slideDown 0.2s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Timeline responsiva */
@media (max-width: 640px) {
    .timeline-item {
        padding-left: 2rem;
    }
    
    .timeline-line {
        left: 1rem;
    }
    
    .timeline-icon {
        left: 0.75rem;
    }
}
</style>
@endpush