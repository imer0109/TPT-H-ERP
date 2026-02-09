<?php
    function renderTreeNode($node, $level = 0) {
        $hasChildren = isset($node['children']) && count($node['children']) > 0;
        $indent = str_repeat('&nbsp;', $level * 4);
?>

<div class="tree-node" data-level="<?php echo e($level); ?>">
    <div class="tree-node-header">
        <?php if($hasChildren): ?>
            <div class="tree-node-toggle">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        <?php else: ?>
            <div class="tree-node-toggle" style="visibility: hidden;">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        <?php endif; ?>
        
        <div class="flex-1 flex items-center">
            <span class="font-mono text-sm"><?php echo e($node['code']); ?></span>
            <span class="ml-2 text-sm"><?php echo e($node['label']); ?></span>
            
            <?php if($node['is_auxiliary'] ?? false): ?>
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Auxiliaire
                </span>
            <?php endif; ?>
            
            <?php if(!($node['is_active'] ?? true)): ?>
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Inactif
                </span>
            <?php endif; ?>
            
            <div class="ml-auto flex space-x-2">
                <a href="<?php echo e(route('accounting.chart-of-accounts.show', $node['id'])); ?>" 
                   class="text-blue-600 hover:text-blue-900 text-xs" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="<?php echo e(route('accounting.chart-of-accounts.edit', $node['id'])); ?>" 
                   class="text-green-600 hover:text-green-900 text-xs" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="<?php echo e(route('accounting.chart-of-accounts.create', ['parent_id' => $node['id']])); ?>" 
                   class="text-purple-600 hover:text-purple-900 text-xs" title="Ajouter un sous-compte">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    
    <?php if($hasChildren): ?>
        <div class="tree-node-children">
            <?php $__currentLoopData = $node['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo renderTreeNode($child, $level + 1); ?>

            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<?php
    }
?>

<?php $__currentLoopData = $nodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $node): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo renderTreeNode($node, $level); ?>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\chart-of-accounts\partials\tree-node.blade.php ENDPATH**/ ?>