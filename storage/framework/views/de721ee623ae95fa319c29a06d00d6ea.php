

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Gérer les affectations de <?php echo e($user->name); ?></h1>
        <a href="<?php echo e(route('user-assignments.index')); ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Retour
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Affectations aux sociétés -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Affectations aux sociétés</h3>
            
            <form action="<?php echo e(route('user-assignments.assign.company', $user)); ?>" method="POST" class="mb-4">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700">Société</label>
                        <select name="company_id" id="company_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Sélectionner une société</option>
                            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($company->id); ?>"><?php echo e($company->raison_sociale); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
            
            <?php if($user->societes->count() > 0): ?>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Société</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $user->societes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($company->raison_sociale); ?>

                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                <?php if($company->pivot->date_debut): ?>
                                    Du <?php echo e(\Carbon\Carbon::parse($company->pivot->date_debut)->format('d/m/Y')); ?>

                                <?php endif; ?>
                                <?php if($company->pivot->date_fin): ?>
                                    Au <?php echo e(\Carbon\Carbon::parse($company->pivot->date_fin)->format('d/m/Y')); ?>

                                <?php endif; ?>
                                <?php if(!$company->pivot->date_debut && !$company->pivot->date_fin): ?>
                                    <span class="text-gray-500">Indéfini</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                <form action="<?php echo e(route('user-assignments.remove.company', [$user, $company->id])); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4 text-gray-500">
                <p>Aucune affectation à une société.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Affectations aux agences -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Affectations aux agences</h3>
            
            <form action="<?php echo e(route('user-assignments.assign.agency', $user)); ?>" method="POST" class="mb-4">
                <?php echo csrf_field(); ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="agency_id" class="block text-sm font-medium text-gray-700">Agence</label>
                        <select name="agency_id" id="agency_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Sélectionner une agence</option>
                            <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($agency->id); ?>"><?php echo e($agency->nom); ?> (<?php echo e($agency->company->raison_sociale); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_debut_agency" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut_agency"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="date_fin_agency" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin_agency"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
            
            <?php if($user->agences->count() > 0): ?>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agence</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php $__currentLoopData = $user->agences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                <?php echo e($agency->nom); ?>

                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                <?php if($agency->pivot->date_debut): ?>
                                    Du <?php echo e(\Carbon\Carbon::parse($agency->pivot->date_debut)->format('d/m/Y')); ?>

                                <?php endif; ?>
                                <?php if($agency->pivot->date_fin): ?>
                                    Au <?php echo e(\Carbon\Carbon::parse($agency->pivot->date_fin)->format('d/m/Y')); ?>

                                <?php endif; ?>
                                <?php if(!$agency->pivot->date_debut && !$agency->pivot->date_fin): ?>
                                    <span class="text-gray-500">Indéfini</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                <form action="<?php echo e(route('user-assignments.remove.agency', [$user, $agency->id])); ?>" method="POST" class="inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-4 text-gray-500">
                <p>Aucune affectation à une agence.</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Affectation à l'équipe et au département -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Affectation à l'équipe et au département</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <form action="<?php echo e(route('user-assignments.assign.team', $user)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label for="team_id" class="block text-sm font-medium text-gray-700">Équipe</label>
                        <select name="team_id" id="team_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Sélectionner une équipe</option>
                            <?php $__currentLoopData = $teams; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $team): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($team->id); ?>" <?php echo e($user->team_id == $team->id ? 'selected' : ''); ?>>
                                    <?php echo e($team->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Mettre à jour
                        </button>
                    </div>
                </form>
                
                <form action="<?php echo e(route('user-assignments.assign.department', $user)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Département</label>
                        <select name="department_id" id="department_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Sélectionner un département</option>
                            <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($department->id); ?>" <?php echo e($user->department_id == $department->id ? 'selected' : ''); ?>>
                                    <?php echo e($department->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Équipe actuelle:</span> 
                        <?php if($user->team): ?>
                            <?php echo e($user->team->name); ?>

                        <?php else: ?>
                            <span class="text-gray-500">Aucune</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Département actuel:</span> 
                        <?php if($user->department): ?>
                            <?php echo e($user->department->name); ?>

                        <?php else: ?>
                            <span class="text-gray-500">Aucun</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Affectation du responsable hiérarchique -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Responsable hiérarchique</h3>
            
            <form action="<?php echo e(route('user-assignments.assign.manager', $user)); ?>" method="POST" class="mb-4">
                <?php echo csrf_field(); ?>
                <div>
                    <label for="manager_id" class="block text-sm font-medium text-gray-700">Responsable</label>
                    <select name="manager_id" id="manager_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Sélectionner un responsable</option>
                        <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $manager): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($manager->id); ?>" <?php echo e($user->manager_id == $manager->id ? 'selected' : ''); ?>>
                                <?php echo e($manager->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="mt-2">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                        Mettre à jour
                    </button>
                </div>
            </form>
            
            <div>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Responsable actuel:</span> 
                    <?php if($user->manager): ?>
                        <?php echo e($user->manager->name); ?>

                    <?php else: ?>
                        <span class="text-gray-500">Aucun</span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\user_assignments\assign.blade.php ENDPATH**/ ?>