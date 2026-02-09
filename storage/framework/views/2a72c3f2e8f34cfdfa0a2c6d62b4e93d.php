<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, success
    'size' => 'md', // sm, md, lg
]));

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

foreach (array_filter(([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, success
    'size' => 'md', // sm, md, lg
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php if($href): ?>
    <a href="<?php echo e($href); ?>" 
       <?php echo e($attributes->merge(['class' => "inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"])); ?>

       class="
           <?php echo e($attributes->get('class')); ?>

           <?php if($variant === 'secondary'): ?> bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 <?php endif; ?>
           <?php if($variant === 'danger'): ?> bg-red-600 hover:bg-red-700 focus:ring-red-500 <?php endif; ?>
           <?php if($variant === 'success'): ?> bg-green-600 hover:bg-green-700 focus:ring-green-500 <?php endif; ?>
           <?php if($variant === 'primary'): ?> bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 <?php endif; ?>
           <?php if($size === 'sm'): ?> px-3 py-1.5 text-xs <?php endif; ?>
           <?php if($size === 'lg'): ?> px-6 py-3 text-base <?php endif; ?>
       ">
        <?php echo e($slot); ?>

    </a>
<?php else: ?>
    <button 
        type="<?php echo e($type); ?>"
        <?php echo e($attributes->merge(['class' => "inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"])); ?>

        class="
            <?php echo e($attributes->get('class')); ?>

            <?php if($variant === 'secondary'): ?> bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 <?php endif; ?>
            <?php if($variant === 'danger'): ?> bg-red-600 hover:bg-red-700 focus:ring-red-500 <?php endif; ?>
            <?php if($variant === 'success'): ?> bg-green-600 hover:bg-green-700 focus:ring-green-500 <?php endif; ?>
            <?php if($variant === 'primary'): ?> bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 <?php endif; ?>
            <?php if($size === 'sm'): ?> px-3 py-1.5 text-xs <?php endif; ?>
            <?php if($size === 'lg'): ?> px-6 py-3 text-base <?php endif; ?>
        ">
        <?php echo e($slot); ?>

    </button>
<?php endif; ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\components\button.blade.php ENDPATH**/ ?>