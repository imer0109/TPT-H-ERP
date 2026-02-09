

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <!-- Breadcrumb -->
    <div class="mb-4 hidden print:block">
        <ol class="list-reset flex text-gray-500 text-sm">
            <li><a href="<?php echo e(route('dashboard')); ?>" class="text-blue-600">Tableau de bord</a></li>
            <li class="mx-2">/</li>
            <li><a href="<?php echo e(route('hr.evaluations.index')); ?>" class="text-blue-600">Évaluations</a></li>
            <li class="mx-2">/</li>
            <li>Évaluation</li>
        </ol>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-2 mb-6 print:hidden">
        <a href="<?php echo e(route('hr.evaluations.index')); ?>" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 flex items-center gap-2">
            <i class="mdi mdi-arrow-left"></i> Retour
        </a>
        <a href="#" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-2">
            <i class="mdi mdi-download"></i> Télécharger PDF
        </a>
        <?php if($evaluation->isDraft()): ?>
        <a href="<?php echo e(route('hr.evaluations.edit', $evaluation)); ?>" class="px-4 py-2 bg-yellow-400 text-white rounded hover:bg-yellow-500 flex items-center gap-2">
            <i class="mdi mdi-pencil"></i> Modifier
        </a>
        <?php endif; ?>
    </div>

    <!-- Evaluation Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-700"><?php echo e(config('app.name', 'TPT-H ERP')); ?></h2>
            <p class="text-gray-500 text-sm mt-1">FICHE D'ÉVALUATION DE PERFORMANCE<br>
                Évaluation des compétences et performance</p>
        </div>
        <div class="text-right mt-4 md:mt-0">
            <h3 class="text-lg font-semibold">ÉVALUATION</h3>
            <p class="text-sm mb-1"><strong>Référence:</strong> #EVA<?php echo e(str_pad($evaluation->id, 3, '0', STR_PAD_LEFT)); ?></p>
            <p class="text-sm mb-1"><strong>Date de création:</strong> <?php echo e($evaluation->created_at->format('d/m/Y')); ?></p>
            <p class="text-sm"><strong>Statut:</strong> 
                <span class="px-2 py-1 text-white bg-<?php echo e($evaluation->status_color); ?>-500 rounded-full text-xs"><?php echo e($evaluation->status_text); ?></span>
            </p>
        </div>
    </div>

    <!-- Employee & Evaluation Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-50 p-4 rounded shadow-sm">
            <h4 class="font-semibold mb-2">Informations Employé</h4>
            <div class="text-sm text-gray-700 space-y-1">
                <p><strong>Nom complet:</strong> <?php echo e($evaluation->employee->full_name); ?></p>
                <p><strong>Poste:</strong> <?php echo e($evaluation->employee->currentPosition->title ?? 'N/A'); ?></p>
                <p><strong>Département:</strong> <?php echo e($evaluation->employee->currentCompany->raison_sociale ?? 'N/A'); ?></p>
                <p><strong>Date d'embauche:</strong> <?php echo e($evaluation->employee->date_embauche ? $evaluation->employee->date_embauche->format('d/m/Y') : 'N/A'); ?></p>
            </div>
        </div>
        <div class="bg-gray-50 p-4 rounded shadow-sm">
            <h4 class="font-semibold mb-2">Informations Évaluation</h4>
            <div class="text-sm text-gray-700 space-y-1">
                <p><strong>Période:</strong> <?php echo e($evaluation->period); ?></p>
                <p><strong>Type:</strong> <?php echo e($evaluation->evaluation_type_text); ?></p>
                <p><strong>Évaluateur:</strong> <?php echo e($evaluation->evaluator->full_name); ?></p>
                <p><strong>Date d'échéance:</strong> <?php echo e($evaluation->due_date ? $evaluation->due_date->format('d/m/Y') : 'N/A'); ?></p>
            </div>
        </div>
    </div>

    <!-- Overall Rating -->
    <div class="bg-blue-100 text-center p-6 rounded mb-6">
        <h4 class="font-semibold text-blue-700 mb-2">NOTE GLOBALE</h4>
        <?php if($evaluation->overall_score): ?>
        <p class="text-3xl font-bold text-blue-800"><?php echo e($evaluation->overall_score); ?>/5 - <?php echo e($evaluation->overall_rating_text); ?></p>
        <?php else: ?>
        <p class="text-3xl font-bold text-blue-800">Non évalué</p>
        <?php endif; ?>
    </div>

    <!-- Objectives & Performance -->
    <div class="mb-6">
        <h4 class="font-semibold mb-3">Objectifs et Performance</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="font-medium mb-2">Objectifs de la Période</h5>
                <p class="text-gray-600 text-sm"><?php echo e($evaluation->objectives ?? 'Aucun objectif défini'); ?></p>
            </div>
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="font-medium mb-2">Réalisations</h5>
                <p class="text-gray-600 text-sm"><?php echo e($evaluation->achievements ?? 'Aucune réalisation enregistrée'); ?></p>
            </div>
            <div class="bg-white p-4 rounded shadow-sm">
                <h5 class="font-medium mb-2">Axes d'Amélioration</h5>
                <p class="text-gray-600 text-sm"><?php echo e($evaluation->areas_improvement ?? 'Aucun axe d\'amélioration identifié'); ?></p>
            </div>
        </div>
    </div>

    <!-- Skills Assessment -->
    <div class="mb-6">
        <h4 class="font-semibold mb-3">Évaluation des Compétences</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border rounded shadow-sm text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="py-2 px-4 text-left">Compétence</th>
                        <th class="py-2 px-4 text-center">Note</th>
                        <th class="py-2 px-4 text-center">Appréciation</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    <?php $__currentLoopData = $criteria; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="py-2 px-4 font-medium"><?php echo e($criterion['name']); ?></td>
                        <?php
                            $score = $evaluation->getCriteriaScore($key);
                        ?>
                        <td class="py-2 px-4 text-center">
                            <?php if($score): ?>
                            <span class="inline-block px-2 py-1 text-white 
                                <?php if($score >= 5): ?> bg-green-500
                                <?php elseif($score >= 4): ?> bg-blue-500
                                <?php elseif($score >= 3): ?> bg-yellow-500
                                <?php elseif($score >= 2): ?> bg-orange-500
                                <?php else: ?> bg-red-500 <?php endif; ?>
                                rounded-full text-xs">
                                <?php echo e($score); ?>/5
                            </span>
                            <?php else: ?>
                            <span class="text-gray-500">Non évalué</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-2 px-4 text-center">
                            <?php if($score): ?>
                                <?php if($score >= 5): ?> Exceptionnel
                                <?php elseif($score >= 4): ?> Très satisfaisant
                                <?php elseif($score >= 3): ?> Satisfaisant
                                <?php elseif($score >= 2): ?> Peu satisfaisant
                                <?php else: ?> Insatisfaisant <?php endif; ?>
                            <?php else: ?>
                            Non évalué
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Comments -->
    <div class="mb-6">
        <h5 class="font-semibold mb-2">Commentaires Généraux</h5>
        <div class="bg-gray-50 p-4 rounded shadow-sm text-gray-700 text-sm">
            <?php echo e($evaluation->recommendations ?? 'Aucun commentaire'); ?>

        </div>
    </div>

    <!-- Signatures -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 print:mt-12">
        <div class="flex flex-col items-start">
            <p class="mb-2">Signature de l'Employé:</p>
            <div class="border-b border-black w-48 h-12 mb-1"></div>
            <small><?php echo e($evaluation->employee->full_name); ?></small><br>
            <small>Date: ___________</small>
        </div>
        <div class="flex flex-col items-start md:items-end">
            <p class="mb-2">Signature de l'Évaluateur:</p>
            <div class="border-b border-black w-48 h-12 mb-1"></div>
            <small><?php echo e($evaluation->evaluator->full_name); ?></small><br>
            <small>Date: ___________</small>
        </div>
    </div>
</div>

<style>
@media print {
    .print\:block { display: block !important; }
    .print\:hidden { display: none !important; }
}
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\evaluations\show.blade.php ENDPATH**/ ?>