<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['status']));

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

foreach (array_filter((['status']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $colors = match (strtolower($status)) {
        'analisando' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'aprovado' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'finalizado' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'rejeitado' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'pago' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    };
?>

<span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full <?php echo e($colors); ?>">
    <?php echo e($status); ?>

</span><?php /**PATH C:\laragon\www\sistemaDM\resources\views/components/status-badge.blade.php ENDPATH**/ ?>