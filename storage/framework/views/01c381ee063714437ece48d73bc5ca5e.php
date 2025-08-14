
<div>
    
    <div class="p-4 sm:p-6 pb-32 md:pb-6">
    
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        
        <div class="flex items-center justify-center bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-4 rounded-lg shadow-sm">
            <a href="<?php echo e(route('transacoes.index', ['ano' => $dataAtual->copy()->subMonth()->year, 'mes' => $dataAtual->copy()->subMonth()->month])); ?>"
               class="p-2 text-gray-500 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div class="flex items-center gap-2 mx-4">
                <h3 class="text-xl font-bold capitalize text-gray-800 dark:text-gray-200">
                    <?php echo e($tituloDoMes); ?>

                </h3>
                <a href="<?php echo e(route('transacoes.index')); ?>" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Ir para o mês atual">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </a>
            </div>

            <a href="<?php echo e(route('transacoes.index', ['ano' => $dataAtual->copy()->addMonth()->year, 'mes' => $dataAtual->copy()->addMonth()->month])); ?>"
               class="p-2 text-gray-500 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg shadow-md transition-all duration-200 transform hover:scale-105">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <!-- Filtros -->
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-lg shadow-sm mb-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Filtro por conta -->
                <div class="flex-1">
                    <label for="conta_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Filtrar por Conta
                    </label>
                    <select wire:model.live="contaSelecionada" id="conta_id" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 shadow-sm transition-all duration-200">
                        <option value="">Todas as contas</option>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $contas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($conta->id); ?>"><?php echo e($conta->nome); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </select>
                </div>

                <!-- Campo de busca -->
                <div class="flex-1">
                    <label for="busca" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Buscar transação
                    </label>
                    <input wire:model.live.debounce.300ms="busca" type="text" id="busca" placeholder="Digite para buscar..." class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 focus:border-blue-500 dark:focus:border-blue-500 focus:ring-blue-500 dark:focus:ring-blue-500 shadow-sm transition-all duration-200">
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="space-y-4 pb-32 md:pb-16">
        <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $lancamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lancamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <!--[if BLOCK]><![endif]--><?php if($lancamento->is_fatura ?? false): ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('credit-card-summary', ['fatura' => $lancamento]);

$__html = app('livewire')->mount($__name, $__params, 'fatura-' . $lancamento->cartao->id, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php else: ?>
                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('transacao-item', ['transacao' => $lancamento]);

$__html = app('livewire')->mount($__name, $__params, 'transacao-' . $lancamento->id, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-8 text-center">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhum lançamento encontrado</h3>
                    <p class="text-gray-500 dark:text-gray-400">Não há lançamentos financeiros para este mês.</p>
                </div>
            </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>



    
    <div class="mt-8 md:hidden">
        <div class="bg-gradient-to-br from-gray-700 to-gray-800 p-6 rounded-lg shadow-lg">
            <h4 class="text-lg font-bold mb-4 text-white text-center">Resumo Financeiro</h4>
            <div class="space-y-4">
                <!-- Receitas Mobile -->
                <div class="flex items-center justify-between bg-white/10 p-4 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-500 p-2 rounded-full">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg>
                        </div>
                        <span class="text-white/80 text-sm font-medium">Receitas</span>
                    </div>
                    <span class="text-white font-bold">R$ <?php echo e(number_format($totalReceitas, 2, ',', '.')); ?></span>
                </div>
                
                <!-- Despesas Mobile -->
                <div class="flex items-center justify-between bg-white/10 p-4 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="bg-red-500 p-2 rounded-full">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg>
                        </div>
                        <span class="text-white/80 text-sm font-medium">Despesas</span>
                    </div>
                    <span class="text-white font-bold">R$ <?php echo e(number_format($totalDespesas, 2, ',', '.')); ?></span>
                </div>
                
                <!-- Saldo Mobile -->
                <div class="flex items-center justify-between bg-white/10 p-4 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="<?php echo e($saldoDoMes >= 0 ? 'bg-blue-500' : 'bg-orange-500'); ?> p-2 rounded-full">
                            <!--[if BLOCK]><![endif]--><?php if($saldoDoMes >= 0): ?>
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            <?php else: ?>
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                        <span class="text-white/80 text-sm font-medium">Saldo</span>
                    </div>
                    <span class="text-white font-bold">R$ <?php echo e(number_format($saldoDoMes, 2, ',', '.')); ?></span>
                </div>
            </div>
        </div>
    </div>

    
<div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-lg z-50 hidden md:block">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-3 gap-6">
            <!-- Receitas Rodapé -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-white/80 uppercase tracking-wide">Receitas</p>
                        <p class="text-lg font-bold text-white mt-1">R$ <?php echo e(number_format($totalReceitas, 2, ',', '.')); ?></p>
                    </div>
                    <div class="bg-white/20 p-2 rounded-full">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Despesas Rodapé -->
            <div class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-white/80 uppercase tracking-wide">Despesas</p>
                        <p class="text-lg font-bold text-white mt-1">R$ <?php echo e(number_format($totalDespesas, 2, ',', '.')); ?></p>
                    </div>
                    <div class="bg-white/20 p-2 rounded-full">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Saldo Rodapé -->
            <div class="bg-gradient-to-br <?php echo e($saldoDoMes >= 0 ? 'from-blue-500 to-blue-600' : 'from-orange-500 to-orange-600'); ?> p-4 rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-white/80 uppercase tracking-wide">Saldo do Mês</p>
                        <p class="text-lg font-bold text-white mt-1">
                            R$ <?php echo e(number_format($saldoDoMes, 2, ',', '.')); ?>

                        </p>
                    </div>
                    <div class="bg-white/20 p-2 rounded-full">
                        <!--[if BLOCK]><![endif]--><?php if($saldoDoMes >= 0): ?>
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        <?php else: ?>
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/transacao-list.blade.php ENDPATH**/ ?>