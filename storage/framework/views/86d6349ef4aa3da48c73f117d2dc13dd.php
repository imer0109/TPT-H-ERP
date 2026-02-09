

<?php $__env->startSection('title', 'Détails de l\'Interaction'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Détails de l'Interaction #<?php echo e($interaction->id); ?></h4>
                    <div class="card-tools">
                        <a href="<?php echo e(route('clients.interactions.index')); ?>" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Client:</label>
                                <p><a href="<?php echo e(route('clients.show', $interaction->client)); ?>"><?php echo e($interaction->client->nom_raison_sociale); ?></a></p>
                            </div>
                            
                            <div class="form-group">
                                <label>Type d'Interaction:</label>
                                <p>
                                    <span class="badge badge-<?php echo e($interaction->type_interaction == 'appel_telephonique' ? 'primary' : 
                                            ($interaction->type_interaction == 'visite_commerciale' ? 'info' : 
                                            ($interaction->type_interaction == 'email' ? 'success' : 'warning'))); ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $interaction->type_interaction))); ?>

                                    </span>
                                </p>
                            </div>
                            
                            <div class="form-group">
                                <label>Utilisateur:</label>
                                <p><?php echo e($interaction->user ? $interaction->user->nom . ' ' . $interaction->user->prenom : 'N/A'); ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label>Date de l'Interaction:</label>
                                <p><?php echo e($interaction->date_interaction->format('d/m/Y')); ?></p>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Suivi Nécessaire:</label>
                                <p>
                                    <?php if($interaction->suivi_necessaire): ?>
                                        <span class="badge badge-danger">Oui</span>
                                    <?php else: ?>
                                        <span class="badge badge-success">Non</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            
                            <?php if($interaction->suivi_necessaire && $interaction->date_suivi): ?>
                            <div class="form-group">
                                <label>Date de Suivi:</label>
                                <p><?php echo e($interaction->date_suivi->format('d/m/Y')); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if($interaction->campagne_id): ?>
                            <div class="form-group">
                                <label>ID de Campagne:</label>
                                <p><?php echo e($interaction->campagne_id); ?></p>
                            </div>
                            <?php endif; ?>
                            
                            <div class="form-group">
                                <label>Date de Création:</label>
                                <p><?php echo e($interaction->created_at->format('d/m/Y H:i')); ?></p>
                            </div>
                            
                            <div class="form-group">
                                <label>Dernière Mise à Jour:</label>
                                <p><?php echo e($interaction->updated_at->format('d/m/Y H:i')); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Description:</label>
                        <p class="form-control-static"><?php echo e($interaction->description); ?></p>
                    </div>
                    
                    <?php if($interaction->resultat): ?>
                    <div class="form-group">
                        <label>Résultat:</label>
                        <p class="form-control-static"><?php echo e($interaction->resultat); ?></p>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Actions -->
                    <div class="form-group">
                        <a href="<?php echo e(route('clients.interactions.edit', $interaction)); ?>" class="btn btn-primary">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                        
                        <form action="<?php echo e(route('clients.interactions.destroy', $interaction)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette interaction ?')">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                        
                        <?php if($interaction->suivi_necessaire): ?>
                        <form action="<?php echo e(route('clients.interactions.mark-as-followed-up', $interaction)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-warning" title="Marquer comme suivi">
                                <i class="fas fa-check"></i> Marquer comme suivi
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\interactions\show.blade.php ENDPATH**/ ?>