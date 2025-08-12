<div class="space-y-6">
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-x-8 gap-y-6">
            
            <div class="lg:col-span-2">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                    <?php echo e($pagamentoEmEdicao ? 'Editar Pagamento' : 'Registrar Novo Pagamento'); ?>

                </h3>
                
                <form wire:submit="salvarPagamento">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        
                        <div>
                            <label for="valor-pago" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Valor (R$)</label>
                            <?php if (isset($component)) { $__componentOriginal69da9de12654df4a9ab7a38b7bdf72bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69da9de12654df4a9ab7a38b7bdf72bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.money-input','data' => ['name' => 'valor','wire:model' => 'valor','id' => 'valor-pago']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('money-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'valor','wire:model' => 'valor','id' => 'valor-pago']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69da9de12654df4a9ab7a38b7bdf72bc)): ?>
<?php $attributes = $__attributesOriginal69da9de12654df4a9ab7a38b7bdf72bc; ?>
<?php unset($__attributesOriginal69da9de12654df4a9ab7a38b7bdf72bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69da9de12654df4a9ab7a38b7bdf72bc)): ?>
<?php $component = $__componentOriginal69da9de12654df4a9ab7a38b7bdf72bc; ?>
<?php unset($__componentOriginal69da9de12654df4a9ab7a38b7bdf72bc); ?>
<?php endif; ?>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['valor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div>
                            <label for="data-pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data</label>
                             <?php if (isset($component)) { $__componentOriginal37e12294b28f0bd91a733acab9bb06c5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37e12294b28f0bd91a733acab9bb06c5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.date-picker','data' => ['name' => 'data_pagamento','wire:model' => 'data_pagamento','id' => 'data-pagamento','value' => $data_pagamento,'class' => 'block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('date-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'data_pagamento','wire:model' => 'data_pagamento','id' => 'data-pagamento','value' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($data_pagamento),'class' => 'block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal37e12294b28f0bd91a733acab9bb06c5)): ?>
<?php $attributes = $__attributesOriginal37e12294b28f0bd91a733acab9bb06c5; ?>
<?php unset($__attributesOriginal37e12294b28f0bd91a733acab9bb06c5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal37e12294b28f0bd91a733acab9bb06c5)): ?>
<?php $component = $__componentOriginal37e12294b28f0bd91a733acab9bb06c5; ?>
<?php unset($__componentOriginal37e12294b28f0bd91a733acab9bb06c5); ?>
<?php endif; ?>
                            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['data_pagamento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
                        </div>

                        
                        <div>
                            <label for="tipo_pagamento" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Forma de Pagamento</label>
                            <select wire:model="tipo_pagamento" id="tipo_pagamento" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                                <option value="PIX">PIX</option>
                                <option value="Transferência">Transferência</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Pagamento Final">Pagamento Final</option>
                                <option value="Entrada">Entrada</option>
                                <option value="Outro">Outro</option>
                            </select>
                        </div>

                        
                        <div>
                            <label for="conta_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Creditar na Conta</label>
                            <select wire:model="conta_id" id="conta_id" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione...</option>
                                <optgroup label="Contas Bancárias">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="Banco_<?php echo e($banco->id); ?>"><?php echo e($banco->nome); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    
                    <div class="mt-6 flex items-center justify-end gap-4 pt-4 border-t dark:border-gray-700">
                        <!--[if BLOCK]><![endif]--><?php if($pagamentoEmEdicao): ?>
                            <button type="button" wire:click="cancelarEdicao" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">Cancelar Edição</button>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-bold text-white hover:bg-indigo-700 transition-colors">
                            <span wire:loading.remove wire:target="salvarPagamento"><?php echo e($pagamentoEmEdicao ? 'Atualizar Pagamento' : 'Registrar Pagamento'); ?></span>
                            <span wire:loading wire:target="salvarPagamento">Salvando...</span>
                        </button>
                    </div>
                </form>
            </div>

            
            <div class="bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6 space-y-6 border dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Resumo Financeiro</h3>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total do Orçamento</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Pago</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">R$ <?php echo e(number_format($totalPago, 2, ',', '.')); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Restante</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">R$ <?php echo e(number_format($restante, 2, ',', '.')); ?></p>
                </div>
                <!--[if BLOCK]><![endif]--><?php if($restante > 0 && !$pagamentoEmEdicao): ?>
                    <button wire:click="quitarOrcamento" wire:confirm="Tem a certeza que deseja quitar o valor restante de R$ <?php echo e(number_format($restante, 2, ',', '.')); ?>?" class="w-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 font-semibold py-2 px-4 rounded-lg hover:bg-green-200 dark:hover:bg-green-800">
                        Quitar Orçamento
                    </button>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>

    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Histórico de Pagamentos</h3>
        <div class="space-y-3">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $pagamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-full">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-300" fill="currentColor" viewBox="0 0 20 20"><path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z"></path></svg>
                        </div>
                        <div>
                            <p class="font-semibold dark:text-gray-200">R$ <?php echo e(number_format($pagamento->valor_pago, 2, ',', '.')); ?></p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <?php echo e($pagamento->tipo_pagamento); ?> em <?php echo e($pagamento->data_pagamento->format('d/m/Y')); ?>

                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <button wire:click="generateReceipt(<?php echo e($pagamento->id); ?>)" title="Gerar Recibo" class="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </button>
                        <button wire:click="editarPagamento(<?php echo e($pagamento->id); ?>)" title="Editar" class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="excluirPagamento(<?php echo e($pagamento->id); ?>)" wire:confirm="Tem a certeza?" title="Excluir" class="text-gray-500 hover:text-red-600 dark:hover:text-red-400">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-sm text-center text-gray-500 p-4">Nenhum pagamento registado.</div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/orcamento-pagamentos.blade.php ENDPATH**/ ?>