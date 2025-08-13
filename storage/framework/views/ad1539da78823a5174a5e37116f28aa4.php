<div class="space-y-8">
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row items-start gap-6">
            <div class="flex-shrink-0 w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                <span class="text-3xl font-bold text-gray-500"><?php echo e(substr($autor->name, 0, 1)); ?></span>
            </div>
            <div class="flex-grow">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100"><?php echo e($autor->name); ?></h2>
                <p class="mt-1 text-gray-600 dark:text-gray-400"><?php echo e($autor->bio); ?></p>
                
                
                <div class="mt-4 flex flex-wrap gap-6 text-sm">
                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 4h5m-5 4h5"></path></svg>
                        <span><span class="font-bold"><?php echo e($totalProjetos); ?></span> Projetos Participados</span>
                    </div>
                    
                    <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01M12 6v-1m0-1V4m0 2.01v.01M12 18v-1m0-1v.01m0-1V14m0 2.01v.01M12 20v-1m0-1v.01m0-1V18m0 2.01v.01M12 4h.01M12 20h.01M4 12h.01M20 12h.01M6.343 17.657h.01M17.657 6.343h.01M6.343 6.343h.01M17.657 17.657h.01"></path></svg>
                        <span>Valor Gerado: <span class="font-bold">R$ <?php echo e(number_format($valorTotalProjetos, 2, ',', '.')); ?></span></span>
                    </div>
                    <!--[if BLOCK]><![endif]--><?php if($principalParceiro): ?>
                        <div class="flex items-center gap-2 text-gray-700 dark:text-gray-300">
                            <svg class="w-5 h-5 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span>Principal Parceiro: <span class="font-bold"><?php echo e($principalParceiro->name); ?></span></span>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>

    
    <div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Linha do Tempo de Projetos</h3>
        <div class="relative border-l-2 border-gray-200 dark:border-gray-700 pl-6">
            <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $orcamentos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $orcamento): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="mb-8">
                    <span class="absolute -left-3 flex h-6 w-6 items-center justify-center rounded-full bg-blue-200 dark:bg-blue-900 ring-8 ring-white dark:ring-gray-800">
                        <svg class="h-3 w-3 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"></path></svg>
                    </span>
                    
                    <a href="<?php echo e(route('orcamentos.show', ['orcamento' => $orcamento, 'back_url' => url()->current()])); ?>" class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($orcamento->created_at->format('d/m/Y')); ?></p>
                                <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100"><?php echo e($orcamento->titulo); ?></h4>
                                <p class="text-sm text-gray-600 dark:text-gray-300">Para: <?php echo e($orcamento->cliente->name); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></p>
                                
                                <?php
                                    $faltaPagar = $orcamento->valor_total - $orcamento->pagamentos_sum_valor_pago;
                                ?>
                                <p class="text-xs font-semibold <?php echo e($faltaPagar > 0 ? 'text-red-500' : 'text-green-500'); ?>">
                                    <?php echo e($faltaPagar > 0 ? 'Falta R$ ' . number_format($faltaPagar, 2, ',', '.') : 'Quitado'); ?>

                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-4 pt-4 border-t dark:border-gray-700 flex justify-between items-center">
                            
                            <div>
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
                            </div>
                            
                            <!--[if BLOCK]><![endif]--><?php if($orcamento->autores->count() > 1): ?>
                                <div class="flex items-center -space-x-2">
                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $orcamento->autores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $colaborador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <!--[if BLOCK]><![endif]--><?php if($colaborador->id !== $autor->id): ?>
                                            <div class="h-8 w-8 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center text-sm font-bold text-gray-700 dark:text-gray-200 ring-2 ring-white dark:ring-gray-800" title="<?php echo e($colaborador->name); ?>">
                                                <?php echo e(substr($colaborador->name, 0, 1)); ?>

                                            </div>
                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow">
                    <p class="text-center text-gray-500 dark:text-gray-400">Nenhum projeto encontrado para este autor.</p>
                </div>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/autor/autor-portfolio.blade.php ENDPATH**/ ?>