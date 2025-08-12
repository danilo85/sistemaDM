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
    x-data="{
        value: <?php if ((object) ($attributes->wire('model')) instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($attributes->wire('model')->value()); ?>')<?php echo e($attributes->wire('model')->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e($attributes->wire('model')); ?>')<?php endif; ?>, // Sincroniza a propriedade 'value' com o wire:model
        imask: null,
        init() {
            // 1. Inicializa a máscara no input visível
            this.imask = IMask(this.$refs.maskedInput, {
                mask: 'R$ num',
                blocks: {
                    num: {
                        mask: Number,
                        scale: 2,
                        thousandsSeparator: '.',
                        padFractionalZeros: true,
                        radix: ',',
                        mapToRadix: ['.'],
                        lazy: false,
                    }
                }
            });

            // 2. Define o valor inicial da máscara com base no valor do Livewire
            this.$nextTick(() => {
                this.imask.unmaskedValue = this.value || '';
            });

            // 3. Quando o usuário digita, atualiza a propriedade 'value' (que está ligada ao Livewire)
            this.imask.on('accept', () => {
                this.value = this.imask.unmaskedValue;
            });

            // 4. Observa por mudanças vindas do Livewire (ex: 'Quitar Orçamento' ou 'Editar')
            //    e atualiza o valor da máscara que o usuário vê.
            this.$watch('value', (newValue) => {
                if (this.imask.unmaskedValue !== newValue) {
                    this.imask.unmaskedValue = newValue || '';
                }
            });
        }
    }"
>
    
    <input 
        x-ref="maskedInput"
        type="text"
        <?php echo e($disabled ? 'disabled' : ''); ?> 
        
        class="block w-full p-2.5 rounded-lg border-transparent bg-gray-100 dark:bg-gray-900 focus:ring-2 focus:ring-blue-500"
    >
</div>
<?php /**PATH C:\laragon\www\sistemaDM\resources\views/components/money-input.blade.php ENDPATH**/ ?>