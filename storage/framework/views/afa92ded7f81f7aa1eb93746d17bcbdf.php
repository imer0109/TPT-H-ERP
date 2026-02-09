

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails de la Fiche de Paie</h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <a href="<?php echo e(route('payroll.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <a href="<?php echo e(route('payroll.pdf', $payslip)); ?>" class="btn btn-primary" target="_blank">
                                <i class="fas fa-file-pdf"></i> Télécharger PDF
                            </a>
                            <?php if($payslip->status === 'draft'): ?>
                            <a href="<?php echo e(route('payroll.edit', $payslip)); ?>" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <button type="button" class="btn btn-success" id="validate-btn">
                                <i class="fas fa-check"></i> Valider
                            </button>
                            <?php endif; ?>
                            <?php if($payslip->status === 'validated'): ?>
                            <button type="button" class="btn btn-primary" id="pay-btn">
                                <i class="fas fa-money-bill"></i> Marquer comme Payé
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Informations Générales</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Référence</th>
                                    <td><?php echo e($payslip->reference); ?></td>
                                </tr>
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-<?php echo e($payslip->status_color); ?>"><?php echo e($payslip->status_label); ?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Période</th>
                                    <td><?php echo e($payslip->period->format('F Y')); ?></td>
                                </tr>
                                <tr>
                                    <th>Date de Génération</th>
                                    <td><?php echo e($payslip->created_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <?php if($payslip->validated_at): ?>
                                <tr>
                                    <th>Date de Validation</th>
                                    <td><?php echo e($payslip->validated_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <?php endif; ?>
                                <?php if($payslip->paid_at): ?>
                                <tr>
                                    <th>Date de Paiement</th>
                                    <td><?php echo e($payslip->paid_at->format('d/m/Y H:i')); ?></td>
                                </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h4>Informations Employé</h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Nom Complet</th>
                                    <td><?php echo e($payslip->employee->full_name); ?></td>
                                </tr>
                                <tr>
                                    <th>Matricule</th>
                                    <td><?php echo e($payslip->employee->employee_id); ?></td>
                                </tr>
                                <tr>
                                    <th>Poste</th>
                                    <td><?php echo e($payslip->employee->currentPosition->title ?? 'N/A'); ?></td>
                                </tr>
                                <tr>
                                    <th>Département</th>
                                    <td><?php echo e($payslip->employee->currentPosition->title ?? 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h4>Détails du Salaire</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th width="30%">Salaire de Base</th>
                                        <td><?php echo e(number_format($payslip->base_salary, 2)); ?> €</td>
                                    </tr>
                                    <tr>
                                        <th>Jours Travaillés</th>
                                        <td><?php echo e($payslip->worked_days); ?> jours</td>
                                    </tr>
                                    <tr>
                                        <th>Heures Supplémentaires</th>
                                        <td><?php echo e($payslip->overtime_hours); ?> heures</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h4>Gains</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-right">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $payslip->earnings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['name']); ?></td>
                                            <td class="text-right"><?php echo e(number_format($item['amount'], 2)); ?> €</td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="font-weight-bold">
                                            <td>Total des Gains</td>
                                            <td class="text-right"><?php echo e(number_format($payslip->gross_salary, 2)); ?> €</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>Déductions</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-right">Montant</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $payslip->deductions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($item['name']); ?></td>
                                            <td class="text-right"><?php echo e(number_format($item['amount'], 2)); ?> €</td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="font-weight-bold">
                                            <td>Total des Déductions</td>
                                            <td class="text-right"><?php echo e(number_format($payslip->gross_salary - $payslip->net_salary, 2)); ?> €</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr class="bg-light font-weight-bold">
                                        <th width="30%">Salaire Brut</th>
                                        <td class="text-right"><?php echo e(number_format($payslip->gross_salary, 2)); ?> €</td>
                                    </tr>
                                    <tr class="bg-light font-weight-bold">
                                        <th>Total Déductions</th>
                                        <td class="text-right"><?php echo e(number_format($payslip->gross_salary - $payslip->net_salary, 2)); ?> €</td>
                                    </tr>
                                    <tr class="bg-success text-white font-weight-bold">
                                        <th>Salaire Net à Payer</th>
                                        <td class="text-right"><?php echo e(number_format($payslip->net_salary, 2)); ?> €</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    // Validation de la fiche de paie
    $('#validate-btn').click(function() {
        if (confirm('Êtes-vous sûr de vouloir valider cette fiche de paie ?')) {
            $.post('<?php echo e(route("payroll.validate", $payslip)); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            })
            .done(function() {
                window.location.reload();
            })
            .fail(function(response) {
                alert('Erreur lors de la validation : ' + response.responseJSON.message);
            });
        }
    });
    
    // Paiement de la fiche de paie
    $('#pay-btn').click(function() {
        if (confirm('Êtes-vous sûr de vouloir marquer cette fiche de paie comme payée ?')) {
            $.post('<?php echo e(route("payroll.pay", $payslip)); ?>', {
                _token: '<?php echo e(csrf_token()); ?>'
            })
            .done(function() {
                window.location.reload();
            })
            .fail(function(response) {
                alert('Erreur lors du paiement : ' + response.responseJSON.message);
            });
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\payroll\show.blade.php ENDPATH**/ ?>