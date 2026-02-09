<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'label' => null,
    'checked' => false,
    'value' => 1,
    'required' => false,
    'error' => $errors->has($name) ? $errors->first($name) : null
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
    'name',
    'label' => null,
    'checked' => false,
    'value' => 1,
    'required' => false,
    'error' => $errors->has($name) ? $errors->first($name) : null
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="mb-4">
    <label class="inline-flex items-center">
        <input 
            type="checkbox"
            name="<?php echo e($name); ?>"
            value="<?php echo e($value); ?>"
            <?php echo e($checked || old($name) ? 'checked' : ''); ?>

            <?php echo e($required ? 'required' : ''); ?>

            <?php echo e($attributes->merge(['class' => 'rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50'])); ?>

        >
        <?php if($label): ?>
            <span class="ml-2 text-sm text-gray-700"><?php echo e($label); ?></span>
        <?php endif; ?>
    </label>
    
    <?php if($error): ?>
        <p class="mt-1 text-sm text-red-600"><?php echo e($error); ?></p>
    <?php endif; ?>
</div><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\components\form\checkbox.blade.php ENDPATH**/ ?>