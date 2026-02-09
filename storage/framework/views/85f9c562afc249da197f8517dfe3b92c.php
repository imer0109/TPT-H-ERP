

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb & title -->
    <div class="mb-6">
        <nav class="text-gray-500 text-sm mb-2" aria-label="breadcrumb">
            <ol class="list-reset flex">
                <li><a href="<?php echo e(route('dashboard')); ?>" class="hover:text-gray-700">Tableau de bord</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="<?php echo e(route('hr.evaluations.index')); ?>" class="hover:text-gray-700">Évaluations</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-700 font-semibold">Nouvelle Évaluation</li>
            </ol>
        </nav>
        <h1 class="text-2xl font-bold text-gray-800">Créer une Évaluation</h1>
    </div>

    <form action="<?php echo e(route('hr.evaluations.store')); ?>" method="POST" class="bg-white shadow rounded-lg p-6 space-y-6" id="evaluationForm">
        <?php echo csrf_field(); ?>

        <!-- Informations principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <!-- Employé -->
                <div>
                    <label for="employee_id" class="block text-gray-700 font-medium">Employé <span class="text-red-500">*</span></label>
                    <select name="employee_id" id="employee_id" required
                        class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 placeholder-gray-400 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="">Sélectionner un employé</option>
                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->full_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-red-500"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Période d'évaluation -->
                <div>
                    <label for="evaluation_period" class="block text-gray-700 font-medium">Période d'Évaluation <span class="text-red-500">*</span></label>
                    <select name="evaluation_period" id="evaluation_period" required
                        class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="">Sélectionner la période</option>
                        <option value="Q1_<?php echo e(date('Y')); ?>">T1 <?php echo e(date('Y')); ?></option>
                        <option value="Q2_<?php echo e(date('Y')); ?>">T2 <?php echo e(date('Y')); ?></option>
                        <option value="Q3_<?php echo e(date('Y')); ?>">T3 <?php echo e(date('Y')); ?></option>
                        <option value="Q4_<?php echo e(date('Y')); ?>">T4 <?php echo e(date('Y')); ?></option>
                        <option value="annual_<?php echo e(date('Y')); ?>">Annuelle <?php echo e(date('Y')); ?></option>
                    </select>
                    <?php $__errorArgs = ['evaluation_period'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-red-500"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Type d'évaluation -->
                <div>
                    <label for="evaluation_type" class="block text-gray-700 font-medium">Type d'Évaluation <span class="text-red-500">*</span></label>
                    <select name="evaluation_type" id="evaluation_type" required
                        class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="">Sélectionner le type</option>
                        <option value="performance">Performance</option>
                        <option value="competency">Compétences</option>
                        <option value="360_feedback">Feedback 360°</option>
                        <option value="probation">Période d'essai</option>
                        <option value="annual_review">Revue annuelle</option>
                    </select>
                    <?php $__errorArgs = ['evaluation_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-red-500"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Évaluateur -->
                <div>
                    <label for="evaluator_id" class="block text-gray-700 font-medium">Évaluateur <span class="text-red-500">*</span></label>
                    <select name="evaluator_id" id="evaluator_id" required
                        class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="">Sélectionner un évaluateur</option>
                        <?php $__currentLoopData = $evaluators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($evaluator->id); ?>"><?php echo e($evaluator->full_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['evaluator_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-red-500"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Date d'échéance -->
                <div>
                    <label for="due_date" class="block text-gray-700 font-medium">Date d'Échéance</label>
                    <input type="date" name="due_date" id="due_date" value="<?php echo e(old('due_date', date('Y-m-d', strtotime('+2 weeks')))); ?>"
                        class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none">
                    <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-red-500"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Note globale -->
                <div>
                    <label for="overall_rating" class="block text-gray-700 font-medium">Note Globale (1-5)</label>
                    <select name="overall_rating" id="overall_rating"
                        class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none">
                        <option value="">À déterminer</option>
                        <option value="1">1 - Insatisfaisant</option>
                        <option value="2">2 - Peu satisfaisant</option>
                        <option value="3">3 - Satisfaisant</option>
                        <option value="4">4 - Très satisfaisant</option>
                        <option value="5">5 - Exceptionnel</option>
                    </select>
                    <?php $__errorArgs = ['overall_rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-red-500"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>
        </div>

        <!-- Objectifs et Performance -->
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Objectifs et Performance</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <textarea name="objectives" rows="3" placeholder="Objectifs de la période"
                    class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none"><?php echo e(old('objectives')); ?></textarea>
                <textarea name="achievements" rows="3" placeholder="Réalisations"
                    class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none"><?php echo e(old('achievements')); ?></textarea>
                <textarea name="areas_improvement" rows="3" placeholder="Axes d'amélioration"
                    class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none"><?php echo e(old('areas_improvement')); ?></textarea>
            </div>
        </div>

        <!-- Compétences évaluées -->
        <div>
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Compétences Évaluées</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="space-y-4">
                    <?php $__currentLoopData = array_slice($criteria, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label for="<?php echo e($key); ?>" class="block text-gray-700 font-medium"><?php echo e($criterion['name']); ?> (1-5)</label>
                        <select name="<?php echo e($key); ?>" id="<?php echo e($key); ?>"
                            class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none">
                            <option value="">Non évalué</option>
                            <?php for($i=1;$i<=5;$i++): ?>
                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                            <?php endfor; ?>
                        </select>
                        <small class="text-gray-500 text-sm"><?php echo e($criterion['description']); ?></small>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <div class="space-y-4">
                    <?php $__currentLoopData = array_slice($criteria, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <label for="<?php echo e($key); ?>" class="block text-gray-700 font-medium"><?php echo e($criterion['name']); ?> (1-5)</label>
                        <select name="<?php echo e($key); ?>" id="<?php echo e($key); ?>"
                            class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none">
                            <option value="">Non évalué</option>
                            <?php for($i=1;$i<=5;$i++): ?>
                                <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                            <?php endfor; ?>
                        </select>
                        <small class="text-gray-500 text-sm"><?php echo e($criterion['description']); ?></small>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Commentaires -->
        <div>
            <label for="comments" class="block text-gray-700 font-medium">Commentaires Généraux</label>
            <textarea name="comments" rows="3" placeholder="Commentaires additionnels, recommandations, plan de développement..."
                class="mt-1 block w-full rounded border border-gray-300 bg-white px-3 py-2 text-gray-700 focus:border-blue-500 focus:ring focus:ring-blue-200 focus:outline-none"><?php echo e(old('comments')); ?></textarea>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-3 mt-6">
            <a href="<?php echo e(route('hr.evaluations.index')); ?>" class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">Annuler</a>
            <button type="submit" name="action" value="draft" class="px-4 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">Enregistrer comme Brouillon</button>
            <button type="submit" name="action" value="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Soumettre l'Évaluation</button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\evaluations\create.blade.php ENDPATH**/ ?>