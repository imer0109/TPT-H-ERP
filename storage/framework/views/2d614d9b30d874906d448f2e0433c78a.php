

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du Contrat</h3>
                    <div class="card-tools">
                        <a href="<?php echo e(route('contracts.edit', $contract)); ?>" class="btn btn-warning">Modifier</a>
                        <a href="<?php echo e(route('contracts.index')); ?>" class="btn btn-secondary">Retour</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Employé</th>
                                    <td><?php echo e($contract->employee->full_name); ?></td>
                                </tr>
                                <tr>
                                    <th>Type de Contrat</th>
                                    <td><?php echo e($contract->type); ?></td>
                                </tr>
                                <tr>
                                    <th>Date de Début</th>
                                    <td><?php echo e($contract->start_date->format('d/m/Y')); ?></td>
                                </tr>
                                <tr>
                                    <th>Date de Fin</th>
                                    <td><?php echo e($contract->end_date ? $contract->end_date->format('d/m/Y') : 'Indéterminé'); ?></td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge <?php echo e($contract->getStatusBadgeClass()); ?>">
                                            <?php echo e($contract->getStatusText()); ?>

                                        </span>
                                    </td>
                                </tr>
                                <?php if($contract->trial_period_start && $contract->trial_period_end): ?>
                                <tr>
                                    <th>Période d'Essai</th>
                                    <td>
                                        Du <?php echo e($contract->trial_period_start->format('d/m/Y')); ?> 
                                        au <?php echo e($contract->trial_period_end->format('d/m/Y')); ?>

                                        (<?php echo e($contract->trial_period_start->diffInDays($contract->trial_period_end)); ?> jours)
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Salaire de Base</th>
                                    <td><?php echo e(number_format($contract->base_salary, 0, ',', ' ')); ?> FCFA</td>
                                </tr>
                                <tr>
                                    <th>Avantages</th>
                                    <td><?php echo e($contract->benefits ?: 'Aucun avantage spécifié'); ?></td>
                                </tr>
                                <tr>
                                    <th>Document du Contrat</th>
                                    <td>
                                        <?php if($contract->contract_file): ?>
                                            <a href="<?php echo e(Storage::url($contract->contract_file)); ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Télécharger
                                            </a>
                                        <?php else: ?>
                                            Aucun document
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fiche d'Embauche</th>
                                    <td>
                                        <?php if($contract->hiring_form): ?>
                                            <a href="<?php echo e(Storage::url($contract->hiring_form)); ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Télécharger
                                            </a>
                                        <?php else: ?>
                                            Aucune fiche
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <?php if($contract->status === 'terminated'): ?>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <h5><i class="fas fa-exclamation-triangle"></i> Contrat Résilié</h5>
                                <p><strong>Date de résiliation :</strong> <?php echo e($contract->terminated_at->format('d/m/Y')); ?></p>
                                <p><strong>Raison :</strong> <?php echo e($contract->termination_reason); ?></p>
                                <?php if($contract->terminatedBy): ?>
                                <p><strong>Résilié par :</strong> <?php echo e($contract->terminatedBy->name); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer">
                    <?php if($contract->status === 'draft' || $contract->status === 'pending'): ?>
                        <form action="<?php echo e(route('contracts.activate', $contract)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir activer ce contrat ?')">
                                <i class="fas fa-check"></i> Activer le Contrat
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if($contract->status === 'active'): ?>
                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#terminateModal">
                            <i class="fas fa-times"></i> Résilier le Contrat
                        </button>
                        
                        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#renewModal">
                            <i class="fas fa-sync"></i> Renouveler le Contrat
                        </button>
                    <?php endif; ?>
                    
                    <form action="<?php echo e(route('contracts.destroy', $contract)); ?>" method="POST" class="d-inline float-right">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce contrat ?')">
                            <i class="fas fa-trash"></i> Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Termination Modal -->
<div class="modal fade" id="terminateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo e(route('contracts.terminate', $contract)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Résilier le Contrat</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="termination_date">Date de Résiliation</label>
                        <input type="date" name="termination_date" id="termination_date" class="form-control" required min="<?php echo e(now()->format('Y-m-d')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="termination_reason">Raison de la Résiliation</label>
                        <textarea name="termination_reason" id="termination_reason" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">Confirmer la Résiliation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Renewal Modal -->
<div class="modal fade" id="renewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo e(route('contracts.renew', $contract)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title">Renouveler le Contrat</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="new_end_date">Nouvelle Date de Fin</label>
                        <input type="date" name="new_end_date" id="new_end_date" class="form-control" required min="<?php echo e(now()->format('Y-m-d')); ?>">
                    </div>
                    <div class="form-group">
                        <label for="new_salary">Nouveau Salaire (optionnel)</label>
                        <input type="number" step="0.01" name="new_salary" id="new_salary" class="form-control" value="<?php echo e($contract->base_salary); ?>">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-info">Confirmer le Renouvellement</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\contracts\show.blade.php ENDPATH**/ ?>