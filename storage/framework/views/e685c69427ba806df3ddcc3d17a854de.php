<tr @dblclick="toggleSelection(<?php echo e($orcamento->id); ?>, '<?php echo e($orcamento->valor_total); ?>', $event)"
    :class="{ '!bg-blue-100 dark:!bg-blue-900/50': selectedIds.includes(<?php echo e($orcamento->id); ?>) }" 
    class="border-b bg-white dark:border-gray-700 dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 cursor-pointer">
    
    
    <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
        <a href="<?php echo e(route('orcamentos.show', $orcamento->id)); ?>" class="hover:underline">
            <span class="font-bold">#<?php echo e($orcamento->numero); ?></span> - <?php echo e($orcamento->cliente->name ?? 'N/A'); ?>

        </a>
    </td>
    
    <td class="px-6 py-4">
        <?php echo e($orcamento->titulo ?? 'Sem Título'); ?>

    </td>
    
    <td class="px-6 py-4">
        <div x-data="{ 
                open: false,
                position: { top: '0px', left: '0px' },
                toggle(event) {
                    if (this.open) {
                        this.open = false;
                        return;
                    }
                    
                    const buttonRect = event.currentTarget.getBoundingClientRect();
                    
                    // Define uma posição inicial e abre o modal
                    this.position = { top: `${buttonRect.bottom + 4}px`, left: `${buttonRect.left}px` };
                    this.open = true;

                    // No próximo ciclo, recalcula a posição final
                    this.$nextTick(() => {
                        const modal = this.$refs.modal;
                        if (!modal) return;

                        let finalTop = buttonRect.bottom + 4;
                        let finalLeft = buttonRect.left;

                        // Verifica se o modal vai sair da tela por baixo
                        if (buttonRect.bottom + modal.offsetHeight > window.innerHeight) {
                            finalTop = buttonRect.top - modal.offsetHeight - 4;
                        }
                        // Verifica se o modal vai sair da tela pela direita
                        if (buttonRect.left + modal.offsetWidth > window.innerWidth) {
                            finalLeft = buttonRect.right - modal.offsetWidth;
                        }

                        this.position = { top: `${finalTop}px`, left: `${finalLeft}px` };
                    });
                }
             }">

            
            <button @click="toggle($event)" type="button" class="inline-flex items-center">
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
                <svg class="ml-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            
            <template x-teleport="body">
                <div x-show="open"
                     x-ref="modal"
                     x-transition
                     @click.outside="open = false"
                     :style="`position: fixed; top: ${position.top}; left: ${position.left};`"
                     class="z-50 w-56 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-700">
                    <div class="py-1" role="menu" aria-orientation="vertical">
                        <a href="#" wire:click.prevent="mudarStatus('Analisando')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Analisando</a>
                        <a href="#" wire:click.prevent="mudarStatus('Aprovado')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Aprovado</a>
                        <a href="#" wire:click.prevent="mudarStatus('Rejeitado')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Rejeitado</a>
                        <a href="#" wire:click.prevent="mudarStatus('Finalizado')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Finalizado</a>
                        <a href="#" wire:click.prevent="mudarStatus('Pago')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-600">Pago</a>
                    </div>
                </div>
            </template>
        </div>
    </td>
    
    <td class="px-6 py-4 text-right text-lg font-semibold text-green-600">
        <span class="whitespace-nowrap">R$ <?php echo e(number_format($orcamento->valor_total, 2, ',', '.')); ?></span>
    </td>
    
    <td class="px-6 py-4">
        <?php echo e($orcamento->created_at->format('d/m/Y')); ?>

    </td>
    
    
    <td class="px-6 py-4 text-right">
        <div class="flex items-center justify-end gap-4">
            <button wire:click.prevent="duplicar" class="text-gray-500 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-500" title="Duplicar">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2-2H9a2 2 0 01-2-2V9z" /><path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h6a2 2 0 00-2-2H5z" /></svg>
            </button>
            <a href="<?php echo e(route('orcamentos.edit', $orcamento->id)); ?>" class="text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" title="Editar">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.5L16.732 3.732z"></path></svg>
            </a>
            <button wire:click="excluirOrcamento" wire:confirm="Tem certeza?" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" title="Excluir">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
    </td>
</tr>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/orcamento-row.blade.php ENDPATH**/ ?>