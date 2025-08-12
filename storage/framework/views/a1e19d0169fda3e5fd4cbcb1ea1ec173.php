<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Dashboard')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Cards de Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total de Clientes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Clientes</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($totalClientes ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total de Orçamentos -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Orçamentos</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($totalOrcamentos ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orçamentos Pendentes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Orçamentos Pendentes</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white"><?php echo e($orcamentosPendentes ?? 0); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Valor Total dos Orçamentos -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Total</p>
                                <p class="text-2xl font-semibold text-gray-900 dark:text-white">R$ <?php echo e(number_format($valorTotal ?? 0, 2, ',', '.')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Ações Rápidas -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Ações Rápidas</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="<?php echo e(route('orcamentos.create')); ?>" class="flex items-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                                <svg class="h-6 w-6 text-blue-600 dark:text-blue-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Novo Orçamento</span>
                            </a>
                            
                            <a href="<?php echo e(route('clientes.create')); ?>" class="flex items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                                <svg class="h-6 w-6 text-green-600 dark:text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">Novo Cliente</span>
                            </a>
                            
                            <a href="<?php echo e(route('orcamentos.index')); ?>" class="flex items-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition-colors">
                                <svg class="h-6 w-6 text-yellow-600 dark:text-yellow-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Ver Orçamentos</span>
                            </a>
                            
                            <a href="<?php echo e(route('clientes.index')); ?>" class="flex items-center p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-900/30 transition-colors">
                                <svg class="h-6 w-6 text-purple-600 dark:text-purple-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-purple-600 dark:text-purple-400">Ver Clientes</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Orçamentos Recentes -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Orçamentos Recentes</h3>
                        <?php if(isset($orcamentosRecentes) && $orcamentosRecentes->count() > 0): ?>
                            <div class="space-y-3">
                                <?php $__currentLoopData = $orcamentosRecentes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                <?php echo e($orcamento->cliente->nome ?? 'Cliente não encontrado'); ?>

                                            </p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                <?php echo e($orcamento->created_at->format('d/m/Y')); ?>

                                            </p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                                R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?>

                                            </p>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                <?php if($orcamento->status === 'aprovado'): ?> bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400
                                                <?php elseif($orcamento->status === 'pendente'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400
                                                <?php else: ?> bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400
                                                <?php endif; ?>">
                                                <?php echo e(ucfirst($orcamento->status)); ?>

                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum orçamento encontrado.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informações do Sistema</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Versão do Sistema</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">1.0.0</p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Último Acesso</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e(now()->format('d/m/Y H:i')); ?></p>
                        </div>
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Usuário Logado</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo e(auth()->user()->name); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH C:\laragon\www\sistemaDM\resources\views/admin/dashboard-simples.blade.php ENDPATH**/ ?>