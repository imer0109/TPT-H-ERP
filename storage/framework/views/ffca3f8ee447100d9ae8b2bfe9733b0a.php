

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-10 px-6">
    <div class="max-w-7xl mx-auto bg-white shadow-xl rounded-2xl p-8">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-2xl font-bold text-indigo-600">Bilan au <?php echo e(\Carbon\Carbon::parse($date)->format('d/m/Y')); ?></h3>
            <a href="<?php echo e(route('accounting.export.excel', request()->all())); ?>" 
               class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center">
               <i class="fas fa-file-excel mr-1"></i> Export Excel
            </a>
        </div>

        <!-- Filters -->
        <form method="GET" class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 font-medium mb-2">Date</label>
                <input type="date" name="date" value="<?php echo e(request('date', $date)); ?>"
                       class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-search mr-2"></i> Filtrer
                </button>
                <a href="<?php echo e(route('accounting.reports.balance-sheet')); ?>" 
                   class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2.5 rounded-lg font-medium transition flex items-center">
                    <i class="fas fa-times mr-2"></i> Réinitialiser
                </a>
            </div>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Assets -->
            <div class="bg-blue-50 rounded-xl shadow-md overflow-hidden">
                <div class="bg-blue-500 text-white px-4 py-2 font-semibold">ACTIF</div>
                <div class="p-4">
                    <?php $totalAssets = 0; ?>
                    <?php $__currentLoopData = $assetAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($class->children->count() > 0): ?>
                            <h6 class="font-medium mt-3"><?php echo e($class->code); ?> - <?php echo e($class->name); ?></h6>
                            <div class="overflow-x-auto mt-2">
                                <table class="min-w-full text-sm text-left border border-gray-200">
                                    <tbody class="divide-y divide-blue-100">
                                        <?php $__currentLoopData = $class->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($group->children->count() > 0): ?>
                                                <?php $__currentLoopData = $group->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($account->children->count() > 0): ?>
                                                        <?php $__currentLoopData = $account->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $balance = $subAccount->entries->sum(function($entry) use ($subAccount) {
                                                                    return $entry->account_id == $subAccount->id ? $entry->debit - $entry->credit : 0;
                                                                });
                                                                $totalAssets += $balance;
                                                            ?>
                                                            <?php if($balance != 0): ?>
                                                                <tr>
                                                                    <td class="px-4 py-2"><?php echo e($subAccount->code); ?></td>
                                                                    <td class="px-4 py-2"><?php echo e($subAccount->name); ?></td>
                                                                    <td class="px-4 py-2 text-right"><?php echo e(number_format($balance, 2)); ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="mt-3 bg-blue-100 text-blue-800 font-semibold p-3 rounded-lg">
                        TOTAL ACTIF: <?php echo e(number_format($totalAssets, 2)); ?>

                    </div>
                </div>
            </div>

            <!-- Liabilities -->
            <div class="bg-purple-50 rounded-xl shadow-md overflow-hidden">
                <div class="bg-purple-500 text-white px-4 py-2 font-semibold">PASSIF</div>
                <div class="p-4">
                    <?php $totalLiabilities = 0; ?>
                    <?php $__currentLoopData = $liabilityAccounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($class->children->count() > 0): ?>
                            <h6 class="font-medium mt-3"><?php echo e($class->code); ?> - <?php echo e($class->name); ?></h6>
                            <div class="overflow-x-auto mt-2">
                                <table class="min-w-full text-sm text-left border border-gray-200">
                                    <tbody class="divide-y divide-purple-100">
                                        <?php $__currentLoopData = $class->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($group->children->count() > 0): ?>
                                                <?php $__currentLoopData = $group->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if($account->children->count() > 0): ?>
                                                        <?php $__currentLoopData = $account->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subAccount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php
                                                                $balance = $subAccount->entries->sum(function($entry) use ($subAccount) {
                                                                    return $entry->account_id == $subAccount->id ? $entry->credit - $entry->debit : 0;
                                                                });
                                                                $totalLiabilities += $balance;
                                                            ?>
                                                            <?php if($balance != 0): ?>
                                                                <tr>
                                                                    <td class="px-4 py-2"><?php echo e($subAccount->code); ?></td>
                                                                    <td class="px-4 py-2"><?php echo e($subAccount->name); ?></td>
                                                                    <td class="px-4 py-2 text-right"><?php echo e(number_format($balance, 2)); ?></td>
                                                                </tr>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <div class="mt-3 bg-purple-100 text-purple-800 font-semibold p-3 rounded-lg">
                        TOTAL PASSIF: <?php echo e(number_format($totalLiabilities, 2)); ?>

                    </div>
                </div>
            </div>
        </div>

        <!-- Balance Check -->
        <div class="mt-8">
            <div class="<?php echo e(abs($totalAssets - $totalLiabilities) < 0.01 ? 'bg-green-500' : 'bg-red-500'); ?> text-white rounded-xl p-5 text-center shadow-md">
                <h4 class="font-bold">
                    ACTIF: <?php echo e(number_format($totalAssets, 2)); ?> | 
                    PASSIF: <?php echo e(number_format($totalLiabilities, 2)); ?> | 
                    ÉCART: <?php echo e(number_format($totalAssets - $totalLiabilities, 2)); ?>

                </h4>
                <p class="mt-2">
                    <?php if(abs($totalAssets - $totalLiabilities) < 0.01): ?>
                        <i class="fas fa-check-circle mr-1"></i> Le bilan est équilibré
                    <?php else: ?>
                        <i class="fas fa-exclamation-triangle mr-1"></i> Le bilan n'est pas équilibré
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\accounting\reports\balance-sheet.blade.php ENDPATH**/ ?>