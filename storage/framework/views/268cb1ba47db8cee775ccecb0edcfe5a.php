<div>
     <?php $__env->slot('header', null, []); ?> 
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Pipeline de Portfólio
            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    Aqui estão os seus orçamentos concluídos que estão prontos para serem adicionados ao seu portfólio público.
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Projeto</th>
                                <th scope="col" class="px-6 py-3">Cliente</th>
                                <th scope="col" class="px-6 py-3">Data de Conclusão</th>
                                <th scope="col" class="px-6 py-3 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $orcamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"><?php echo e($orcamento->titulo); ?></td>
                                    <td class="px-6 py-4"><?php echo e($orcamento->cliente->name); ?></td>
                                    <td class="px-6 py-4"><?php echo e($orcamento->updated_at->format('d/m/Y')); ?></td>
                                    <td class="px-6 py-4 text-right">
                                            <a href="<?php echo e(route('portfolio.create', ['orcamento_id' => $orcamento->id])); ?>" class="inline-flex items-center p-2 text-blue-600 dark:text-blue-500 hover:bg-blue-100 dark:hover:bg-blue-900 rounded-lg transition-colors" title="Adicionar ao Portfólio">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </a>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">Nenhum trabalho novo no pipeline.</td>
                                </tr>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </tbody>
                    </table>
                </div>
                <div class="mt-4"><?php echo e($orcamentos->links()); ?></div>
            </div>
        </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/portfolio/pipeline.blade.php ENDPATH**/ ?>