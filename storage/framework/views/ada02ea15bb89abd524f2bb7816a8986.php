<div class="space-y-4 max-h-96 overflow-y-auto pr-2">
    <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $atividades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $atividade): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="flex gap-3">
            <div class="flex-shrink-0 pt-1">
                <span class="inline-block h-2 w-2 rounded-full bg-gray-300 dark:bg-gray-600"></span>
            </div>
            <div>
                
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
                    <?php echo $this->getFormattedDescription($atividade); ?>

                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Por <?php echo e($atividade->causer->name ?? 'Sistema'); ?> em <?php echo e($atividade->created_at->format('d/m/Y \à\s H:i')); ?>

                </p>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <p class="text-sm text-gray-500">Nenhuma alteração registrada ainda.</p>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
</div><?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/orcamento-history.blade.php ENDPATH**/ ?>