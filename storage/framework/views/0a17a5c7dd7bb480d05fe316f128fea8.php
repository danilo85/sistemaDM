<form wire:submit="save">
    <div class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div>
                <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome da Conta</label>
                <select wire:model="nome" id="nome" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione um banco</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $bancosDisponiveis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bancoNome): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($bancoNome); ?>"><?php echo e($bancoNome); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>

            
            <div>
                <label for="tipo_conta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Conta</label>
                <select wire:model="tipo_conta" id="tipo_conta" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="Corrente">Corrente</option>
                    <option value="Poupança">Poupança</option>
                    <option value="Investimentos">Investimentos</option>
                    <option value="Outro">Outro</option>
                </select>
            </div>
        </div>
        
        
        <div>
            <label for="saldo_inicial" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Saldo Inicial (R$)</label>
            <?php if (isset($component)) { $__componentOriginal69da9de12654df4a9ab7a38b7bdf72bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69da9de12654df4a9ab7a38b7bdf72bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.money-input','data' => ['wire:model' => 'saldo_inicial','name' => 'saldo_inicial','id' => 'saldo_inicial']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('money-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'saldo_inicial','name' => 'saldo_inicial','id' => 'saldo_inicial']); ?>
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
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['saldo_inicial'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
    </div>

    
    <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
        <a href="<?php echo e(route('bancos.index')); ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Cancelar
        </a>
        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            
            <?php echo e($banco->exists ? 'Atualizar Conta' : 'Salvar Conta'); ?>

        </button>
    </div>
</form>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/banco-form.blade.php ENDPATH**/ ?>