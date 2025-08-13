<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['disabled' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['disabled' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>


<div
    wire:ignore
    x-data
    x-init="
        let mask = IMask($refs.input, {
            mask: '(00) 00000-0000'
        });

        // Usa Livewire.entangle para obter uma propriedade reativa
        let value = Livewire.entangle($wire, '<?php echo e($attributes->get('wire:model')); ?>');

        // Define o valor inicial na máscara a partir do Livewire
        mask.value = value.get() || '';

        // Quando o usuário digita, atualiza a propriedade no Livewire
        mask.on('accept', () => {
            value.set(mask.value);
        });

        // Quando a propriedade no Livewire muda, atualiza a máscara
        $watch(() => value.get(), (newValue) => {
            if (mask.value != newValue) {
                mask.value = newValue || '';
            }
        });
    "
>
    <input 
        x-ref="input"
        type="text"
        placeholder="(99) 99999-9999"
        <?php echo e($disabled ? 'disabled' : ''); ?> 
        <?php echo $attributes->except('wire:model')->merge(['class' => 'block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500']); ?>

    >
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/components/phone-input.blade.php ENDPATH**/ ?>