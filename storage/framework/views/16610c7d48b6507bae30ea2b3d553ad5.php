

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Créer une nouvelle agence</h1>
        <a href="<?php echo e(route('agencies.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Retour</a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6">
        <form action="<?php echo e(route('agencies.store')); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nom de l'agence -->
                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'agence</label>
                    <input type="text" name="nom" id="nom" class="mt-1 block w-full py-2 border rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                </div>

                <!-- Code unique -->
                <div>
                    <label for="code_unique" class="block text-sm font-medium text-gray-700">Code unique</label>
                    <input type="text" name="code_unique" id="code_unique" class="mt-1 block w-full py-2 border rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                </div>

                <!-- Société -->
                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700">Société</label>
                    <select name="company_id" id="company_id" class="mt-1 block w-full rounded-md py-2 border border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Sélectionner une société</option>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>"><?php echo e($company->raison_sociale); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Responsable -->
                <div>
                    <label for="responsable_id" class="block text-sm font-medium text-gray-700">Responsable</label>
                    <select name="responsable_id" id="responsable_id" class="mt-1 block w-full rounded-md py-2 border border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="">Sélectionner un responsable</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Zone géographique -->
                <div>
                    <label for="zone_geographique" class="block text-sm font-medium text-gray-700">Zone géographique</label>
                    <input type="text" name="zone_geographique" id="zone_geographique" class="mt-1 block py-2 border w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required>
                </div>

                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" id="statut" class="mt-1 block w-full rounded-md border-gray-300 py-2 border shadow-sm focus:border-red-500 focus:ring-red-500" required>
                        <option value="active">Active</option>
                        <option value="en_veille">En veille</option>
                    </select>
                </div>
            </div>

            <!-- Adresse -->
            <div>
                <label for="adresse" class="block text-sm font-medium text-gray-700">Adresse</label>
                <textarea name="adresse" id="adresse" rows="3" class="mt-1 block w-full rounded-md py-2 border border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" required></textarea>
            </div>

            <!-- Coordonnées GPS -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                    <input type="number" step="any" name="latitude" id="latitude" class="mt-1 block w-full py-2 border rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>

                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                    <input type="number" step="any" name="longitude" id="longitude" class="mt-1 block py-2 border w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg">Créer l'agence</button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/agencies/create.blade.php ENDPATH**/ ?>