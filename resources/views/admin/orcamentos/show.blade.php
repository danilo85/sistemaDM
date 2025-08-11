<x-app-layout>
    <x-slot name="header">
        {{-- O cabeçalho agora está dentro de um container para garantir o alinhamento --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-page-header>
                <x-slot:title>
                    <div class="flex items-center gap-4">
                        {{-- Botão de Voltar Inteligente --}}
                        <a href="{{ route('orcamentos.index') }}" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white" title="Voltar">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <div>
                            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                                Orçamento #{{ $orcamento->numero }}
                            </h2>
                            <span class="text-sm text-gray-500">{{ $orcamento->titulo }}</span>
                        </div>
                    </div>
                </x-slot:title>
                <x-slot:actions>
                    <a href="{{ route('orcamentos.edit', $orcamento->id) }}" class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white hover:bg-blue-700">
                        Editar Orçamento
                    </a>
                </x-slot:actions>
            </x-page-header>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Estrutura de Grid com 2 Colunas --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- =============================================================== --}}
                {{-- COLUNA PRINCIPAL (ESQUERDA) --}}
                {{-- =============================================================== --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Card de Detalhes do Orçamento --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Detalhes do Orçamento</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $orcamento->cliente->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Autores</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $orcamento->autores->pluck('name')->join(', ') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                        <dd class="mt-1 text-sm">
                                            @livewire('orcamento-status-updater', ['orcamento' => $orcamento])
                                        </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Total</dt>
                                    <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-gray-200">R$ {{ number_format($orcamento->valor_total, 2, ',', '.') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Emissão</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $orcamento->data_emissao->format('d/m/Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Validade</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200">{{ $orcamento->data_validade->format('d/m/Y') }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Card da Descrição --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Descrição do Serviço</h3>
                            <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                               {!! $orcamento->descricao !!}
                            </div>
                        </div>
                    </div>

                    {{-- Componente de Pagamentos --}}
                    @livewire('orcamento-pagamentos', ['orcamento' => $orcamento])

                </div>

                {{-- =============================================================== --}}
                {{-- COLUNA LATERAL (DIREITA) --}}
                {{-- =============================================================== --}}
                <div class="lg:col-span-1 space-y-6">

                    {{-- Card de Ações (CORRIGIDO) --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6" x-data="{ 
                            linkCopiado: false,
                            copiarLink(url) {
                                // Usa um método de fallback para garantir a compatibilidade
                                const textarea = document.createElement('textarea');
                                textarea.value = url;
                                document.body.appendChild(textarea);
                                textarea.select();
                                document.execCommand('copy');
                                document.body.removeChild(textarea);

                                this.linkCopiado = true;
                                setTimeout(() => { this.linkCopiado = false; }, 2500);
                            }
                        }">
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ações</h3>
                            <div class="space-y-3">
                                <a href="{{ $orcamento->public_url }}" target="_blank" class="w-full flex items-center justify-center gap-2 text-center rounded-lg bg-gray-500 px-4 py-2 font-bold text-white hover:bg-gray-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                    <span>Visualizar como Cliente</span>
                                </a>
                                <button @click="copiarLink('{{ $orcamento->public_url }}')" class="w-full flex items-center justify-center gap-2 text-center rounded-lg bg-blue-600 px-4 py-2 font-bold text-white hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 005.656 5.656l-1.5 1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" /></svg>
                                    <span x-show="!linkCopiado">Copiar Link Público</span>
                                    <span x-show="linkCopiado" class="text-green-300">Link Copiado! ✓</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Card de Histórico --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Histórico de Alterações</h3>
                            @livewire('orcamento-history', ['orcamentoId' => $orcamento->id])
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
