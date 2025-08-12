<div>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($cliente->name); ?></h1>
                        <div class="flex items-center gap-2 mt-1">
                            <!--[if BLOCK]><![endif]--><?php if($cliente->is_complete): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    ✓ Cadastro Completo
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                    ⚠ Cadastro Incompleto
                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($saldoDevedor > 0): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    Pendente
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    Em Dia
                                </span>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>

                
                <div class="flex flex-wrap gap-2">
                    <a href="<?php echo e(route('orcamentos.create', ['cliente_id' => $cliente->id])); ?>" 
                       class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Novo Orçamento
                    </a>
                    <button wire:click="compartilharExtrato" 
                            id="btn-compartilhar-extrato"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 transform">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                        </svg>
                        <span id="btn-text">Compartilhar Extrato</span>
                    </button>
                    <button wire:click="alternarExtratoAtivo" 
                            class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-md <?php echo e($cliente->extrato_ativo ? 'text-red-700 dark:text-red-400 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30' : 'text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/30'); ?> focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <!--[if BLOCK]><![endif]--><?php if($cliente->extrato_ativo): ?>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18 12M6 6l12 12"></path>
                            </svg>
                            Desativar Extrato
                        <?php else: ?>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Ativar Extrato
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </button>

                </div>
            </div>
        </div>

        
        <div class="px-6 py-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Orçamentos Ativos</p>
                            <p class="text-2xl font-bold"><?php echo e($orcamentosEnviados); ?></p>
                        </div>
                        <div class="bg-blue-400 bg-opacity-30 rounded-full p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Taxa de Aprovação</p>
                            <p class="text-2xl font-bold"><?php echo e(number_format($taxaAprovacao, 1)); ?>%</p>
                        </div>
                        <div class="bg-green-400 bg-opacity-30 rounded-full p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Receita Total</p>
                            <p class="text-2xl font-bold">R$ <?php echo e(number_format($totalEmProjetos, 2, ',', '.')); ?></p>
                        </div>
                        <div class="bg-purple-400 bg-opacity-30 rounded-full p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">A Receber</p>
                            <p class="text-2xl font-bold">R$ <?php echo e(number_format($saldoDevedor, 2, ',', '.')); ?></p>
                        </div>
                        <div class="bg-orange-400 bg-opacity-30 rounded-full p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button wire:click="setActiveTab('visao-geral')" 
                        class="<?php echo e($activeTab === 'visao-geral' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Visão Geral
                </button>
                <button wire:click="setActiveTab('orcamentos')" 
                        class="<?php echo e($activeTab === 'orcamentos' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Orçamentos
                </button>
                <button wire:click="setActiveTab('pagamentos')" 
                        class="<?php echo e($activeTab === 'pagamentos' ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'); ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Pagamentos/Extrato
                </button>
            </nav>
        </div>

        
        <div class="p-6">
            
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'visao-geral'): ?>
                <div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Orçamentos Ativos</p>
                                    <p class="text-2xl font-bold"><?php echo e($orcamentosEnviados); ?></p>
                                </div>
                                <div class="bg-blue-400 bg-opacity-30 rounded-full p-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">Taxa de Aprovação</p>
                                    <p class="text-2xl font-bold"><?php echo e(number_format($taxaAprovacao, 1)); ?>%</p>
                                </div>
                                <div class="bg-green-400 bg-opacity-30 rounded-full p-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Receita Total</p>
                                    <p class="text-2xl font-bold">R$ <?php echo e(number_format($totalEmProjetos, 2, ',', '.')); ?></p>
                                </div>
                                <div class="bg-purple-400 bg-opacity-30 rounded-full p-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg p-4 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-orange-100 text-sm font-medium">A Receber</p>
                                    <p class="text-2xl font-bold">R$ <?php echo e(number_format($saldoDevedor, 2, ',', '.')); ?></p>
                                </div>
                                <div class="bg-orange-400 bg-opacity-30 rounded-full p-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Timeline de Atividades</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Histórico cronológico de todas as interações com o cliente</p>
                        </div>
                        
                        <div class="p-6">
                            <!--[if BLOCK]><![endif]--><?php if(!empty($timelineEventos) && count($timelineEventos) > 0): ?>
                                <div class="flow-root">
                                    <ul role="list" class="-mb-8">
                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $timelineEventos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $evento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <div class="relative pb-8">
                                                    <!--[if BLOCK]><![endif]--><?php if($index < count($timelineEventos) - 1): ?>
                                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <div class="relative flex space-x-3">
                                                        <div>
                                                            <!--[if BLOCK]><![endif]--><?php if($evento['tipo'] === 'orcamento'): ?>
                                                                <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-900">
                                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                    </svg>
                                                                </span>
                                                            <?php elseif($evento['tipo'] === 'aprovacao'): ?>
                                                                <span class="h-8 w-8 rounded-full bg-green-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-900">
                                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                    </svg>
                                                                </span>
                                                            <?php elseif($evento['tipo'] === 'pagamento'): ?>
                                                                <span class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center ring-8 ring-white dark:ring-gray-900">
                                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                                    </svg>
                                                                </span>
                                                            <?php elseif($evento['tipo'] === 'rejeicao'): ?>
                                                                <span class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white dark:ring-gray-900">
                                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                    </svg>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white dark:ring-gray-900">
                                                                    <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                </span>
                                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                                                <div class="flex items-start justify-between">
                                                                    <div class="flex-1">
                                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($evento['titulo']); ?></p>
                                                                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1"><?php echo e($evento['descricao']); ?></p>
                                                                        
                                                                        <!--[if BLOCK]><![endif]--><?php if(isset($evento['detalhes'])): ?>
                                                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                                                <?php echo e($evento['detalhes']); ?>

                                                                            </div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        
                                                                        <!--[if BLOCK]><![endif]--><?php if(isset($evento['valor'])): ?>
                                                                            <div class="mt-2">
                                                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                                    R$ <?php echo e(number_format($evento['valor'], 2, ',', '.')); ?>

                                                                                </span>
                                                                            </div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                        
                                                                        <!--[if BLOCK]><![endif]--><?php if(isset($evento['status'])): ?>
                                                                            <div class="mt-2">
                                                                                <?php if (isset($component)) { $__componentOriginal8860cf004fec956b6e41d036eb967550 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8860cf004fec956b6e41d036eb967550 = $attributes; } ?>
