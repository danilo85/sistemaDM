<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header do Cliente -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
        <div class="px-6 py-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center space-x-4">
                    <!--[if BLOCK]><![endif]--><?php if($user && $user->logo_path): ?>
                        <img src="<?php echo e(Storage::url($user->logo_path)); ?>" alt="Logo" class="h-16 w-auto object-contain">
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900"><?php echo e($cliente->name); ?></h1>
                        <p class="mt-2 text-gray-600">
                            <!--[if BLOCK]><![endif]--><?php if($cliente->contact_person): ?>
                                Contato: <?php echo e($cliente->contact_person); ?>

                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            <!--[if BLOCK]><![endif]--><?php if($cliente->email): ?>
                                • <?php echo e($cliente->email); ?>

                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        </p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm2 6a2 2 0 114 0 2 2 0 01-4 0zm8 0a2 2 0 114 0 2 2 0 01-4 0z" clip-rule="evenodd"></path>
                        </svg>
                        Extrato Financeiro
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total em Projetos -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total em Projetos</p>
                    <p class="text-2xl font-bold text-gray-900">R$ <?php echo e(number_format($totalEmProjetos, 2, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Total Pago -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pago</p>
                    <p class="text-2xl font-bold text-green-600">R$ <?php echo e(number_format($totalPago, 2, ',', '.')); ?></p>
                </div>
            </div>
        </div>

        <!-- Saldo Devedor -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 <?php echo e($saldoDevedor > 0 ? 'bg-red-100' : 'bg-gray-100'); ?> rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 <?php echo e($saldoDevedor > 0 ? 'text-red-600' : 'text-gray-600'); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Saldo Devedor</p>
                    <p class="text-2xl font-bold <?php echo e($saldoDevedor > 0 ? 'text-red-600' : 'text-gray-900'); ?>">
                        R$ <?php echo e(number_format($saldoDevedor, 2, ',', '.')); ?>

                    </p>
                </div>
            </div>
        </div>

        <!-- Taxa de Aprovação -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Taxa de Aprovação</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo e($taxaAprovacao); ?>%</p>
                    <p class="text-xs text-gray-500"><?php echo e($orcamentosAprovados); ?>/<?php echo e($totalOrcamentos); ?> orçamentos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Período -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 p-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Período do Extrato</h2>
            <div class="text-sm text-gray-600">
                <?php echo e(\Carbon\Carbon::parse($dataInicio)->format('d/m/Y')); ?> até <?php echo e(\Carbon\Carbon::parse($dataFim)->format('d/m/Y')); ?>

            </div>
        </div>
    </div>

    <!-- Débitos e Créditos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Débitos (Projetos Aprovados) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Débitos (Projetos Aprovados)
                    <span class="ml-2 text-sm font-normal text-gray-500">(<?php echo e(count($debitos)); ?>)</span>
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $debitos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $debito): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">#<?php echo e($debito['numero']); ?></h4>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($debito['titulo']); ?></p>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e($debito['data']); ?></p>
                            </div>
                            <div class="text-right ml-4">
                                <p class="font-semibold text-gray-900">R$ <?php echo e(number_format($debito['valor'], 2, ',', '.')); ?></p>
                                <!--[if BLOCK]><![endif]--><?php if($debito['valor_pago'] > 0): ?>
                                    <p class="text-xs text-green-600">Pago: R$ <?php echo e(number_format($debito['valor_pago'], 2, ',', '.')); ?></p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                <!--[if BLOCK]><![endif]--><?php if($debito['saldo_devedor'] > 0): ?>
                                    <p class="text-xs text-red-600">Pendente: R$ <?php echo e(number_format($debito['saldo_devedor'], 2, ',', '.')); ?></p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-6 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p>Nenhum projeto aprovado no período selecionado.</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <!-- Créditos (Pagamentos) -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                    Créditos (Pagamentos Recebidos)
                    <span class="ml-2 text-sm font-normal text-gray-500">(<?php echo e(count($creditos)); ?>)</span>
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                <!--[if BLOCK]><![endif]--><?php $__empty_1 = true; $__currentLoopData = $creditos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $credito): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">#<?php echo e($credito['orcamento_numero']); ?></h4>
                                <p class="text-sm text-gray-600 mt-1"><?php echo e($credito['orcamento_titulo']); ?></p>
                                <p class="text-xs text-gray-500 mt-1"><?php echo e($credito['data']); ?></p>
                                <!--[if BLOCK]><![endif]--><?php if($credito['descricao']): ?>
                                    <p class="text-xs text-gray-500 mt-1"><?php echo e($credito['descricao']); ?></p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                            <div class="text-right ml-4">
                                <p class="font-semibold text-green-600">R$ <?php echo e(number_format($credito['valor'], 2, ',', '.')); ?></p>
                                <!--[if BLOCK]><![endif]--><?php if($credito['metodo']): ?>
                                    <p class="text-xs text-gray-500"><?php echo e($credito['metodo']); ?></p>
                                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="p-6 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        <p>Nenhum pagamento recebido no período selecionado.</p>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>

    <!-- Resumo Final -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mt-8 p-6">
        <div class="text-center">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Resumo do Período</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <p class="text-sm text-gray-600">Total Faturado</p>
                    <p class="text-xl font-bold text-blue-600">R$ <?php echo e(number_format($totalEmProjetos, 2, ',', '.')); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Recebido</p>
                    <p class="text-xl font-bold text-green-600">R$ <?php echo e(number_format($totalPago, 2, ',', '.')); ?></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Saldo Pendente</p>
                    <p class="text-xl font-bold <?php echo e($saldoDevedor > 0 ? 'text-red-600' : 'text-gray-900'); ?>">
                        R$ <?php echo e(number_format($saldoDevedor, 2, ',', '.')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Nota de Atualização -->
    <div class="mt-8 text-center text-sm text-gray-500">
        <p>Este extrato é atualizado automaticamente. Última atualização: <?php echo e(now()->format('d/m/Y H:i')); ?></p>
    </div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/public-extrato-view.blade.php ENDPATH**/ ?>