<form wire:submit="save" x-data="{ tipo: $wire.entangle('tipo', true) }">
    
    <div class="mb-6">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo</label>
        <div class="relative flex items-center justify-center p-1 rounded-lg bg-gray-100 dark:bg-gray-700">
            
            <div class="absolute left-1 h-full w-1/2 rounded-md transition-all duration-300 ease-in-out"
                 :class="{
                     'bg-green-600 shadow': tipo === 'receita',
                     'bg-red-600 shadow': tipo === 'despesa',
                     'transform translate-x-full': tipo === 'despesa'
                 }">
            </div>
            
            
            <button type="button" @click="tipo = 'receita'"
                    class="relative w-1/2 py-2 text-center rounded-md transition-colors text-sm font-semibold z-10"
                    :class="tipo === 'receita' ? 'text-white' : 'text-gray-600 dark:text-gray-400'">
                Receita
            </button>

            
            <button type="button" @click="tipo = 'despesa'"
                    class="relative w-1/2 py-2 text-center rounded-md transition-colors text-sm font-semibold z-10"
                    :class="tipo === 'despesa' ? 'text-white' : 'text-gray-600 dark:text-gray-400'">
                Despesa
            </button>
            
            
            <input type="radio" id="tipo-receita-toggle" value="receita" wire:model.live="tipo" class="hidden">
            <input type="radio" id="tipo-despesa-toggle" value="despesa" wire:model.live="tipo" class="hidden">
        </div>
    </div>

    
    <div class="space-y-6">
        <div>
            <label for="descricao" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Descrição</label>
            <input type="text" wire:model="descricao" id="descricao" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['descricao'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="valor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Valor (R$)</label>
                <?php if (isset($component)) { $__componentOriginal69da9de12654df4a9ab7a38b7bdf72bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69da9de12654df4a9ab7a38b7bdf72bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.money-input','data' => ['wire:model' => 'valor','name' => 'valor']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('money-input'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'valor','name' => 'valor']); ?>
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
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div>
                <label for="data" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Data</label>
                <?php if (isset($component)) { $__componentOriginal37e12294b28f0bd91a733acab9bb06c5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal37e12294b28f0bd91a733acab9bb06c5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.date-picker','data' => ['wire:model' => 'data','name' => 'data','id' => 'data','value' => ''.e($data).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('date-picker'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['wire:model' => 'data','name' => 'data','id' => 'data','value' => ''.e($data).'']); ?>
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
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['data'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="categoria_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Categoria</label>
                <select wire:model="categoria_id" id="categoria_id" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione...</option>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categoriasFiltradas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($categoria->id); ?>"><?php echo e($categoria->nome); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['categoria_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
            <div>
                <label for="conta" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Conta / Cartão</label>
                <select wire:model="conta" id="conta" required class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                    <option value="">Selecione...</option>
                    <optgroup label="Contas Bancárias">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $bancos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banco): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="Banco_<?php echo e($banco->id); ?>"><?php echo e($banco->nome); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </optgroup>
                    <optgroup label="Cartões de Crédito">
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $cartoes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartao): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="CartaoCredito_<?php echo e($cartao->id); ?>"><?php echo e($cartao->nome); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                    </optgroup>
                </select>
                <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['conta'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
            </div>
        </div>
        
        <div class="pt-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Frequência</label>
            <div class="flex flex-wrap gap-4">
                <div>
                    <input type="radio" id="tipoUnico" value="unico" wire:model.live="launchType" class="hidden peer">
                    <label for="tipoUnico" class="inline-flex items-center justify-between w-full p-3 rounded-lg cursor-pointer transition-colors text-gray-500 bg-white border border-gray-200 peer-checked:bg-blue-600 peer-checked:text-white hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700 dark:peer-checked:bg-blue-500">
                        <div class="block"><div class="w-full text-sm font-semibold">Único</div></div>
                    </label>
                </div>
                <div>
                    <input type="radio" id="tipoParcelado" value="parcelado" wire:model.live="launchType" class="hidden peer">
                    <label for="tipoParcelado" class="inline-flex items-center justify-between w-full p-3 rounded-lg cursor-pointer transition-colors text-gray-500 bg-white border border-gray-200 peer-checked:bg-green-600 peer-checked:text-white hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700 dark:peer-checked:bg-green-500">
                        <div class="block"><div class="w-full text-sm font-semibold">Parcelado</div></div>
                    </label>
                </div>
                <div>
                    <input type="radio" id="tipoRecorrente" value="recorrente" wire:model.live="launchType" class="hidden peer">
                    <label for="tipoRecorrente" class="inline-flex items-center justify-between w-full p-3 rounded-lg cursor-pointer transition-colors text-gray-500 bg-white border border-gray-200 peer-checked:bg-purple-600 peer-checked:text-white hover:bg-gray-100 dark:bg-gray-900 dark:text-gray-400 dark:border-gray-700 dark:hover:bg-gray-700 dark:peer-checked:bg-purple-500">
                        <div class="block"><div class="w-full text-sm font-semibold">Recorrente</div></div>
                    </label>
                </div>
            </div>
        </div>

        <!--[if BLOCK]><![endif]--><?php if($launchType === 'parcelado'): ?>
        <div class="pt-2">
            <label for="parcela_total" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Número de Parcelas</label>
            <input type="number" wire:model="parcela_total" id="parcela_total" min="2" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['parcela_total'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="mt-2 text-sm text-red-600 dark:text-red-500"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        
        <!--[if BLOCK]><![endif]--><?php if($launchType === 'recorrente'): ?>
        <div class="pt-2">
            <label for="frequency" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Repetir a cada:</label>
            <select wire:model="frequency" id="frequency" class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500">
                <option value="monthly">Mês</option>
                <option value="yearly">Ano</option>
            </select>
        </div>
        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
    </div>

    <div class="flex items-center justify-end mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-x-4">
        <a href="<?php echo e(route('transacoes.index')); ?>" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Cancelar
        </a>
        <button type="submit" 
                class="font-medium rounded-lg text-sm px-5 py-2.5 text-white transition-colors"
                :class="{
                    'bg-green-600 hover:bg-green-800 focus:ring-4 focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800': tipo === 'receita',
                    'bg-red-600 hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:bg-red-500 dark:hover:bg-red-700 dark:focus:ring-red-800': tipo === 'despesa'
                }">
            Salvar Lançamento
        </button>
    </div>
</form>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/livewire/transacao-form.blade.php ENDPATH**/ ?>