<?php $component = App\View\Components\StatusBadge::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatusBadge::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($evento['status']),'size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8860cf004fec956b6e41d036eb967550)): ?>
<?php $attributes = $__attributesOriginal8860cf004fec956b6e41d036eb967550; ?>
<?php unset($__attributesOriginal8860cf004fec956b6e41d036eb967550); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8860cf004fec956b6e41d036eb967550)): ?>
<?php $component = $__componentOriginal8860cf004fec956b6e41d036eb967550; ?>
<?php unset($__componentOriginal8860cf004fec956b6e41d036eb967550); ?>
<?php endif; ?>
                                                                            </div>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                    </div>
                                                                    <div class="text-right text-xs text-gray-500 dark:text-gray-400 ml-4">
                                                                        <time datetime="<?php echo e($evento['data']); ?>"><?php echo e($evento['data']->format('d/m/Y')); ?></time>
                                                                        <!--[if BLOCK]><![endif]--><?php if(isset($evento['hora'])): ?>
                                                                            <br><span class="text-gray-400"><?php echo e($evento['hora']); ?></span>
                                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-12">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma atividade encontrada</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Quando houver orçamentos ou pagamentos, eles aparecerão aqui.</p>
                                    <div class="mt-6">
                                        <a href="<?php echo e(route('orcamentos.create', ['cliente_id' => $cliente->id])); ?>" 
                                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Criar Primeiro Orçamento
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'orcamentos'): ?>
                <div>
                    
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Todos os Orçamentos</h3>
                        
                        
                        <div class="flex flex-wrap gap-3">
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Status:</label>
                                <select wire:model.live="orcamentoStatusFilter" 
                                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $this->getStatusOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </select>
                            </div>
                            
                            
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Ordenar:</label>
                                <select wire:change="setOrcamentoOrder($event.target.value, 'desc')" 
                                        class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="created_at">Data de Criação</option>
                                    <option value="updated_at">Última Atualização</option>
                                    <option value="valor_total">Valor</option>
                                    <option value="numero">Número</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!--[if BLOCK]><![endif]--><?php if(!empty($orcamentosDetalhados) && count($orcamentosDetalhados) > 0): ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-3">Número</th>
                                        <th class="px-4 py-3">Título</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3">Data Criação</th>
                                        <th class="px-4 py-3 text-right">Valor</th>
                                        <th class="px-4 py-3 text-right">Pago</th>
                                        <th class="px-4 py-3 text-right">Saldo</th>
                                        <th class="px-4 py-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $orcamentosDetalhados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $totalPago = $orcamento->pagamentos->sum('valor_pago');
                                            $saldo = $orcamento->valor_total - $totalPago;
                                        ?>
                                        <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="px-4 py-3 font-medium">
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-gray-900 dark:text-gray-100">#<?php echo e($orcamento->numero); ?></span>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($orcamento->data_emissao ? $orcamento->data_emissao->format('d/m/Y') : 'N/A'); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-col">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($orcamento->titulo); ?></span>
                                                    <!--[if BLOCK]><![endif]--><?php if($orcamento->data_validade): ?>
                                                        <span class="text-xs <?php echo e($orcamento->data_validade->isPast() ? 'text-red-500' : 'text-gray-500'); ?> dark:text-gray-400">
                                                            Válido até <?php echo e($orcamento->data_validade->format('d/m/Y')); ?>

                                                        </span>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                            <td class="px-4 py-3">
                                                <?php if (isset($component)) { $__componentOriginal8860cf004fec956b6e41d036eb967550 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8860cf004fec956b6e41d036eb967550 = $attributes; } ?>
<?php $component = App\View\Components\StatusBadge::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatusBadge::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($orcamento->status)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8860cf004fec956b6e41d036eb967550)): ?>
<?php $attributes = $__attributesOriginal8860cf004fec956b6e41d036eb967550; ?>
<?php unset($__attributesOriginal8860cf004fec956b6e41d036eb967550); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8860cf004fec956b6e41d036eb967550)): ?>
<?php $component = $__componentOriginal8860cf004fec956b6e41d036eb967550; ?>
<?php unset($__componentOriginal8860cf004fec956b6e41d036eb967550); ?>
<?php endif; ?>
                                            </td>
                                            <td class="px-4 py-3"><?php echo e($orcamento->created_at->format('d/m/Y')); ?></td>
                                            <td class="px-4 py-3 text-right font-medium">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></td>
                                            <td class="px-4 py-3 text-right text-green-600 dark:text-green-400">R$ <?php echo e(number_format($totalPago, 2, ',', '.')); ?></td>
                                            <td class="px-4 py-3 text-right font-medium <?php echo e($saldo > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'); ?>">
                                                R$ <?php echo e(number_format($saldo, 2, ',', '.')); ?>

                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex items-center gap-2">
                                                    <a href="<?php echo e(route('orcamentos.show', $orcamento)); ?>" 
                                                       class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium"
                                                       title="Ver Orçamento">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </a>
                                                    <a href="<?php echo e(route('orcamentos.pdf', $orcamento)); ?>" 
                                                       target="_blank"
                                                       class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300 text-sm font-medium"
                                                       title="Baixar PDF">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    </a>
                                                    <!--[if BLOCK]><![endif]--><?php if($orcamento->status === 'Aprovado' && $saldo > 0): ?>
                                                        <a href="<?php echo e(route('pagamentos.create', ['orcamento_id' => $orcamento->id])); ?>" 
                                                           class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-sm font-medium"
                                                           title="Registrar Pagamento">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                                            </svg>
                                                        </a>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum orçamento encontrado</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Crie o primeiro orçamento para este cliente.</p>
                            <div class="mt-6">
                                <a href="<?php echo e(route('orcamentos.create', ['cliente_id' => $cliente->id])); ?>" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Criar Orçamento
                                </a>
                            </div>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            
            <!--[if BLOCK]><![endif]--><?php if($activeTab === 'pagamentos'): ?>
                <div>
                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Resumo de Pagamentos</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400">Total em Projetos</div>
                                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">R$ <?php echo e(number_format($totalEmProjetos, 2, ',', '.')); ?></div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                <div class="text-sm font-medium text-green-600 dark:text-green-400">Total Pago</div>
                                <div class="text-2xl font-bold text-green-900 dark:text-green-100">R$ <?php echo e(number_format($totalPago, 2, ',', '.')); ?></div>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                                <div class="text-sm font-medium text-red-600 dark:text-red-400">Saldo Devedor</div>
                                <div class="text-2xl font-bold text-red-900 dark:text-red-100">R$ <?php echo e(number_format($saldoDevedor, 2, ',', '.')); ?></div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg">
                                <div class="text-sm font-medium text-purple-600 dark:text-purple-400">% Pago</div>
                                <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                                    <?php echo e($totalEmProjetos > 0 ? number_format(($totalPago / $totalEmProjetos) * 100, 1) : 0); ?>%
                                </div>
                            </div>
                        </div>
                    </div>

                    
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Extrato de Pagamentos</h3>
                        <div class="flex items-center gap-4">
                            <div>
                                <label for="dataInicio" class="text-sm font-medium text-gray-700 dark:text-gray-300">De:</label>
                                <input type="date" id="dataInicio" wire:model.live="dataInicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label for="dataFim" class="text-sm font-medium text-gray-700 dark:text-gray-300">Até:</label>
                                <input type="date" id="dataFim" wire:model.live="dataFim" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pagamentos por Orçamento</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Histórico detalhado de pagamentos agrupados por projeto</p>
                        </div>
                        
                        <!--[if BLOCK]><![endif]--><?php if(!empty($orcamentosDetalhados) && count($orcamentosDetalhados) > 0): ?>
                            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $orcamentosDetalhados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        $totalPagoOrcamento = $orcamento->pagamentos->sum('valor_pago');
                                        $saldoOrcamento = $orcamento->valor_total - $totalPagoOrcamento;
                                        $percentualPago = $orcamento->valor_total > 0 ? ($totalPagoOrcamento / $orcamento->valor_total) * 100 : 0;
                                    ?>
                                    
                                    <div class="accordion-item">
                                        
                                        <button type="button" 
                                                class="w-full px-6 py-4 text-left hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 transition-colors duration-200"
                                                onclick="toggleAccordion('orcamento-<?php echo e($orcamento->id); ?>')">
                                            <div class="flex items-center justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-4">
                                                        <div>
                                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                #<?php echo e($orcamento->numero); ?> - <?php echo e($orcamento->titulo); ?>

                                                            </h4>
                                                            <div class="flex items-center gap-4 mt-1">
                                                                <?php if (isset($component)) { $__componentOriginal8860cf004fec956b6e41d036eb967550 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8860cf004fec956b6e41d036eb967550 = $attributes; } ?>
<?php $component = App\View\Components\StatusBadge::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('status-badge'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\StatusBadge::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($orcamento->status),'size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8860cf004fec956b6e41d036eb967550)): ?>
<?php $attributes = $__attributesOriginal8860cf004fec956b6e41d036eb967550; ?>
<?php unset($__attributesOriginal8860cf004fec956b6e41d036eb967550); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8860cf004fec956b6e41d036eb967550)): ?>
<?php $component = $__componentOriginal8860cf004fec956b6e41d036eb967550; ?>
<?php unset($__componentOriginal8860cf004fec956b6e41d036eb967550); ?>
<?php endif; ?>
                                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                                    <?php echo e($orcamento->created_at->format('d/m/Y')); ?>

                                                                </span>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="flex items-center gap-6 text-sm">
                                                            <div class="text-center">
                                                                <div class="text-gray-500 dark:text-gray-400">Valor Total</div>
                                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                                    R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?>

                                                                </div>
                                                            </div>
                                                            <div class="text-center">
                                                                <div class="text-gray-500 dark:text-gray-400">Pago</div>
                                                                <div class="font-medium text-green-600 dark:text-green-400">
                                                                    R$ <?php echo e(number_format($totalPagoOrcamento, 2, ',', '.')); ?>

                                                                </div>
                                                            </div>
                                                            <div class="text-center">
                                                                <div class="text-gray-500 dark:text-gray-400">Saldo</div>
                                                                <div class="font-medium <?php echo e($saldoOrcamento > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'); ?>">
                                                                    R$ <?php echo e(number_format($saldoOrcamento, 2, ',', '.')); ?>

                                                                </div>
                                                            </div>
                                                            <div class="text-center">
                                                                <div class="text-gray-500 dark:text-gray-400">% Pago</div>
                                                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                                                    <?php echo e(number_format($percentualPago, 1)); ?>%
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="mt-3">
                                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                            <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                                                 style="width: <?php echo e(min($percentualPago, 100)); ?>%"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="ml-4">
                                                    <svg class="w-5 h-5 text-gray-400 transform transition-transform duration-200 accordion-icon" 
                                                         id="icon-orcamento-<?php echo e($orcamento->id); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </button>
                                        
                                        
                                        <div id="orcamento-<?php echo e($orcamento->id); ?>" class="accordion-content hidden">
                                            <div class="px-6 pb-4">
                                                <!--[if BLOCK]><![endif]--><?php if($orcamento->pagamentos && count($orcamento->pagamentos) > 0): ?>
                                                    <div class="overflow-x-auto">
                                                        <table class="min-w-full">
                                                            <thead>
                                                                <tr class="border-b border-gray-200 dark:border-gray-700">
                                                                    <th class="text-left py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Data</th>
                                                                    <th class="text-left py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                                                                    <th class="text-right py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Valor</th>
                                                                    <th class="text-left py-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Método</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $orcamento->pagamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                                                        <td class="py-2 text-sm text-gray-900 dark:text-gray-100">
                                                                            <?php echo e($pagamento->data_pagamento ? $pagamento->data_pagamento->format('d/m/Y') : 'N/A'); ?>

                                                                        </td>
                                                                        <td class="py-2 text-sm text-gray-900 dark:text-gray-100">
                                                                            <?php echo e($pagamento->descricao ?? 'Pagamento do orçamento #' . $orcamento->numero); ?>

                                                                        </td>
                                                                        <td class="py-2 text-sm font-medium text-green-600 dark:text-green-400 text-right">
                                                                            R$ <?php echo e(number_format($pagamento->valor_pago, 2, ',', '.')); ?>

                                                                        </td>
                                                                        <td class="py-2 text-sm text-gray-500 dark:text-gray-400">
                                                                            <?php echo e($pagamento->metodo_pagamento ?? 'N/A'); ?>

                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-center py-4">
                                                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum pagamento registrado para este orçamento</p>
                                                        <!--[if BLOCK]><![endif]--><?php if($orcamento->status === 'Aprovado'): ?>
                                                            <a href="<?php echo e(route('pagamentos.create', ['orcamento_id' => $orcamento->id])); ?>" 
                                                               class="inline-flex items-center mt-2 px-3 py-1 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 dark:bg-indigo-900 dark:text-indigo-300 dark:hover:bg-indigo-800">
                                                                Registrar Primeiro Pagamento
                                                            </a>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    </div>
                                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        <?php else: ?>
                            <div class="p-6 text-center">
                                <p class="text-gray-500 dark:text-gray-400">Nenhum orçamento encontrado para este cliente.</p>
                            </div>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>

                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Débitos (Projetos Aprovados)</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="bg-gray-100 dark:bg-gray-600 text-xs uppercase">
                                        <tr>
                                            <th class="px-3 py-2">Data</th>
                                            <th class="px-3 py-2">Descrição</th>
                                            <th class="px-3 py-2 text-right">Valor Total</th>
                                            <th class="px-3 py-2 text-right">Falta Pagar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $orcamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="border-b dark:border-gray-600">
                                                <td class="px-3 py-2 whitespace-nowrap"><?php echo e(optional($orcamento->updated_at)->format('d/m/Y')); ?></td>
                                                <td class="px-3 py-2"><?php echo e($orcamento->titulo); ?></td>
                                                <td class="px-3 py-2 text-right whitespace-nowrap">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></td>
                                                <?php
                                                    $faltaPagar = $orcamento->valor_total - $orcamento->pagamentos_sum_valor_pago;
                                                ?>
                                                <td class="px-3 py-2 text-right font-bold whitespace-nowrap <?php echo e($faltaPagar > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400'); ?>">
                                                    R$ <?php echo e(number_format($faltaPagar, 2, ',', '.')); ?>

                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="4" class="text-center py-4">Nenhum projeto no período.</td>
                                            </tr>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Créditos (Pagamentos Recebidos)</h4>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="bg-gray-100 dark:bg-gray-600 text-xs uppercase">
                                        <tr>
                                            <th class="px-3 py-2">Data</th>
                                            <th class="px-3 py-2">Descrição</th>
                                            <th class="px-3 py-2 text-right">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $pagamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <tr class="border-b dark:border-gray-600">
                                                <td class="px-3 py-2 whitespace-nowrap"><?php echo e(optional($pagamento->data_pagamento)->format('d/m/Y')); ?></td>
                                                <td class="px-3 py-2">Pagamento ref. Orçamento #<?php echo e($pagamento->orcamento->numero); ?></td>
                                                <td class="px-3 py-2 text-right text-green-600 dark:text-green-400 whitespace-nowrap">R$ <?php echo e(number_format($pagamento->valor_pago, 2, ',', '.')); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                            <tr>
                                                <td colspan="3" class="text-center py-4">Nenhum pagamento no período.</td>
                                            </tr>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>



