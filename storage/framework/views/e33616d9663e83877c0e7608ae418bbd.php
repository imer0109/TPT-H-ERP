<?php $__env->startSection('title', 'Réclamations Clients'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Gestion des Réclamations Clients</h4>
                    <a href="<?php echo e(route('clients.reclamations.create')); ?>" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Nouvelle Réclamation
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="reclamations-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Statut</th>
                                    <th>Agent</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $reclamations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reclamation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($reclamation->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('clients.show', $reclamation->client)); ?>">
                                            <?php echo e($reclamation->client->nom_raison_sociale); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-<?php echo e($reclamation->type_reclamation == 'produit_defectueux' ? 'danger' : 
                                                ($reclamation->type_reclamation == 'retard_livraison' ? 'warning' : 
                                                ($reclamation->type_reclamation == 'erreur_facturation' ? 'info' : 'secondary'))); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $reclamation->type_reclamation))); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e(Str::limit($reclamation->description, 50)); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo e($reclamation->statut == 'ouverte' ? 'warning' : 
                                                ($reclamation->statut == 'en_cours' ? 'info' : 'success')); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $reclamation->statut))); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($reclamation->agent ? $reclamation->agent->nom . ' ' . $reclamation->agent->prenom : 'Non assigné'); ?></td>
                                    <td><?php echo e($reclamation->created_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('clients.reclamations.show', $reclamation)); ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('clients.reclamations.edit', $reclamation)); ?>" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('clients.reclamations.destroy', $reclamation)); ?>" method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php echo e($reclamations->links()); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    $('#reclamations-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        "pageLength": 25,
        "order": [[0, "desc"]]
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\reclamations\index.blade.php ENDPATH**/ ?>