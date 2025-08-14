
<div x-data="{ expanded: false }" class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg shadow-lg overflow-hidden transition-all duration-200 hover:shadow-xl transform hover:scale-[1.02]">
    
    <div class="p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4 flex-grow cursor-pointer" @click="expanded = !expanded">
                <!--[if BLOCK]><![endif]--><?php if($fatura->cartao->bandeira_path): ?>
                    <img src="<?php echo e($fatura->cartao->bandeira_path); ?>" alt="Bandeira" class="h-10 w-16 object-contain rounded-md bg-gray-50 dark:bg-gray-700 p-1">
                <?php else: ?>
                    <div class="h-10 w-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-md flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <div class="flex-1 min-w-0">
                    <div class="font-bold text-lg text-white whitespace-nowrap overflow-hidden text-ellipsis"><?php echo e($fatura->cartao->nome); ?></div>
                    <div class="text-sm text-white/80"><?php echo e(count($fatura->transacoes)); ?> <?php echo e(count($fatura->transacoes) == 1 ? 'despesa' : 'despesas'); ?> na fatura</div>
                    <div class="text-xs text-white/70 mt-1">
                        <?php
                            $faturaEstaPaga = $fatura->transacoes->every(fn($t) => $t->pago);
                        ?>
                        Status: <span class="bg-white text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
                            <?php echo e($faturaEstaPaga ? 'Paga' : 'Pendente'); ?>

                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right">
                    <div class="font-mono text-2xl font-bold text-white">- R$ <?php echo e(number_format($fatura->total, 2, ',', '.')); ?></div>
                    <div class="text-xs text-white/70">Total da Fatura</div>
                </div>
                
                <button wire:click="pagarFatura" 
                        class="px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105 shadow-md
                               <?php echo e(!$faturaEstaPaga ? 'bg-white text-red-600 hover:bg-red-50' : 'bg-white/20 text-white hover:bg-white/30'); ?>">
                    <?php echo e(!$faturaEstaPaga ? 'Pagar Fatura' : 'Desfazer'); ?>

                </button>
                
                <button class="text-white/70 p-2 rounded-full hover:bg-white/20 transition-colors duration-200" @click="expanded = !expanded">
                    <svg x-show="!expanded" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <svg x-show="expanded" class="w-5 h-5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    
    <div x-show="expanded" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
        <div class="p-4">
            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Lançamentos da Fatura
            </h4>
            <div class="space-y-2">
                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $fatura->transacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <!--[if BLOCK]><![endif]--><?php if($transacao->categoria): ?>
                                            <div class="w-3 h-3 rounded-full" style="background-color: <?php echo e($transacao->categoria->cor ?? '#6B7280'); ?>"></div>
                                        <?php else: ?>
                                            <div class="w-3 h-3 rounded-full bg-gray-400"></div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 whitespace-nowrap overflow-hidden text-ellipsis"><?php echo e($transacao->descricao); ?></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <?php echo e($transacao->data->format('d/m/Y')); ?> • 
                                            <!--[if BLOCK]><![endif]--><?php if($transacao->categoria): ?>
                                                <?php echo e($transacao->categoria->nome); ?>

                                            <?php else: ?>
                                                Sem categoria
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-mono font-semibold text-red-600 dark:text-red-400">
                                    - R$ <?php echo e(number_format($transacao->valor, 2, ',', '.')); ?>

                                </span>
                                <!--[if BLOCK]><![endif]--><?php if($transacao->pago): ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Pago
                                    </span>
                                <?php else: ?>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                        Pendente
                                    </span>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/credit-card-summary.blade.php ENDPATH**/ ?>