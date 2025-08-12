<div class="work-modal flex flex-col h-full max-h-[90vh]">
    <!-- Header do Modal -->
    <div class="flex items-center justify-between p-6 border-b border-gray-200">
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full {{ $trabalho->status_cor }}"></div>
                <h2 class="text-xl font-semibold text-gray-900">{{ $trabalho->titulo }}</h2>
            </div>
            
            <span class="px-2 py-1 text-xs font-medium rounded-full {{ $trabalho->status_classe }}">
                {{ $trabalho->status_label }}
            </span>
        </div>
        
        <button 
            wire:click="fecharModal"
            class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navegação por Abas -->
    <div class="border-b border-gray-200">
        <nav class="flex space-x-8 px-6" aria-label="Tabs">
            @foreach($abas as $aba => $config)
                <button 
                    wire:click="selecionarAba('{{ $aba }}')"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors duration-200 {{ 
                        $abaSelecionada === $aba 
                            ? 'border-blue-500 text-blue-600' 
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    }}"
                >
                    <div class="flex items-center gap-2">
                        @if($config['icone'] === 'information-circle')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @elseif($config['icone'] === 'clock')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @elseif($config['icone'] === 'document')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        @elseif($config['icone'] === 'chat-bubble-left')
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                        @else
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                        {{ $config['titulo'] }}
                        @if($config['contador'])
                            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded-full">
                                {{ $config['contador'] }}
                            </span>
                        @endif
                    </div>
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Conteúdo das Abas -->
    <div class="flex-1 overflow-y-auto">
        @if($abaSelecionada === 'detalhes')
            @include('livewire.work-modal.detalhes')
        @elseif($abaSelecionada === 'historico')
            @include('livewire.work-modal.historico')
        @elseif($abaSelecionada === 'arquivos')
            @include('livewire.work-modal.arquivos')
        @elseif($abaSelecionada === 'comentarios')
            @include('livewire.work-modal.comentarios')
        @endif
    </div>

    <!-- Footer com Ações -->
    <div class="border-t border-gray-200 p-6">
        <div class="flex items-center justify-between">
            <!-- Ações Principais -->
            <div class="flex items-center gap-3">
                @if($trabalho->status !== 'publicado')
                    <!-- Botões de Mudança de Status -->
                    @foreach($trabalho->proximos_status_permitidos as $status)
                        <button 
                            wire:click="alterarStatus('{{ $status }}')"
                            class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 {{
                                $status === 'publicado' 
                                    ? 'bg-green-600 text-white hover:bg-green-700'
                                    : 'bg-blue-600 text-white hover:bg-blue-700'
                            }}"
                        >
                            {{ \App\Models\Trabalho::getStatusLabel($status) }}
                        </button>
                    @endforeach
                @endif
            </div>

            <!-- Ações Secundárias -->
            <div class="flex items-center gap-2">
                <button 
                    wire:click="enviarLembrete"
                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                >
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    Lembrete
                </button>
                
                @if($trabalho->status === 'finalizado')
                    <button 
                        wire:click="criarLancamentoReceita"
                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Receita
                    </button>
                    
                    @if($trabalho->pronto_para_portfolio)
                        <button 
                            wire:click="ativarNoPortfolio"
                            class="px-3 py-2 text-sm font-medium text-white bg-purple-600 rounded-md hover:bg-purple-700"
                        >
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Portfólio
                        </button>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.work-modal {
    min-height: 600px;
}

/* Scrollbar personalizada */
.work-modal .overflow-y-auto::-webkit-scrollbar {
    width: 6px;
}

.work-modal .overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.work-modal .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
}

.work-modal .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Animações para transições de aba */
.tab-content {
    animation: fadeIn 0.2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush