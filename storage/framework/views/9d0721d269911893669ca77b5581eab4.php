

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="<?php echo e(route('hr.evaluations.index')); ?>">Évaluations</a></li>
                        <li class="breadcrumb-item active">Modifier Évaluation</li>
                    </ol>
                </div>
                <h4 class="page-title">Modifier l'Évaluation</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo e(route('hr.evaluations.update', $evaluation)); ?>" method="POST" id="evaluationForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="employee_id">Employé <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="employee_id" id="employee_id" required disabled>
                                        <option value="<?php echo e($evaluation->employee->id); ?>" selected><?php echo e($evaluation->employee->full_name); ?> - <?php echo e($evaluation->employee->currentPosition->title ?? 'N/A'); ?></option>
                                    </select>
                                    <input type="hidden" name="employee_id" value="<?php echo e($evaluation->employee->id); ?>">
                                    <small class="text-muted">L'employé ne peut pas être modifié après création</small>
                                    <?php $__errorArgs = ['employee_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="evaluation_period">Période d'Évaluation <span class="text-danger">*</span></label>
                                    <select class="form-control" name="evaluation_period" id="evaluation_period" required disabled>
                                        <option value="<?php echo e($evaluation->period); ?>" selected><?php echo e($evaluation->period); ?></option>
                                    </select>
                                    <input type="hidden" name="evaluation_period" value="<?php echo e($evaluation->period); ?>">
                                    <small class="text-muted">La période ne peut pas être modifiée après création</small>
                                    <?php $__errorArgs = ['evaluation_period'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="evaluation_type">Type d'Évaluation <span class="text-danger">*</span></label>
                                    <select class="form-control" name="evaluation_type" id="evaluation_type" required>
                                        <option value="">Sélectionner le type</option>
                                        <option value="performance" <?php echo e(old('evaluation_type', $evaluation->evaluation_type) == 'performance' ? 'selected' : ''); ?>>Performance</option>
                                        <option value="competency" <?php echo e(old('evaluation_type', $evaluation->evaluation_type) == 'competency' ? 'selected' : ''); ?>>Compétences</option>
                                        <option value="360_feedback" <?php echo e(old('evaluation_type', $evaluation->evaluation_type) == '360_feedback' ? 'selected' : ''); ?>>Feedback 360°</option>
                                        <option value="probation" <?php echo e(old('evaluation_type', $evaluation->evaluation_type) == 'probation' ? 'selected' : ''); ?>>Période d'essai</option>
                                        <option value="annual_review" <?php echo e(old('evaluation_type', $evaluation->evaluation_type) == 'annual_review' ? 'selected' : ''); ?>>Revue annuelle</option>
                                    </select>
                                    <?php $__errorArgs = ['evaluation_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="evaluator_id">Évaluateur <span class="text-danger">*</span></label>
                                    <select class="form-control select2" name="evaluator_id" id="evaluator_id" required>
                                        <option value="">Sélectionner un évaluateur</option>
                                        <?php $__currentLoopData = $evaluators; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $evaluator): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($evaluator->id); ?>" <?php echo e(old('evaluator_id', $evaluation->evaluator_id) == $evaluator->id ? 'selected' : ''); ?>><?php echo e($evaluator->full_name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['evaluator_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="due_date">Date d'Échéance</label>
                                    <input type="date" class="form-control" name="due_date" id="due_date" 
                                           value="<?php echo e(old('due_date', $evaluation->due_date ? $evaluation->due_date->format('Y-m-d') : '')); ?>">
                                    <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="overall_rating">Note Globale (1-5)</label>
                                    <select class="form-control" name="overall_rating" id="overall_rating">
                                        <option value="">À déterminer</option>
                                        <option value="1" <?php echo e(old('overall_rating', $evaluation->overall_score) == 1 ? 'selected' : ''); ?>>1 - Insatisfaisant</option>
                                        <option value="2" <?php echo e(old('overall_rating', $evaluation->overall_score) == 2 ? 'selected' : ''); ?>>2 - Peu satisfaisant</option>
                                        <option value="3" <?php echo e(old('overall_rating', $evaluation->overall_score) == 3 ? 'selected' : ''); ?>>3 - Satisfaisant</option>
                                        <option value="4" <?php echo e(old('overall_rating', $evaluation->overall_score) == 4 ? 'selected' : ''); ?>>4 - Très satisfaisant</option>
                                        <option value="5" <?php echo e(old('overall_rating', $evaluation->overall_score) == 5 ? 'selected' : ''); ?>>5 - Exceptionnel</option>
                                    </select>
                                    <?php $__errorArgs = ['overall_rating'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="header-title mb-3">Objectifs et Performance</h5>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="objectives">Objectifs de la Période</label>
                                    <textarea class="form-control" name="objectives" id="objectives" rows="4" 
                                              placeholder="Décrivez les objectifs fixés pour cette période..."><?php echo e(old('objectives', $evaluation->objectives)); ?></textarea>
                                    <?php $__errorArgs = ['objectives'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="achievements">Réalisations</label>
                                    <textarea class="form-control" name="achievements" id="achievements" rows="4" 
                                              placeholder="Décrivez les réalisations et accomplissements..."><?php echo e(old('achievements', $evaluation->achievements)); ?></textarea>
                                    <?php $__errorArgs = ['achievements'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-group">
                                    <label for="areas_improvement">Axes d'Amélioration</label>
                                    <textarea class="form-control" name="areas_improvement" id="areas_improvement" rows="3" 
                                              placeholder="Identifiez les domaines nécessitant une amélioration..."><?php echo e(old('areas_improvement', $evaluation->areas_improvement)); ?></textarea>
                                    <?php $__errorArgs = ['areas_improvement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5 class="header-title mb-3">Compétences Évaluées</h5>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <?php $__currentLoopData = array_slice($criteria, 0, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group">
                                    <label for="<?php echo e($key); ?>"><?php echo e($criterion['name']); ?> (1-5)</label>
                                    <select class="form-control" name="<?php echo e($key); ?>" id="<?php echo e($key); ?>">
                                        <option value="">Non évalué</option>
                                        <?php for($i=1;$i<=5;$i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e(old($key, $evaluation->getCriteriaScore($key)) == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <small class="text-muted"><?php echo e($criterion['description']); ?></small>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <div class="col-md-6">
                                <?php $__currentLoopData = array_slice($criteria, 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $criterion): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group">
                                    <label for="<?php echo e($key); ?>"><?php echo e($criterion['name']); ?> (1-5)</label>
                                    <select class="form-control" name="<?php echo e($key); ?>" id="<?php echo e($key); ?>">
                                        <option value="">Non évalué</option>
                                        <?php for($i=1;$i<=5;$i++): ?>
                                            <option value="<?php echo e($i); ?>" <?php echo e(old($key, $evaluation->getCriteriaScore($key)) == $i ? 'selected' : ''); ?>><?php echo e($i); ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <small class="text-muted"><?php echo e($criterion['description']); ?></small>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="comments">Commentaires Généraux</label>
                                    <textarea class="form-control" name="comments" id="comments" rows="4" 
                                              placeholder="Commentaires additionnels, recommandations, plan de développement..."><?php echo e(old('comments', $evaluation->recommendations)); ?></textarea>
                                    <?php $__errorArgs = ['comments'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group text-right">
                            <a href="<?php echo e(route('hr.evaluations.index')); ?>" class="btn btn-secondary mr-2">Annuler</a>
                            <?php if($evaluation->isDraft()): ?>
                            <button type="submit" name="action" value="draft" class="btn btn-warning mr-2">
                                <i class="mdi mdi-content-save mr-1"></i>Enregistrer les Modifications
                            </button>
                            <button type="submit" name="action" value="submit" class="btn btn-primary">
                                <i class="mdi mdi-send mr-1"></i>Soumettre l'Évaluation
                            </button>
                            <?php else: ?>
                            <button type="submit" name="action" value="draft" class="btn btn-warning mr-2">
                                <i class="mdi mdi-content-save mr-1"></i>Enregistrer les Modifications
                            </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\evaluations\edit.blade.php ENDPATH**/ ?>