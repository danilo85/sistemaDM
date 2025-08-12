<div class="tab-content p-6">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Informações Principais -->
        <div class="space-y-6">
            <!-- Informações Básicas -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Básicas</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Cliente:</span>
                        <span class="text-sm text-gray-900">{{ $trabalho->cliente->name ?? 'Não definido' }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Responsável:</span>
                        <span class="text-sm text-gray-900">{{ $trabalho->responsavel->name ?? 'Não definido' }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Categoria:</span>
                        <span class="text-sm text-gray-900">{{ $trabalho->categoria->name ?? 'Não definida' }}</span>
                    </div>
                    
                    <div class="flex justify-between">
                        <span class="text-sm font-medium text-gray-500">Status:</span>
                        <span class="text-sm px-2 py-1 rounded-full {{ $trabalho->status_classe }}">
                            {{ $trabalho->status_label }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Valores e Prazos -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Valores e Prazos</h3>
                
                <div class="space-y-3">
                    @if($trabalho->valor_total)
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Valor Total:</span>
                            <span class="text-sm font-semibold text-green-600">
                                R$ {{ number_format($trabalho->valor_total, 2, ',', '.') }}
                            </span>
                        </div>
                    @endif
                    
                    @if($trabalho->prazo_entrega)
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Prazo de Entrega:</span>
                            <span class="text-sm {{ $trabalho->prazo_vencido ? 'text-red-600 font-semibold' : 'text-gray-900' }}">
                                {{ $trabalho->prazo_entrega->format('d/m/Y') }}
                                @if($trabalho->prazo_vencido)
                                    <span class="text-xs">(Vencido)</span>
                                @else
                                    <span class="text-xs text-gray-500">
                                        ({{ $trabalho->prazo_entrega->diffForHumans() }})
                                    </span>
                                @endif
                            </span>
                        </div>
                    @endif
                    
                    @if($trabalho->data_inicio)
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Data de Início:</span>
                            <span class="text-sm text-gray-900">{{ $trabalho->data_inicio->format('d/m/Y') }}</span>
                        </div>
                    @endif
                    
                    @if($trabalho->data_finalizacao)
                        <div class="flex justify-between">
                            <span class="text-sm font-medium text-gray-500">Data de Finalização:</span>
                            <span class="text-sm text-gray-900">{{ $trabalho->data_finalizacao->format('d/m/Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Alertas e Indicadores -->
            @if($trabalho->alerta_prazo || $trabalho->alerta_orcamento || $trabalho->contador_retrabalho > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        Alertas
                    </h3>
                    
                    <div class="space-y-2">
                        @if($trabalho->alerta_prazo)
                            <div class="flex items-center gap-2 text-sm text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Prazo de entrega vencido</span>
                            </div>
                        @endif
                        
                        @if($trabalho->alerta_orcamento)
                            <div class="flex items-center gap-2 text-sm text-yellow-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span>Orçamento pendente de aprovação</span>
                            </div>
                        @endif
                        
                        @if($trabalho->contador_retrabalho > 0)
                            <div class="flex items-center gap-2 text-sm text-orange-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span>{{ $trabalho->contador_retrabalho }} retrabalho(s) registrado(s)</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Descrição e Configurações -->
        <div class="space-y-6">
            <!-- Descrição -->
            @if($trabalho->descricao)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Descrição</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $trabalho->descricao }}</p>
                    </div>
                </div>
            @endif

            <!-- Orçamento Relacionado -->
            @if($trabalho->orcamento)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Orçamento</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-blue-900">
                                #{{ $trabalho->orcamento->numero }} - {{ $trabalho->orcamento->titulo }}
                            </span>
                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded-full">
                                {{ ucfirst($trabalho->orcamento->status) }}
                            </span>
                        </div>
                        
                        @if($trabalho->orcamento->data_emissao)
                            <p class="text-xs text-blue-700">
                                Emitido em {{ $trabalho->orcamento->data_emissao->format('d/m/Y') }}
                            </p>
                        @endif
                        
                        @if($trabalho->orcamento->data_validade)
                            <p class="text-xs text-blue-700">
                                Válido até {{ $trabalho->orcamento->data_validade->format('d/m/Y') }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Configurações -->
            @if($trabalho->configuracoes && count($trabalho->configuracoes) > 0)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Configurações</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="space-y-2">
                            @foreach($trabalho->configuracoes as $chave => $valor)
                                <div class="flex justify-between text-sm">
                                    <span class="font-medium text-gray-500">{{ ucfirst(str_replace('_', ' ', $chave)) }}:</span>
                                    <span class="text-gray-900">{{ is_bool($valor) ? ($valor ? 'Sim' : 'Não') : $valor }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Portfólio -->
            @if($trabalho->portfolio_ativo || $trabalho->portfolio)
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Portfólio</h3>
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        @if($trabalho->portfolio_ativo)
                            <div class="flex items-center gap-2 text-sm text-purple-700 mb-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium">Ativo no portfólio</span>
                            </div>
                        @endif
                        
                        @if($trabalho->portfolio)
                            <p class="text-xs text-purple-600">
                                Publicado em {{ $trabalho->portfolio->created_at->format('d/m/Y') }}
                            </p>
                        @else
                            <p class="text-xs text-purple-600">
                                {{ $trabalho->pronto_para_portfolio ? 'Pronto para publicação' : 'Aguardando thumbnail e descrição' }}
                            </p>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Estatísticas -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Estatísticas</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-blue-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-blue-600">{{ $trabalho->arquivos->count() }}</div>
                        <div class="text-xs text-blue-700">Arquivos</div>
                    </div>
                    
                    <div class="bg-green-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $trabalho->comentarios->count() }}</div>
                        <div class="text-xs text-green-700">Comentários</div>
                    </div>
                    
                    <div class="bg-yellow-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $trabalho->historico->count() }}</div>
                        <div class="text-xs text-yellow-700">Movimentações</div>
                    </div>
                    
                    <div class="bg-purple-50 rounded-lg p-3 text-center">
                        <div class="text-2xl font-bold text-purple-600">{{ $trabalho->dias_em_andamento }}</div>
                        <div class="text-xs text-purple-700">Dias</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>