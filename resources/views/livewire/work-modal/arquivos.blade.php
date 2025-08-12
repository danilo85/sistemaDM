<div class="tab-content p-6">
    <!-- Header com Botão de Upload -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-lg font-semibold text-gray-900">Arquivos do Trabalho</h3>
        
        <button 
            wire:click="toggleFormArquivo"
            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Adicionar Arquivo
        </button>
    </div>

    <!-- Formulário de Upload -->
    @if($mostrarFormArquivo)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
            <h4 class="text-sm font-semibold text-gray-900 mb-4">Enviar Novo Arquivo</h4>
            
            <form wire:submit.prevent="uploadArquivos">
                <div class="space-y-4">
                    <!-- Upload de Arquivos -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Selecionar Arquivos
                        </label>
                        <input 
                            type="file" 
                            wire:model="arquivos" 
                            multiple
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                        >
                        @error('arquivos.*') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Categoria -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Categoria
                        </label>
                        <select 
                            wire:model="categoriaArquivo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            @foreach($categoriaArquivos as $valor => $label)
                                <option value="{{ $valor }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('categoriaArquivo') 
                            <span class="text-red-500 text-xs mt-1">{{ $message }}</span> 
                        @enderror
                    </div>

                    <!-- Descrição -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Descrição (opcional)
                        </label>
                        <textarea 
                            wire:model="descricaoArquivo"
                            rows="2"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Descreva o arquivo..."
                        ></textarea>
                    </div>

                    <!-- Botões -->
                    <div class="flex items-center gap-3">
                        <button 
                            type="submit"
                            class="px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                            wire:loading.attr="disabled"
                            wire:target="uploadArquivos"
                        >
                            <div wire:loading.remove wire:target="uploadArquivos">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <div wire:loading wire:target="uploadArquivos">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                            </div>
                            <span wire:loading.remove wire:target="uploadArquivos">Enviar</span>
                            <span wire:loading wire:target="uploadArquivos">Enviando...</span>
                        </button>
                        
                        <button 
                            type="button"
                            wire:click="toggleFormArquivo"
                            class="px-4 py-2 text-gray-700 bg-white border border-gray-300 text-sm font-medium rounded-md hover:bg-gray-50"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    @endif

    <!-- Lista de Arquivos -->
    @if($trabalho->arquivos->count() > 0)
        <!-- Filtros por Categoria -->
        <div class="mb-4">
            <div class="flex flex-wrap gap-2">
                <button class="px-3 py-1 text-xs font-medium bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200">
                    Todos ({{ $trabalho->arquivos->count() }})
                </button>
                @foreach($categoriaArquivos as $valor => $label)
                    @php $count = $trabalho->arquivos->where('categoria', $valor)->count(); @endphp
                    @if($count > 0)
                        <button class="px-3 py-1 text-xs font-medium bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200">
                            {{ $label }} ({{ $count }})
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Grid de Arquivos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($trabalho->arquivos->sortByDesc('created_at') as $arquivo)
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow duration-200">
                    <!-- Header do Arquivo -->
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-2 min-w-0 flex-1">
                            <!-- Ícone do Arquivo -->
                            <div class="flex-shrink-0">
                                @if($arquivo->eh_imagem)
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                @elseif(str_contains($arquivo->tipo_mime, 'pdf'))
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @elseif(str_contains($arquivo->tipo_mime, 'word') || str_contains($arquivo->tipo_mime, 'document'))
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @elseif(str_contains($arquivo->tipo_mime, 'sheet') || str_contains($arquivo->tipo_mime, 'excel'))
                                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                @endif
                            </div>
                            
                            <!-- Nome do Arquivo -->
                            <div class="min-w-0 flex-1">
                                <h4 class="text-sm font-medium text-gray-900 truncate" title="{{ $arquivo->nome_original }}">
                                    {{ $arquivo->nome_original }}
                                </h4>
                                <p class="text-xs text-gray-500">{{ $arquivo->tamanho_formatado }}</p>
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
                                class="absolute right-0 top-8 w-40 bg-white rounded-md shadow-lg border border-gray-200 py-1 z-10"
                                style="display: none;"
                            >
                                <a 
                                    href="{{ $arquivo->url_download }}"
                                    target="_blank"
                                    class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>
                                
                                @if($arquivo->eh_imagem)
                                    <a 
                                        href="{{ $arquivo->url_arquivo }}"
                                        target="_blank"
                                        class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Visualizar
                                    </a>
                                @endif
                                
                                @if(auth()->id() === $arquivo->user_id || auth()->user()->hasRole('admin'))
                                    <button 
                                        wire:click="excluirArquivo({{ $arquivo->id }})"
                                        onclick="return confirm('Tem certeza que deseja excluir este arquivo?')"
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 flex items-center gap-2"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Excluir
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Preview da Imagem -->
                    @if($arquivo->eh_imagem)
                        <div class="mb-3">
                            <img 
                                src="{{ $arquivo->url_arquivo }}" 
                                alt="{{ $arquivo->nome_original }}"
                                class="w-full h-32 object-cover rounded-md border border-gray-200"
                                loading="lazy"
                            >
                        </div>
                    @endif

                    <!-- Categoria e Descrição -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded-full">
                                {{ $arquivo->categoria_label }}
                            </span>
                            
                            @if($arquivo->visivel_cliente)
                                <span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">
                                    Visível ao cliente
                                </span>
                            @endif
                        </div>
                        
                        @if($arquivo->descricao)
                            <p class="text-xs text-gray-600">{{ $arquivo->descricao }}</p>
                        @endif
                    </div>

                    <!-- Footer com Informações -->
                    <div class="mt-3 pt-2 border-t border-gray-100">
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $arquivo->usuario->name }}</span>
                            <span>{{ $arquivo->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Resumo dos Arquivos -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                Resumo dos Arquivos
            </h4>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <div class="text-lg font-bold text-blue-600">{{ $trabalho->arquivos->count() }}</div>
                    <div class="text-xs text-blue-700">Total</div>
                </div>
                
                <div>
                    <div class="text-lg font-bold text-blue-600">{{ $trabalho->arquivos->where('eh_imagem', true)->count() }}</div>
                    <div class="text-xs text-blue-700">Imagens</div>
                </div>
                
                <div>
                    <div class="text-lg font-bold text-blue-600">{{ $trabalho->arquivos->where('visivel_cliente', true)->count() }}</div>
                    <div class="text-xs text-blue-700">Visíveis ao cliente</div>
                </div>
                
                <div>
                    @php
                        $tamanhoTotal = $trabalho->arquivos->sum('tamanho');
                        $tamanhoFormatado = $tamanhoTotal > 1048576 
                            ? round($tamanhoTotal / 1048576, 1) . ' MB'
                            : round($tamanhoTotal / 1024, 1) . ' KB';
                    @endphp
                    <div class="text-lg font-bold text-blue-600">{{ $tamanhoFormatado }}</div>
                    <div class="text-xs text-blue-700">Tamanho total</div>
                </div>
            </div>
        </div>
    @else
        <!-- Estado Vazio -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum arquivo encontrado</h3>
            <p class="mt-1 text-sm text-gray-500">
                Adicione arquivos relacionados ao trabalho para organizar melhor o projeto.
            </p>
            
            <button 
                wire:click="toggleFormArquivo"
                class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 flex items-center gap-2 mx-auto"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Adicionar Primeiro Arquivo
            </button>
        </div>
    @endif
</div>

@push('styles')
<style>
/* Animações para upload */
.file-upload-area {
    transition: all 0.2s ease;
}

.file-upload-area:hover {
    border-color: #3b82f6;
    background-color: #eff6ff;
}

/* Grid responsivo */
@media (max-width: 640px) {
    .grid-cols-1.md\:grid-cols-2.lg\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
}

/* Preview de imagem */
.image-preview {
    transition: transform 0.2s ease;
}

.image-preview:hover {
    transform: scale(1.02);
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