</div>

    <?php
        $__scriptKey = '2566806237-0';
        ob_start();
    ?>
<script>
    let clienteData = {};
    
    // Função para alternar abas
    function setActiveTab(tab) {
        window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('activeTab', tab);
    }
    
    // Função para controlar accordion
    function toggleAccordion(elementId) {
        const content = document.getElementById(elementId);
        const icon = document.getElementById('icon-' + elementId);
        
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.style.transform = 'rotate(180deg)';
        } else {
            content.classList.add('hidden');
            icon.style.transform = 'rotate(0deg)';
        }
    }
    
    // Listener para o evento de link copiado
    $wire.on('link-copiado', (event) => {
        console.log('Evento link-copiado recebido:', event);
        
        const link = event[0].link;
        const message = event[0].message;
        const button = document.getElementById('btn-compartilhar-extrato');
        const buttonText = document.getElementById('btn-text');
        
        console.log('Link a ser copiado:', link);
        console.log('Botão encontrado:', button);
        console.log('Texto do botão encontrado:', buttonText);
        
        if (!button || !buttonText) {
            console.error('Elementos do botão não encontrados');
            return;
        }
        
        // Verifica se o navegador suporta clipboard API
        if (!navigator.clipboard) {
            console.warn('Clipboard API não suportada, usando fallback');
            // Fallback para navegadores que não suportam clipboard API
            const textArea = document.createElement('textarea');
            textArea.value = link;
            document.body.appendChild(textArea);
            textArea.select();
            try {
                document.execCommand('copy');
                console.log('Link copiado usando fallback');
                // Animação de sucesso
                animateButton(button, buttonText, message);
            } catch (err) {
                console.error('Erro ao copiar usando fallback:', err);
                prompt('Copie o link abaixo:', link);
            }
            document.body.removeChild(textArea);
            return;
        }
        
        // Copia o link para a área de transferência usando Clipboard API
        navigator.clipboard.writeText(link).then(() => {
            console.log('Link copiado com sucesso usando Clipboard API');
            // Animação de sucesso
            animateButton(button, buttonText, message);
        }).catch((err) => {
            console.error('Erro ao copiar usando Clipboard API:', err);
            // Fallback se não conseguir copiar
            prompt('Copie o link abaixo:', link);
        });
    });
    
    // Função para animar o botão
    function animateButton(button, buttonText, message) {
        // Animação e feedback visual
        button.classList.add('bg-green-500', 'border-green-500', 'text-white', 'scale-105');
        button.classList.remove('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-200', 'border-gray-300', 'dark:border-gray-600');
        buttonText.textContent = 'Copiado!';
        
        // Volta ao estado original após 2 segundos
        setTimeout(() => {
            button.classList.remove('bg-green-500', 'border-green-500', 'text-white', 'scale-105');
            button.classList.add('bg-white', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-200', 'border-gray-300', 'dark:border-gray-600');
            buttonText.textContent = 'Compartilhar Extrato';
        }, 2000);
        
        // Mostra notificação de sucesso
        if (typeof window.showToast === 'function') {
            window.showToast(message, 'success');
        } else {
            console.log('Função showToast não encontrada, mensagem:', message);
        }
    }

</script>
    <?php
        $__output = ob_get_clean();

        \Livewire\store($this)->push('scripts', $__output, $__scriptKey)
    ?><?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/cliente/cliente-360.blade.php ENDPATH**/ ?>