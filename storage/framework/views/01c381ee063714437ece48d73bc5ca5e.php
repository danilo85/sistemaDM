
<div class="p-4 sm:p-6">
    
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        
        <div class="flex items-center justify-center">
            <a href="<?php echo e(route('transacoes.index', ['ano' => $dataAtual->copy()->subMonth()->year, 'mes' => $dataAtual->copy()->subMonth()->month])); ?>"
               class="p-2 text-gray-500 bg-white rounded-full hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 shadow-sm border dark:border-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            
            <div class="flex items-center gap-2 mx-4">
                <h3 class="text-xl font-semibold capitalize text-gray-800 dark:text-gray-200">
                    <?php echo e($tituloDoMes); ?>

                </h3>
                <a href="<?php echo e(route('transacoes.index')); ?>" class="text-blue-500 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300" title="Ir para o mês atual">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </a>
            </div>

            <a href="<?php echo e(route('transacoes.index', ['ano' => $dataAtual->copy()->addMonth()->year, 'mes' => $dataAtual->copy()->addMonth()->month])); ?>"
               class="p-2 text-gray-500 bg-white rounded-full hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 shadow-sm border dark:border-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        <div class="flex items-center gap-2">
            
            
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700" type="button" title="Filtrar por Conta">
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M7 12h10M10 18h4"/>
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 z-10 w-56 p-3 bg-white rounded-lg shadow dark:bg-gray-700 mt-1" style="display: none;">
                    <h6 class="mb-3 text-sm font-medium text-gray-900 dark:text-white">Filtrar por Conta</h6>
                    <ul class="space-y-1 text-sm">
                        <li class="cursor-pointer">
                            <label for="conta-todas" class="flex items-center w-full p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input id="conta-todas" type="radio" value="" wire:model.live="filtroConta" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:bg-gray-600 dark:border-gray-500">
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Todas as Contas</span>
                            </label>
                        </li>

                        <!--[if BLOCK]><![endif]--><?php if($bancos->isNotEmpty()): ?>
                            <li>
                                <h6 class="px-2 pt-2 text-xs font-bold text-gray-400 uppercase">Contas Bancárias</h6>
                            </li>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="cursor-pointer">
                                 <label for="banco-<?php echo e($banco->id); ?>" class="flex items-center w-full p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <input id="banco-<?php echo e($banco->id); ?>" type="radio" value="Banco_<?php echo e($banco->id); ?>" wire:model.live="filtroConta" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:bg-gray-600 dark:border-gray-500">
                                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"><?php echo e($banco->nome); ?></span>
                                </label>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                        <!--[if BLOCK]><![endif]--><?php if($cartoes->isNotEmpty()): ?>
                            <li>
                                <h6 class="px-2 pt-2 text-xs font-bold text-gray-400 uppercase">Cartões de Crédito</h6>
                            </li>
                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cartoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="cursor-pointer">
                                 <label for="cartao-<?php echo e($cartao->id); ?>" class="flex items-center w-full p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                    <input id="cartao-<?php echo e($cartao->id); ?>" type="radio" value="CartaoCredito_<?php echo e($cartao->id); ?>" wire:model.live="filtroConta" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:bg-gray-600 dark:border-gray-500">
                                    <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300"><?php echo e($cartao->nome); ?></span>
                                </label>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </ul>
                </div>
            </div>

            <div x-data="{ searchOpen: $wire.get('busca') !== '' }" class="relative flex items-center h-10">
                <div x-show="searchOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-x-0" x-transition:enter-end="opacity-100 scale-x-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-x-100" x-transition:leave-end="opacity-0 scale-x-0" @click.away="if($wire.get('busca') === '') searchOpen = false" class="relative origin-right" style="display: none;">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                    </div>
                    <input x-ref="searchInput" type="text" wire:model.live.debounce.300ms="busca" placeholder="Buscar..." class="block w-full sm:w-80 rounded-lg border-transparent bg-gray-100 dark:bg-gray-800 pl-10 pr-10 shadow-sm focus:ring-2 focus:ring-blue-500">
                    <div x-show="$wire.get('busca') !== ''" x-transition class="absolute inset-y-0 right-0 flex items-center pr-3">
                        <button wire:click="$set('busca', '')" @click="if($wire.get('busca') === '') searchOpen = false" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
                <button x-show="!searchOpen" @click="searchOpen = true; $nextTick(() => $refs.searchInput.focus())" type="button" class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>
                </button>
            </div>
        </div>
    </div>
    
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Data</th>
                    <th scope="col" class="px-6 py-3">Descrição</th>
                    <th scope="col" class="px-6 py-3">Categoria</th>
                    <th scope="col" class="px-6 py-3 text-right">Valor</th>
                    <th scope="col" class="px-6 py-3 text-center">Status</th>
                    <th scope="col" class="px-6 py-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
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
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="6" class="px-6 py-4 text-center">
                            Nenhum lançamento financeiro encontrado para este mês.
                        </td>
                    </tr>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
            </tbody>
        </table>
    </div>

    
    <div class="mt-8 grid grid-cols-1 gap-4 border-t border-gray-200 pt-6 text-center dark:border-gray-700 md:grid-cols-3">
        <div class="rounded-lg bg-green-50 p-4 dark:bg-green-900/50">
            <span class="block text-sm text-green-800 dark:text-green-300">Receitas do Mês</span>
            <span class="text-2xl font-bold text-green-600 dark:text-green-400 whitespace-nowrap">R$ <?php echo e(number_format($totalReceitas, 2, ',', '.')); ?></span>
        </div>
        <div class="rounded-lg bg-red-50 p-4 dark:bg-red-900/50">
            <span class="block text-sm text-red-800 dark:text-red-300">Despesas do Mês</span>
            <span class="text-2xl font-bold text-red-600 dark:text-red-400 whitespace-nowrap">R$ <?php echo e(number_format($totalDespesas, 2, ',', '.')); ?></span>
        </div>
        <div class="rounded-lg bg-blue-50 p-4 dark:bg-blue-900/50">
            <span class="block text-sm text-blue-800 dark:text-blue-300">Saldo do Mês</span>
            <span class="text-2xl font-bold <?php echo e($saldoDoMes >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-orange-500'); ?> whitespace-nowrap">
                R$ <?php echo e(number_format($saldoDoMes, 2, ',', '.')); ?>

            </span>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/transacao-list.blade.php ENDPATH**/ ?>