

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 py-6">

    
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-col items-center">

                <img src="<?php echo e($employee->photo ? asset('storage/' . $employee->photo) : asset('img/default-avatar.png')); ?>"
                     class="w-28 h-28 rounded-full object-cover border">

                <h2 class="mt-4 text-xl font-semibold">
                    <?php echo e($employee->last_name); ?> <?php echo e($employee->first_name); ?>

                </h2>

                <p class="text-gray-500 text-sm">
                    <?php echo e($employee->currentPosition->title ?? 'Aucun poste'); ?>

                </p>

                <div class="w-full mt-6 space-y-2">
                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="font-medium">Matricule</span>
                        <span><?php echo e($employee->matricule); ?></span>
                    </div>

                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="font-medium">Email</span>
                        <span><?php echo e($employee->email); ?></span>
                    </div>

                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="font-medium">Téléphone</span>
                        <span><?php echo e($employee->phone ?? 'N/A'); ?></span>
                    </div>

                    <div class="flex justify-between text-sm border-b pb-2">
                        <span class="font-medium">Statut</span>

                        <span class="px-2 py-1 rounded text-white text-xs
                            <?php echo e($employee->status === 'actif' ? 'bg-green-600' : 'bg-red-600'); ?>">
                            <?php echo e(ucfirst($employee->status)); ?>

                        </span>
                    </div>
                </div>

                
                <div class="flex gap-2 mt-6 w-full">
                    <a href="<?php echo e(route('hr.employees.edit', $employee)); ?>"
                       class="flex-1 bg-yellow-500 hover:bg-yellow-600 text-white py-2 rounded">
                        Modifier
                    </a>

                    <button onclick="confirmDelete('<?php echo e($employee->id); ?>')"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-2 rounded">
                        Supprimer
                    </button>
                </div>

            </div>
        </div>

        
        <div class="lg:col-span-3 bg-white shadow rounded-lg p-6">

            
            <ul class="flex border-b mb-6 space-x-4 text-sm font-medium">
                <li><a href="#details" class="tab-link active-tab">Détails</a></li>
                <li><a href="#contracts" class="tab-link">Contrats</a></li>
                <li><a href="#leaves" class="tab-link">Congés</a></li>
                <li><a href="#attendances" class="tab-link">Présences</a></li>
                <li><a href="#evaluations" class="tab-link">Évaluations</a></li>
                <li><a href="#assignments" class="tab-link">Affectations</a></li>
            </ul>

            
            <div class="flex flex-wrap gap-3 mb-6">

                
                <a href="<?php echo e(route('hr.contracts.index')); ?>"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   <?php echo e(request()->routeIs('hr.contracts.*')
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    Contrats
                </a>

                
                <a href="<?php echo e(route('hr.leaves.index')); ?>"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   <?php echo e(request()->routeIs('hr.leaves.*')
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    Congés
                </a>

                
                <a href="<?php echo e(route('hr.attendances.index')); ?>"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   <?php echo e(request()->routeIs('hr.attendances.*')
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    Présences
                </a>

                
                <a href="<?php echo e(route('hr.evaluations.index')); ?>"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   <?php echo e(request()->routeIs('hr.evaluations.*')
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    Évaluations
                </a>

                
                <a href="#assignments"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   <?php echo e(request()->routeIs('hr.employees.assignments.*')
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>"
                   onclick="document.querySelector('a[href=\'#assignments\']').click();">
                    Affectations
                </a>

                
                <a href="#"
                   class="px-5 py-2 rounded-lg font-semibold transition
                   <?php echo e(request()->routeIs('autre.*')
                        ? 'bg-blue-600 text-white shadow-lg'
                        : 'bg-gray-100 text-gray-700 hover:bg-gray-200'); ?>">
                    Autres
                </a>

            </div>

            
            <div id="details" class="tab-content block">
                <h3 class="text-lg font-semibold mb-4">Informations Personnelles</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <table class="w-full text-sm">
                        <tr class="border-b">
                            <th class="py-2 font-medium">Date de naissance</th>
                            <td><?php echo e($employee->birth_date ? $employee->birth_date->format('d/m/Y') : 'N/A'); ?></td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 font-medium">Lieu de naissance</th>
                            <td><?php echo e($employee->birth_place ?? 'N/A'); ?></td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 font-medium">Nationalité</th>
                            <td><?php echo e($employee->nationality ?? 'N/A'); ?></td>
                        </tr>
                    </table>

                    <table class="w-full text-sm">
                        <tr class="border-b">
                            <th class="py-2 font-medium">Poste actuel</th>
                            <td><?php echo e($employee->currentPosition->title ?? 'N/A'); ?></td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 font-medium">Date d'embauche</th>
                            <td><?php echo e($employee->getDateEmbaucheAttribute()?->format('d/m/Y') ?? 'N/A'); ?></td>
                        </tr>
                        <tr class="border-b">
                            <th class="py-2 font-medium">Superviseur</th>
                            <td>
                                <?php if($employee->supervisor): ?>
                                    <?php echo e($employee->supervisor->last_name); ?> <?php echo e($employee->supervisor->first_name); ?>

                                <?php else: ?>
                                    Non assigné
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

        </div>

    </div>
</div>


<script>
    const links = document.querySelectorAll('.tab-link');
    const contents = document.querySelectorAll('.tab-content');

    links.forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();

            links.forEach(l => l.classList.remove('active-tab'));
            link.classList.add('active-tab');

            contents.forEach(c => c.classList.add('hidden'));
            const target = document.querySelector(link.getAttribute('href'));
            target.classList.remove('hidden');
        });
    });
</script>


<style>
    .tab-link { @apply pb-2 border-b-2 border-transparent text-gray-500 hover:text-gray-800; }
    .active-tab { @apply text-blue-600 border-blue-600; }
    .tab-content { @apply hidden; }
    .tab-content.block { @apply block; }
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\employees\show.blade.php ENDPATH**/ ?>