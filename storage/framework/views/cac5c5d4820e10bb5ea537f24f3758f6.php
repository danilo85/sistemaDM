<?php
    // Define um valor padrão para isDetalheFatura, caso não seja passado
    $isDetalheFatura = $isDetalheFatura ?? false;
?>


<div class="relative overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 <?php echo e($transacao->tipo === 'receita' ? 'bg-gradient-to-br from-green-500 via-green-600 to-green-700' : 'bg-gradient-to-br from-red-500 via-red-600 to-red-700'); ?>">
    
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300"></div>
    
    <div class="relative p-6 text-white">
        
        <div class="flex sm:hidden flex-col items-center text-center gap-3 mb-4">
            
            <!--[if BLOCK]><![endif]--><?php if($transacao->transacionavel_type === 'App\\Models\\Banco'): ?>
                <!--[if BLOCK]><![endif]--><?php if($transacao->transacionavel && $transacao->transacionavel->logo_path): ?>
                    <div class="w-14 h-14 bg-white rounded-full p-2 flex items-center justify-center flex-shrink-0">
                        <img src="<?php echo e($transacao->transacionavel->logo_path); ?>" alt="Logo" class="w-10 h-10 object-contain">
                    </div>
                <?php else: ?>
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php else: ?>
                <!--[if BLOCK]><![endif]--><?php if($transacao->transacionavel && $transacao->transacionavel->bandeira_path): ?>
                    <div class="w-14 h-14 bg-white rounded-full p-2 flex items-center justify-center flex-shrink-0">
                        <img src="<?php echo e($transacao->transacionavel->bandeira_path); ?>" alt="Bandeira" class="h-8 w-12 object-contain">
                    </div>
                <?php else: ?>
                    <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-7 h-7 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            
            <h3 class="text-lg font-bold text-white">
                <?php echo e($transacao->descricao); ?>

            </h3>
            
            
            <div class="text-2xl font-black text-white drop-shadow-lg">
                <?php echo e($transacao->tipo === 'receita' ? '+' : '-'); ?> R$ <?php echo e(number_format($transacao->valor, 2, ',', '.')); ?>

            </div>
            
            
            <div class="flex flex-wrap justify-center gap-2">
                <!--[if BLOCK]><![endif]--><?php if($transacao->parcela_total > 1): ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white text-gray-800">
                        <?php echo e($transacao->parcela_atual); ?>/<?php echo e($transacao->parcela_total); ?>

                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if($transacao->is_recurring): ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white text-gray-800">
                        Recorrente
                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if($transacao->pago): ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white text-green-800">
                        ✓ Pago
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white text-yellow-900">
                        ⏳ Pendente
                    </span>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        
        
        <div class="hidden sm:flex items-start justify-between mb-4">
            <div class="flex items-center gap-4 flex-1 min-w-0">
                
                <!--[if BLOCK]><![endif]--><?php if($transacao->transacionavel_type === 'App\\Models\\Banco'): ?>
                    <!--[if BLOCK]><![endif]--><?php if($transacao->transacionavel && $transacao->transacionavel->logo_path): ?>
                        <div class="w-14 h-14 bg-white rounded-full p-2 flex items-center justify-center flex-shrink-0">
                            <img src="<?php echo e($transacao->transacionavel->logo_path); ?>" alt="Logo" class="w-10 h-10 object-contain">
                        </div>
                    <?php else: ?>
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php else: ?>
                    <!--[if BLOCK]><![endif]--><?php if($transacao->transacionavel && $transacao->transacionavel->bandeira_path): ?>
                        <div class="w-14 h-14 bg-white rounded-full p-2 flex items-center justify-center flex-shrink-0">
                            <img src="<?php echo e($transacao->transacionavel->bandeira_path); ?>" alt="Bandeira" class="h-8 w-12 object-contain">
                        </div>
                    <?php else: ?>
                        <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <div class="flex-1 min-w-0">
                    <h3 class="text-lg font-bold text-white mb-1 truncate">
                        <?php echo e($transacao->descricao); ?>

                    </h3>
                    
                    
                    <div class="flex flex-wrap gap-2 mb-2">
                        <!--[if BLOCK]><![endif]--><?php if($transacao->parcela_total > 1): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white text-gray-800">
                                <?php echo e($transacao->parcela_atual); ?>/<?php echo e($transacao->parcela_total); ?>

                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <!--[if BLOCK]><![endif]--><?php if($transacao->is_recurring): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white text-gray-800">
                                Recorrente
                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <!--[if BLOCK]><![endif]--><?php if($transacao->pago): ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white text-green-800">
                                ✓ Pago
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-white text-yellow-900">
                                ⏳ Pendente
                            </span>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
            </div>
            
            
            <div class="text-right ml-4">
                <div class="text-2xl font-black text-white drop-shadow-lg">
                    <?php echo e($transacao->tipo === 'receita' ? '+' : '-'); ?> R$ <?php echo e(number_format($transacao->valor, 2, ',', '.')); ?>

                </div>
            </div>
        </div>
        
        
        <div class="flex items-center justify-between text-white/80 text-sm mb-4">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <?php echo e(\Carbon\Carbon::parse($transacao->data)->format('d/m/Y')); ?>

                </div>
                
                <!--[if BLOCK]><![endif]--><?php if($transacao->categoria): ?>
                    <div class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <?php echo e($transacao->categoria->nome); ?>

                    </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        
        
        <div class="flex flex-wrap items-center gap-2">
            <!--[if BLOCK]><![endif]--><?php if (! ($isDetalheFatura)): ?>
                <button wire:click="togglePago" 
                        class="<?php echo e($transacao->pago ? 'bg-orange-500/80 hover:bg-orange-400/80' : 'bg-white/20 hover:bg-white/30'); ?> backdrop-blur-sm text-white font-semibold rounded-lg px-4 py-2 text-sm flex items-center gap-2 transition-all duration-200 transform hover:scale-105"
                        title="<?php echo e($transacao->pago ? 'Marcar como Pendente' : 'Marcar como Pago'); ?>">
                    <!--[if BLOCK]><![endif]--><?php if($transacao->pago): ?>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden sm:inline">Marcar Pendente</span>
                    <?php else: ?>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <span class="hidden sm:inline">Pagar</span>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </button>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            
            <button wire:click="duplicate" 
                    class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold rounded-lg px-4 py-2 text-sm flex items-center gap-2 transition-all duration-200 transform hover:scale-105"
                    title="Duplicar">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <span class="hidden sm:inline">Duplicar</span>
            </button>
            
            <a href="<?php echo e(route('transacoes.edit', $transacao->id)); ?>" 
               class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white font-semibold rounded-lg px-4 py-2 text-sm flex items-center gap-2 transition-all duration-200 transform hover:scale-105"
               title="Editar">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z" />
                </svg>
                <span class="hidden sm:inline">Editar</span>
            </a>
            
            <button wire:click="confirmDelete" 
                    class="bg-red-500/80 hover:bg-red-400/80 backdrop-blur-sm text-white font-semibold rounded-lg px-4 py-2 text-sm flex items-center gap-2 transition-all duration-200 transform hover:scale-105"
                    title="Excluir">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span class="hidden sm:inline">Excluir</span>
            </button>
        </div>
    </div>
