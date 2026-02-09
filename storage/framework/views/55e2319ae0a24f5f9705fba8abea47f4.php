

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Liste des Contrats</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('contracts.create')); ?>" class="btn btn-primary">Nouveau Contrat</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form action="<?php echo e(route('contracts.index')); ?>" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="employee_id">Employé</label>
                                    <select name="employee_id" id="employee_id" class="form-control">
                                        <option value="">Tous les employés</option>
                                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($employee->id); ?>" <?php echo e(request('employee_id') == $employee->id ? 'selected' : ''); ?>>
                                                <?php echo e($employee->full_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="">Tous les statuts</option>
                                        <option value="draft" <?php echo e(request('status') == 'draft' ? 'selected' : ''); ?>>Brouillon</option>
                                        <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>En attente</option>
                                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Actif</option>
                                        <option value="terminated" <?php echo e(request('status') == 'terminated' ? 'selected' : ''); ?>>Résilié</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="type">Type de Contrat</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Tous les types</option>
                                        <option value="CDI" <?php echo e(request('type') == 'CDI' ? 'selected' : ''); ?>>CDI</option>
                                        <option value="CDD" <?php echo e(request('type') == 'CDD' ? 'selected' : ''); ?>>CDD</option>
                                        <option value="Stage" <?php echo e(request('type') == 'Stage' ? 'selected' : ''); ?>>Stage</option>
                                        <option value="Prestation" <?php echo e(request('type') == 'Prestation' ? 'selected' : ''); ?>>Prestation</option>
                                        <option value="Intérim" <?php echo e(request('type') == 'Intérim' ? 'selected' : ''); ?>>Intérim</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Filtrer</button>
                                <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-secondary">Réinitialiser</a>
                            </div>
                        </div>
                    </form>
                    
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Employé</th>
                                <th>Type de Contrat</th>
                                <th>Date de Début</th>
                                <th>Date de Fin</th>
                                <th>Salaire de Base</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $contracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($contract->employee->full_name); ?></td>
                                <td><?php echo e($contract->type); ?></td>
                                <td><?php echo e($contract->start_date->format('d/m/Y')); ?></td>
                                <td><?php echo e($contract->end_date ? $contract->end_date->format('d/m/Y') : 'Indéterminé'); ?></td>
                                <td><?php echo e(number_format($contract->base_salary, 0, ',', ' ')); ?> FCFA</td>
                                <td>
                                    <span class="badge <?php echo e($contract->getStatusBadgeClass()); ?>">
                                        <?php echo e($contract->getStatusText()); ?>

                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="btn btn-sm btn-info">Voir</a>
                                        <a href="<?php echo e(route('contracts.edit', $contract)); ?>" class="btn btn-sm btn-warning">Modifier</a>
                                        <form action="<?php echo e(route('contracts.destroy', $contract)); ?>" method="POST" class="d-inline" id="delete-form-<?php echo e($contract->id); ?>">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('<?php echo e($contract->id); ?>')">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">Aucun contrat trouvé</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?php echo e($contracts->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function confirmDelete(contractId) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')) {
        document.getElementById('delete-form-' + contractId).submit();
    }
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\contracts\index.blade.php ENDPATH**/ ?>