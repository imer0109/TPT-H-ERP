

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modifier le Contrat</h3>
                </div>
                <form action="<?php echo e(route('contracts.update', $contract)); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="employee_id">Employé</label>
                                    <select name="employee_id" id="employee_id" class="form-control <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Sélectionner un employé</option>
                                        <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($employee->id); ?>" <?php echo e(old('employee_id', $contract->employee_id) == $employee->id ? 'selected' : ''); ?>>
                                                <?php echo e($employee->full_name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <div class="form-group">
                                    <label for="type">Type de Contrat</label>
                                    <select name="type" id="type" class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="CDI" <?php echo e(old('type', $contract->type) == 'CDI' ? 'selected' : ''); ?>>CDI</option>
                                        <option value="CDD" <?php echo e(old('type', $contract->type) == 'CDD' ? 'selected' : ''); ?>>CDD</option>
                                        <option value="Stage" <?php echo e(old('type', $contract->type) == 'Stage' ? 'selected' : ''); ?>>Stage</option>
                                        <option value="Prestation" <?php echo e(old('type', $contract->type) == 'Prestation' ? 'selected' : ''); ?>>Prestation</option>
                                        <option value="Intérim" <?php echo e(old('type', $contract->type) == 'Intérim' ? 'selected' : ''); ?>>Intérim</option>
                                    </select>
                                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="start_date">Date de Début</label>
                                    <input type="date" name="start_date" id="start_date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('start_date', $contract->start_date->format('Y-m-d'))); ?>" required>
                                    <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="end_date">Date de Fin (optionnel pour CDI)</label>
                                    <input type="date" name="end_date" id="end_date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('end_date', $contract->end_date ? $contract->end_date->format('Y-m-d') : '')); ?>">
                                    <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <div class="form-group">
                                    <label for="status">Statut</label>
                                    <select name="status" id="status" class="form-control <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="draft" <?php echo e(old('status', $contract->status) == 'draft' ? 'selected' : ''); ?>>Brouillon</option>
                                        <option value="pending" <?php echo e(old('status', $contract->status) == 'pending' ? 'selected' : ''); ?>>En attente</option>
                                        <option value="active" <?php echo e(old('status', $contract->status) == 'active' ? 'selected' : ''); ?>>Actif</option>
                                        <option value="terminated" <?php echo e(old('status', $contract->status) == 'terminated' ? 'selected' : ''); ?>>Résilié</option>
                                    </select>
                                    <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="base_salary">Salaire de Base</label>
                                    <input type="number" step="0.01" name="base_salary" id="base_salary" class="form-control <?php $__errorArgs = ['base_salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('base_salary', $contract->base_salary)); ?>" required>
                                    <?php $__errorArgs = ['base_salary'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="trial_period_months">Période d'Essai (en mois)</label>
                                    <input type="number" name="trial_period_months" id="trial_period_months" class="form-control <?php $__errorArgs = ['trial_period_months'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('trial_period_months')); ?>">
                                    <?php $__errorArgs = ['trial_period_months'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <div class="form-group">
                                    <label for="trial_period_start">Début de la Période d'Essai</label>
                                    <input type="date" name="trial_period_start" id="trial_period_start" class="form-control <?php $__errorArgs = ['trial_period_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('trial_period_start', $contract->trial_period_start ? $contract->trial_period_start->format('Y-m-d') : '')); ?>">
                                    <?php $__errorArgs = ['trial_period_start'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <div class="form-group">
                                    <label for="trial_period_end">Fin de la Période d'Essai</label>
                                    <input type="date" name="trial_period_end" id="trial_period_end" class="form-control <?php $__errorArgs = ['trial_period_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" value="<?php echo e(old('trial_period_end', $contract->trial_period_end ? $contract->trial_period_end->format('Y-m-d') : '')); ?>">
                                    <?php $__errorArgs = ['trial_period_end'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="benefits">Avantages</label>
                                    <textarea name="benefits" id="benefits" class="form-control <?php $__errorArgs = ['benefits'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"><?php echo e(old('benefits', $contract->benefits)); ?></textarea>
                                    <?php $__errorArgs = ['benefits'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="contract_file">Document du Contrat</label>
                                    <?php if($contract->contract_file): ?>
                                        <div class="mb-2">
                                            <a href="<?php echo e(Storage::url($contract->contract_file)); ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Document actuel
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="contract_file" id="contract_file" class="form-control-file <?php $__errorArgs = ['contract_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">Laissez vide pour conserver le document actuel</small>
                                    <?php $__errorArgs = ['contract_file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                
                                <div class="form-group">
                                    <label for="hiring_form">Fiche d'Embauche</label>
                                    <?php if($contract->hiring_form): ?>
                                        <div class="mb-2">
                                            <a href="<?php echo e(Storage::url($contract->hiring_form)); ?>" target="_blank" class="btn btn-sm btn-info">
                                                <i class="fas fa-download"></i> Fiche actuelle
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" name="hiring_form" id="hiring_form" class="form-control-file <?php $__errorArgs = ['hiring_form'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept=".pdf,.doc,.docx">
                                    <small class="form-text text-muted">Laissez vide pour conserver la fiche actuelle</small>
                                    <?php $__errorArgs = ['hiring_form'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><?php echo e($message); ?></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>
                        
                        <?php if($contract->status === 'terminated'): ?>
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-warning">
                                    <h5><i class="fas fa-exclamation-triangle"></i> Contrat Résilié</h5>
                                    <p><strong>Date de résiliation :</strong> <?php echo e($contract->terminated_at->format('d/m/Y')); ?></p>
                                    <p><strong>Raison :</strong> <?php echo e($contract->termination_reason); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        <a href="<?php echo e(route('contracts.show', $contract)); ?>" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('type').addEventListener('change', function() {
    const endDateField = document.getElementById('end_date');
    if (this.value === 'CDI') {
        endDateField.removeAttribute('required');
        endDateField.value = '';
    } else {
        endDateField.setAttribute('required', 'required');
    }
});

// Auto-calculate trial period end date
document.getElementById('trial_period_start').addEventListener('change', function() {
    const startDate = this.value;
    const trialMonths = document.getElementById('trial_period_months').value;
    
    if (startDate && trialMonths) {
        const endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + parseInt(trialMonths));
        
        const formattedDate = endDate.toISOString().split('T')[0];
        document.getElementById('trial_period_end').value = formattedDate;
    }
});

document.getElementById('trial_period_months').addEventListener('change', function() {
    const startDate = document.getElementById('trial_period_start').value;
    const trialMonths = this.value;
    
    if (startDate && trialMonths) {
        const endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + parseInt(trialMonths));
        
        const formattedDate = endDate.toISOString().split('T')[0];
        document.getElementById('trial_period_end').value = formattedDate;
    }
});

// Déclencher l'événement au chargement pour initialiser correctement
document.getElementById('type').dispatchEvent(new Event('change'));
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\contracts\edit.blade.php ENDPATH**/ ?>