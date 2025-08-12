<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if (isset($component)) { $__componentOriginal522a3c8aadb4ccbcc229d59265244da1 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal522a3c8aadb4ccbcc229d59265244da1 = $attributes; } ?>
<?php $component = App\View\Components\PageHeader::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('page-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PageHeader::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                 <?php $__env->slot('title', null, []); ?> 
                    <div class="flex items-center gap-4">
                        
                        <a href="<?php echo e(route('orcamentos.index')); ?>" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white" title="Voltar">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                        <div>
                            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                                Orçamento #<?php echo e($orcamento->numero); ?>

                            </h2>
                            <span class="text-sm text-gray-500"><?php echo e($orcamento->titulo); ?></span>
                        </div>
                    </div>
                 <?php $__env->endSlot(); ?>
                 <?php $__env->slot('actions', null, []); ?> 
                    <a href="<?php echo e(route('orcamentos.edit', $orcamento->id)); ?>" class="rounded-lg bg-blue-600 px-4 py-2 font-bold text-white hover:bg-blue-700">
                        Editar Orçamento
                    </a>
                 <?php $__env->endSlot(); ?>
             <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal522a3c8aadb4ccbcc229d59265244da1)): ?>
<?php $attributes = $__attributesOriginal522a3c8aadb4ccbcc229d59265244da1; ?>
<?php unset($__attributesOriginal522a3c8aadb4ccbcc229d59265244da1); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal522a3c8aadb4ccbcc229d59265244da1)): ?>
<?php $component = $__componentOriginal522a3c8aadb4ccbcc229d59265244da1; ?>
<?php unset($__componentOriginal522a3c8aadb4ccbcc229d59265244da1); ?>
<?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                
                
                
                <div class="lg:col-span-2 space-y-6">

                    
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Detalhes do Orçamento</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-4 gap-y-6">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Cliente</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200"><?php echo e($orcamento->cliente->name); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Autores</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200"><?php echo e($orcamento->autores->pluck('name')->join(', ')); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                        <dd class="mt-1 text-sm">
                                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('orcamento-status-updater', ['orcamento' => $orcamento]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3161975401-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                        </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Total</dt>
                                    <dd class="mt-1 text-sm font-mono text-gray-900 dark:text-gray-200">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Emissão</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200"><?php echo e($orcamento->data_emissao->format('d/m/Y')); ?></dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Validade</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200"><?php echo e($orcamento->data_validade->format('d/m/Y')); ?></dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Descrição do Serviço</h3>
                            <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300">
                               <?php echo $orcamento->descricao; ?>

                            </div>
                        </div>
                    </div>

                    
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('orcamento-pagamentos', ['orcamento' => $orcamento]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3161975401-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                </div>

                
                
                
                <div class="lg:col-span-1 space-y-6">

                    
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6" x-data="{ 
                            linkCopiado: false,
                            copiarLink(url) {
                                // Usa um método de fallback para garantir a compatibilidade
                                const textarea = document.createElement('textarea');
                                textarea.value = url;
                                document.body.appendChild(textarea);
                                textarea.select();
                                document.execCommand('copy');
                                document.body.removeChild(textarea);

                                this.linkCopiado = true;
                                setTimeout(() => { this.linkCopiado = false; }, 2500);
                            }
                        }">
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Ações</h3>
                            <div class="space-y-3">
                                <a href="<?php echo e($orcamento->public_url); ?>" target="_blank" class="w-full flex items-center justify-center gap-2 text-center rounded-lg bg-gray-500 px-4 py-2 font-bold text-white hover:bg-gray-600 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z" /><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.022 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" /></svg>
                                    <span>Visualizar como Cliente</span>
                                </a>
                                <button @click="copiarLink('<?php echo e($orcamento->public_url); ?>')" class="w-full flex items-center justify-center gap-2 text-center rounded-lg bg-blue-600 px-4 py-2 font-bold text-white hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 005.656 5.656l-1.5 1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd" /></svg>
                                    <span x-show="!linkCopiado">Copiar Link Público</span>
                                    <span x-show="linkCopiado" class="text-green-300">Link Copiado! ✓</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Histórico de Alterações</h3>
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('orcamento-history', ['orcamentoId' => $orcamento->id]);

$__html = app('livewire')->mount($__name, $__params, 'lw-3161975401-2', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/admin/orcamentos/show.blade.php ENDPATH**/ ?>