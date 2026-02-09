

<?php $__env->startSection('title', 'Interactions Clients'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Gestion des Interactions Clients</h4>
                    <a href="<?php echo e(route('clients.interactions.create')); ?>" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Nouvelle Interaction
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="interactions-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Utilisateur</th>
                                    <th>Suivi Nécessaire</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $interactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($interaction->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('clients.show', $interaction->client)); ?>">
                                            <?php echo e($interaction->client->nom_raison_sociale); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo e($interaction->type_interaction == 'appel_telephonique' ? 'primary' : 
                                                ($interaction->type_interaction == 'visite_commerciale' ? 'info' : 
                                                ($interaction->type_interaction == 'email' ? 'success' : 'warning'))); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $interaction->type_interaction))); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e(Str::limit($interaction->description, 50)); ?></td>
                                    <td><?php echo e($interaction->date_interaction->format('d/m/Y')); ?></td>
                                    <td><?php echo e($interaction->user ? $interaction->user->nom . ' ' . $interaction->user->prenom : 'N/A'); ?></td>
                                    <td>
                                        <?php if($interaction->suivi_necessaire): ?>
                                            <span class="badge badge-danger">Oui</span>
                                            <?php if($interaction->date_suivi): ?>
                                                <small class="d-block">Date: <?php echo e($interaction->date_suivi->format('d/m/Y')); ?></small>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="badge badge-success">Non</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo e(route('clients.interactions.show', $interaction)); ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('clients.interactions.edit', $interaction)); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('clients.interactions.destroy', $interaction)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette interaction ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <?php if($interaction->suivi_necessaire): ?>
                                            <form action="<?php echo e(route('clients.interactions.mark-as-followed-up', $interaction)); ?>" method="POST" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-sm btn-warning" title="Marquer comme suivi">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo e($interactions->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    $('#interactions-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        "pageLength": 25,
        "order": [[0, "desc"]]
    });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\interactions\index.blade.php ENDPATH**/ ?>