<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['name', 'value' => '']));

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

foreach (array_filter((['name', 'value' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div
    wire:ignore
    x-data="{
        value: '<?php echo e($value); ?>',
        instance: null,
        init() {
            this.instance = flatpickr(this.$refs.input, {
                dateFormat: 'd/m/Y',
                defaultDate: this.value,
                onChange: (selectedDates, dateStr) => {
                    this.value = dateStr;
                    // Sincronizar com Livewire
                    if (this.$wire) {
                        this.$wire.set('<?php echo e($name); ?>', dateStr);
                    }
                }
            });
            
            // Observar mudanças do Livewire para atualizar o flatpickr
            this.$watch('$wire.<?php echo e($name); ?>', (newValue) => {
                if (newValue !== this.value && this.instance) {
                    this.value = newValue;
                    this.instance.setDate(newValue, false);
                }
            });
        }
    }"
>
    <input 
        <?php echo e($attributes->merge(['class' => 'block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500'])); ?>

        type="text"
        name="<?php echo e($name); ?>"
        x-ref="input"
        x-model="value"
    >
</div><?php /**PATH C:\laragon\www\sistemaDM\resources\views/components/date-picker.blade.php ENDPATH**/ ?>