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
    
    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white w-full">
        <div class="px-4 sm:px-6 lg:px-8 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div>
                    <h1 class="text-lg font-bold">Contas Bancárias</h1>
                    <p class="text-blue-100 mt-1 text-sm">Gerencie suas contas bancárias e acompanhe saldos e movimentações</p>
                </div>
                <div class="hidden sm:block">
                    <svg class="w-8 h-8 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>
    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            
    <?php if($bancos->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 md:gap-6 mb-8 max-w-6xl mx-auto">
            <?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow duration-200">
                    
                    <div class="p-4 md:p-6 pb-3 md:pb-4">
                        
                        <div class="block md:hidden">
                            
                            <div class="flex justify-center mb-3">
                                <?php if($banco->logo_path): ?>
                                    <img src="<?php echo e($banco->logo_path); ?>" alt="Logo <?php echo e($banco->nome); ?>" class="w-12 h-12 object-contain">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gray-200 dark:bg-gray-600 flex items-center justify-center rounded">
                                        <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="text-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    <?php echo e($banco->nome); ?>

                                </h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?php if($banco->tipo_conta === 'conta_corrente'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    <?php elseif($banco->tipo_conta === 'conta_poupanca'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    <?php elseif($banco->tipo_conta === 'conta_investimento'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                    <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                    <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $banco->tipo_conta))); ?>

                                </span>
                            </div>
                        </div>
                        
                        
                        <div class="hidden md:block">
                            
                            <div class="flex justify-center mb-4">
                                <div class="flex items-center gap-4">
                                    
                                    <div class="flex-shrink-0">
                                        <?php if($banco->logo_path): ?>
                                            <img src="<?php echo e($banco->logo_path); ?>" alt="Logo <?php echo e($banco->nome); ?>" class="w-16 h-16 object-contain border-2 border-white dark:border-gray-600">
                                        <?php else: ?>
                                            <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 flex items-center justify-center border-2 border-white dark:border-gray-600">
                                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">
                                            <?php echo e($banco->nome); ?>

                                        </h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            <?php if($banco->tipo_conta === 'conta_corrente'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            <?php elseif($banco->tipo_conta === 'conta_poupanca'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            <?php elseif($banco->tipo_conta === 'conta_investimento'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                            <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                            <?php endif; ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $banco->tipo_conta))); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="text-center space-y-4 mb-4">
                            
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-lg p-2 border border-blue-200 dark:border-blue-700">
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                    </svg>
                                    <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Saldo Inicial</div>
                                </div>
                                <div class="text-xl font-bold text-blue-800 dark:text-blue-200">
                                    R$ <?php echo e(number_format($banco->saldo_inicial, 2, ',', '.')); ?>

                                </div>
                            </div>
                            
                            
                            <div class="<?php if($banco->saldo_atual >= 0): ?> bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-green-200 dark:border-green-700 <?php else: ?> bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-red-200 dark:border-red-700 <?php endif; ?> rounded-lg p-2 border">
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <?php if($banco->saldo_atual >= 0): ?>
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                        </svg>
                                        <div class="text-sm font-medium text-green-700 dark:text-green-300">Saldo Atual</div>
                                    <?php else: ?>
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                        </svg>
                                        <div class="text-sm font-medium text-red-700 dark:text-red-300">Saldo Atual</div>
                                    <?php endif; ?>
                                </div>
                                <div class="text-xl font-bold <?php if($banco->saldo_atual >= 0): ?> text-green-800 dark:text-green-200 <?php else: ?> text-red-800 dark:text-red-200 <?php endif; ?>">
                                    R$ <?php echo e(number_format($banco->saldo_atual, 2, ',', '.')); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                            
                        
                        <div class="px-4 pb-4">
                            <div class="flex items-stretch gap-2">
                                <a href="<?php echo e(route('bancos.edit', $banco->id)); ?>" class="flex-1 bg-blue-100 hover:bg-blue-200 dark:bg-blue-900/30 dark:hover:bg-blue-900/50 text-blue-700 dark:text-blue-300 px-3 py-2 rounded-md text-xs font-medium text-center transition-colors flex flex-col items-center justify-center" title="Editar">
                                    <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                                    Editar
                                </a>
                                <form method="POST" action="<?php echo e(route('bancos.destroy', $banco->id)); ?>" onsubmit="return confirm('Tem certeza que deseja excluir esta conta?')" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="w-full h-full bg-red-100 hover:bg-red-200 dark:bg-red-900/30 dark:hover:bg-red-900/50 text-red-700 dark:text-red-300 px-3 py-2 rounded-md text-xs font-medium transition-colors flex flex-col items-center justify-center" title="Excluir">
                                        <svg class="w-4 h-4 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma conta bancária encontrada</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece adicionando uma nova conta bancária.</p>
                    <div class="mt-6">
                        <a href="<?php echo e(route('bancos.create')); ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Adicionar Conta Bancária
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    
    <a href="<?php echo e(route('bancos.create')); ?>" title="Nova Conta"
       class="fixed bottom-6 right-6 inline-flex items-center justify-center w-12 h-12 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform duration-200 ease-in-out hover:scale-110">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
    </a>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/admin/bancos/index.blade.php ENDPATH**/ ?>