</div>


<!--[if BLOCK]><![endif]--><?php if($showConfirmModal): ?>
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" wire:click="$set('showConfirmModal', false)">
        <div class="relative top-20 mx-auto p-6 border-0 w-96 shadow-2xl rounded-2xl bg-white dark:bg-gray-800" wire:click.stop>
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/50 mb-4">
                    <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-xl leading-6 font-bold text-gray-900 dark:text-white mb-2">Excluir Lançamento</h3>
                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Tem certeza que deseja excluir este lançamento?
                        <!--[if BLOCK]><![endif]--><?php if($transacao->compra_id && $transacao->parcela_total > 1): ?>
                            <br><br>
                            <strong class="text-red-600 dark:text-red-400">Este lançamento faz parte de uma compra parcelada.</strong>
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </p>
                </div>
                <div class="space-y-3">
                    <!--[if BLOCK]><![endif]--><?php if($transacao->compra_id && $transacao->parcela_total > 1): ?>
                        <button wire:click="deleteSingle" 
                                class="w-full px-6 py-3 bg-red-500 text-white font-semibold rounded-xl shadow-lg hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300 transition-all duration-200">
                            Excluir apenas esta parcela
                        </button>
                        <button wire:click="deleteAll" 
                                class="w-full px-6 py-3 bg-red-700 text-white font-semibold rounded-xl shadow-lg hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 transition-all duration-200">
                            Excluir todas as parcelas
                        </button>
                        <button wire:click="$set('showConfirmModal', false)" 
                                class="w-full px-6 py-3 bg-gray-500 text-white font-semibold rounded-xl shadow-lg hover:bg-gray-600 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-200">
                            Cancelar
                        </button>
                    <?php else: ?>
                        <div class="flex gap-3">
                            <button wire:click="deleteSingle" 
                                    class="flex-1 px-6 py-3 bg-red-500 text-white font-semibold rounded-xl shadow-lg hover:bg-red-600 focus:outline-none focus:ring-4 focus:ring-red-300 transition-all duration-200">
                                Sim, excluir
                            </button>
                            <button wire:click="$set('showConfirmModal', false)" 
                                    class="flex-1 px-6 py-3 bg-gray-500 text-white font-semibold rounded-xl shadow-lg hover:bg-gray-600 focus:outline-none focus:ring-4 focus:ring-gray-300 transition-all duration-200">
                                Cancelar
                            </button>
                        </div>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </div>
            </div>
        </div>
    </div>
<?php endif; ?><!--[if ENDBLOCK]><![endif]-->
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/transacao-item.blade.php ENDPATH**/ ?>