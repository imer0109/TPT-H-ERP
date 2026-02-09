<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name',
    'value' => old($name),
    'label' => null,
    'required' => false,
    'options' => [],
    'placeholder' => 'Sélectionnez une option',
    'readonly' => false,
    'disabled' => false,
    'help' => null,
    'errorKey' => $name,
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
    'value' => old($name),
    'label' => null,
    'required' => false,
    'options' => [],
    'placeholder' => 'Sélectionnez une option',
    'readonly' => false,
    'disabled' => false,
    'help' => null,
    'errorKey' => $name,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<div class="mb-4">
    <?php if($label): ?>
        <label for="<?php echo e($name); ?>" class="block text-sm font-medium text-gray-700 mb-1">
            <?php echo e($label); ?>

            <?php if($required): ?>
                <span class="text-red-500">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <select 
        name="<?php echo e($name); ?>"
        id="<?php echo e($name); ?>"
        <?php if($required): ?> required <?php endif; ?>
        <?php if($readonly): ?> readonly <?php endif; ?>
        <?php if($disabled): ?> disabled <?php endif; ?>
        <?php echo e($attributes->merge(['class' => 'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500'])); ?>

    >
        <?php if($placeholder): ?>
            <option value=""><?php echo e($placeholder); ?></option>
        <?php endif; ?>
        <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $optionLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($key); ?>" <?php echo e((string)$key === (string)$value ? 'selected' : ''); ?>>
                <?php echo e($optionLabel); ?>

            </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    
    <?php if($errors->has($errorKey)): ?>
        <p class="mt-1 text-sm text-red-600"><?php echo e($errors->first($errorKey)); ?></p>
    <?php endif; ?>
    
    <?php if($help): ?>
        <p class="mt-1 text-sm text-gray-500"><?php echo e($help); ?></p>
    <?php endif; ?>
</div><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/fournisseurs/portal/partials/select.blade.php ENDPATH**/ ?>