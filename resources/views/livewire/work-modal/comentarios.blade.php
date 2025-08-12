<div class="tab-content p-6">
    <!-- Header com Botão de Adicionar -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Comentários do Trabalho</h3>
        
        <button 
            wire:click="toggleFormComentario"
            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Adicionar Comentário
        </button>
    </div>

    <!-- Formulário de Novo Comentário -->
    @if($mostrarFormComentario)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-semibold text-gray-900 mb-4">Novo Comentário</h4>
            
            <form wire:submit.prevent="adicionarComentario">
                <div class="space-y-4">
                    <!-- Textarea do Comentário -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Comentário
                        </label>
                        <textarea 
                            wire:model="novoComentario"
                            rows="4"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Digite seu comentário..."
                        ></textarea>
                        @error('novoComentario') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Opções -->
                    <div class="flex items-center gap-6">
                        <label class="flex items-center gap-2">
                            <input 
                                type="checkbox" 
                                wire:model="comentarioVisivelCliente"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                            >
                            <span class="text-sm text-gray-700">Visível para o cliente</span>
                        </label>
                        
                        <label class="flex items-center gap-2">
                            <input 
                                type="checkbox" 
                                wire:model="comentarioImportante"
                                class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500"
                            >
                            <span class="text-sm text-gray-700">Marcar como importante</span>
                        </label>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center gap-3">
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                            wire:loading.attr="disabled"
                            wire:target="adicionarComentario"
                        >
                            <div wire:loading.remove wire:target="adicionarComentario">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                            </div>
                            <div wire:loading wire:target="adicionarComentario">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                            </div>
                            <span wire:loading.remove wire:target="adicionarComentario">Adicionar</span>
                            <span wire:loading wire:target="adicionarComentario">Adicionando...</span>
                        </button>
                        
                        <button 
                            type="button"
                            wire:click="toggleFormComentario"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 text-sm font-medium rounded-md hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Lista de Comentários -->
    @if($trabalho->comentarios->count() > 0)
        <!-- Filtros -->
        <div class="mb-4">
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">
                    Todos ({{ $trabalho->comentarios->count() }})
                </button>
                
                @php $importantes = $trabalho->comentarios->where('importante', true)->count(); @endphp
                @if($importantes > 0)
                    <button class="px-3 py-1 text-xs font-medium bg-yellow-100 text-yellow-700 rounded-full hover:bg-yellow-200">
                        Importantes ({{ $importantes }})
                    </button>
                @endif
                
                @php $visiveis = $trabalho->comentarios->where('visivel_cliente', true)->count(); @endphp
                @if($visiveis > 0)
                    <button class="px-3 py-1 text-xs font-medium bg-green-100 text-green-700 rounded-full hover:bg-green-200">
                        Visíveis ao cliente ({{ $visiveis }})
                    </button>
                @endif
                
                @php $internos = $trabalho->comentarios->where('visivel_cliente', false)->count(); @endphp
                @if($internos > 0)
                    <button class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200">
                        Internos ({{ $internos }})
                    </button>
                @endif
            </div>
        </div>

        <!-- Timeline de Comentários -->
        <div class="space-y-4">
            @foreach($trabalho->comentarios->sortByDesc('created_at') as $comentario)
                <div class="bg-white border border-gray-200 rounded-lg p-4 {{ $comentario->importante ? 'ring-2 ring-yellow-200 bg-yellow-50' : '' }}">
                    <!-- Header do Comentário -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3">
                            <!-- Avatar do Usuário -->
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ substr($comentario->usuario->name, 0, 1) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Informações do Usuário -->
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $comentario->usuario->name }}</span>
                                    
                                    <!-- Badges de Status -->
                                    <div class="flex items-center gap-1">
                                        @if($comentario->importante)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800 rounded-full">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                    </svg>
                                                Importante
                                            </span>
                                        @endif
                                        
                                        @if($comentario->visivel_cliente)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-green-100 text-green-800 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                                Visível
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                    </svg>
                                                Interno
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-2 text-xs text-gray-500">
                                    <time datetime="{{ $comentario->created_at->toISOString() }}">
                                        {{ $comentario->created_at->format('d/m/Y H:i') }}
                                    </time>
                                    <span>•</span>
                                    <span>{{ $comentario->tempo_decorrido }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Menu de Ações -->
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                @click.stop="open = !open"
                                class="text-gray-400 hover:text-gray-600 p-1 rounded"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v.01M12 12v.01M12 18v.01"></path>
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
                                    wire:click="marcarComentarioImportante({{ $comentario->id }})"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    @if($comentario->importante)
                                        <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                                        </svg>
                                        Desmarcar importante
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                        </svg>
                                        Marcar como importante
                                    @endif
                                </button>
                                
                                <button 
                                    wire:click="alternarVisibilidadeComentario({{ $comentario->id }})"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    @if($comentario->visivel_cliente)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                        Ocultar do cliente
                                    @else
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Mostrar ao cliente
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Conteúdo do Comentário -->
                    <div class="ml-11">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $comentario->comentario }}</p>
                        </div>
                        
                        <!-- Resumo (se o comentário for longo) -->
                        @if(strlen($comentario->comentario) > 200)
                            <div class="mt-2">
                                <button class="text-xs text-blue-600 hover:text-blue-800">
                                    Mostrar menos
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Resumo dos Comentários -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Resumo dos Comentários
            </h4>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-lg font-bold text-blue-600">{{ $trabalho->comentarios->count() }}</div>
                    <div class="text-xs text-blue-700">Total</div>
                </div>
                
                <div>
                    <div class="text-lg font-bold text-blue-600">{{ $trabalho->comentarios->where('importante', true)->count() }}</div>
                    <div class="text-xs text-blue-700">Importantes</div>
                </div>
                
                <div>
                    <div class="text-lg font-bold text-blue-600">{{ $trabalho->comentarios->where('visivel_cliente', true)->count() }}</div>
                    <div class="text-xs text-blue-700">Visíveis ao cliente</div>
                </div>
                
                <div>
                    @php
                        $ultimoComentario = $trabalho->comentarios->sortByDesc('created_at')->first();
                        $diasUltimoComentario = $ultimoComentario ? $ultimoComentario->created_at->diffInDays(now()) : 0;
                    @endphp
                    <div class="text-lg font-bold text-blue-600">{{ $diasUltimoComentario }}</div>
                    <div class="text-xs text-blue-700">Dias desde último</div>
                </div>
            </div>
        </div>
    @else
        <!-- Estado Vazio -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum comentário encontrado</h3>
            <p class="mt-1 text-sm text-gray-500">
                Adicione comentários para registrar observações e comunicar-se sobre o trabalho.
            </p>
            
            <button 
                wire:click="toggleFormComentario"
                class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 flex items-center gap-2 mx-auto"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Adicionar Primeiro Comentário
            </button>
        </div>
    @endif
</div>

@push('styles')
<style>
/* Destaque para comentários importantes */
.comentario-importante {
    background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
    border-color: #f59e0b;
}

/* Animação para novos comentários */
.novo-comentario {
    animation: slideInFromTop 0.3s ease-out;
}

@keyframes slideInFromTop {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Estilo para comentários longos */
.comentario-expandido {
    max-height: none;
}

.comentario-colapsado {
    max-height: 100px;
    overflow: hidden;
    position: relative;
}

.comentario-colapsado::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 20px;
    background: linear-gradient(transparent, #f9fafb);
}

/* Avatar personalizado */
.avatar-usuario {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

/* Badges responsivos */
@media (max-width: 640px) {
    .badges-comentario {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.25rem;
    }
}

/* Loading animation */
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
@endpush