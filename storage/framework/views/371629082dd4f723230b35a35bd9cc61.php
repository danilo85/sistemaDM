<form wire:submit="save">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome do Cartão (Ex: NuBank Ultravioleta)</label>
                <input type="text" wire:model="nome" id="nome" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" required>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div>
                <label for="bandeira" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Bandeira</label>
                <select wire:model="bandeira" id="bandeira" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione uma bandeira</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $bandeirasDisponiveis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bandeiraOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($bandeiraOption); ?>"><?php echo e($bandeiraOption); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['bandeira'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <div>
            <label for="limite_credito" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Limite de Crédito (R$)</label>
            <?php if (isset($component)) { $__componentOriginal69da9de12654df4a9ab7a38b7bdf72bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69da9de12654df4a9ab7a38b7bdf72bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.money-input','data' => ['wire:model' => 'limite_credito','name' => 'limite_credito','id' => 'limite_credito']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('money-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'limite_credito','name' => 'limite_credito','id' => 'limite_credito']); ?>
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
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['limite_credito'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="dia_fechamento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dia do Fechamento</label>
                <input type="number" wire:model="dia_fechamento" id="dia_fechamento" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" required min="1" max="31">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['dia_fechamento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div>
                <label for="dia_vencimento" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dia do Vencimento</label>
                <input type="number" wire:model="dia_vencimento" id="dia_vencimento" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500" required min="1" max="31">
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['dia_vencimento'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
    </div>

    
    <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
        <a href="<?php echo e(route('cartoes.index')); ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Cancelar
        </a>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            <?php echo e($cartao->exists ? 'Atualizar Cartão' : 'Salvar Cartão'); ?>

        </button>
    </div>
</form>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/cartao-form.blade.php ENDPATH**/ ?>