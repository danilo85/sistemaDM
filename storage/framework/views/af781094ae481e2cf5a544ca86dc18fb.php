
<tr x-data="{ expanded: false }" class="bg-gray-50 dark:bg-gray-900/50 border-b dark:border-gray-700">
    <td colspan="6" class="p-0">
        <div class="p-4">
            
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 flex-grow cursor-pointer" @click="expanded = !expanded">
                    <!--[if BLOCK]><![endif]--><?php if($fatura->cartao->bandeira_path): ?>
                        <img src="<?php echo e($fatura->cartao->bandeira_path); ?>" alt="Bandeira" class="h-8 rounded-md">
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <div>
                        <div class="font-bold text-gray-800 dark:text-gray-200"><?php echo e($fatura->cartao->nome); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e(count($fatura->transacoes)); ?> despesas na fatura</div>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <div class="font-mono text-lg font-semibold text-red-500">- R$ <?php echo e(number_format($fatura->total, 2, ',', '.')); ?></div>
                        <div class="text-xs text-gray-500">Total da Fatura</div>
                    </div>
                    <?php
                        $faturaEstaPaga = $fatura->transacoes->every(fn($t) => $t->pago);
                    ?>

                    <button wire:click="pagarFatura" 
                            class="ml-4 px-3 py-1 text-xs font-medium text-white rounded-full 
                                   <?php echo e(!$faturaEstaPaga ? 'bg-blue-600 hover:bg-blue-700' : 'bg-gray-400 hover:bg-gray-500'); ?>">
                        <?php echo e(!$faturaEstaPaga ? 'Pagar Fatura' : 'Desfazer'); ?>

                    </button>
                    <button class="text-gray-400 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700" @click="expanded = !expanded">
                        <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7"></path></svg>
                        <svg x-show="expanded" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 15l7-7 7 7"></path></svg>
                    </button>
                </div>
            </div>

            
            <div x-show="expanded" x-transition class="mt-4 pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $fatura->transacoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transacao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('transacao-item', ['transacao' => $transacao, 'isDetalheFatura' => true]);

$__html = app('livewire')->mount($__name, $__params, 'fatura-'.$transacao->id, $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </td>
</tr>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/credit-card-summary.blade.php ENDPATH**/ ?>