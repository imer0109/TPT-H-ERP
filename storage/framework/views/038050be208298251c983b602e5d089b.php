

<?php $__env->startSection('title', 'Profil Fournisseur'); ?>
<?php $__env->startSection('header', 'Profil'); ?>

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 py-10">
    <div class="mx-auto max-w-7xl px-4">
        <form action="<?php echo e(route('supplier.portal.update-profile')); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="space-y-8">

                <!-- CARD -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="mb-6 text-xl font-semibold text-gray-800">
                        Informations générales
                    </h2>

                    <div class="grid gap-6 md:grid-cols-2">
                        <?php echo $__env->make('fournisseurs.portal.partials.input', [
                            'label' => 'Raison sociale',
                            'name' => 'raison_sociale',
                            'required' => true,
                            'value' => old('raison_sociale', $supplier?->raison_sociale)
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php echo $__env->make('fournisseurs.portal.partials.select', [
                            'label' => 'Type',
                            'name' => 'type',
                            'required' => true,
                            'options' => [
                                'personne_physique' => 'Personne physique',
                                'entreprise' => 'Entreprise',
                                'institution' => 'Institution',
                            ],
                            'value' => old('type', $supplier?->type)
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php echo $__env->make('fournisseurs.portal.partials.select', [
                            'label' => 'Activité',
                            'name' => 'activite',
                            'required' => true,
                            'options' => [
                                'transport' => 'Transport',
                                'logistique' => 'Logistique',
                                'matieres_premieres' => 'Matières premières',
                                'services' => 'Services',
                                'autre' => 'Autre',
                            ],
                            'value' => old('activite', $supplier?->activite)
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php echo $__env->make('fournisseurs.portal.partials.input', [
                            'label' => 'Contact principal',
                            'name' => 'contact_principal',
                            'required' => true,
                            'value' => old('contact_principal', $supplier?->contact_principal)
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>

                <!-- COORDONNÉES -->
                <div class="rounded-xl bg-white p-6 shadow-sm">
                    <h2 class="mb-6 text-xl font-semibold text-gray-800">Coordonnées</h2>

                    <div class="grid gap-6 md:grid-cols-2">
                        <?php echo $__env->make('fournisseurs.portal.partials.input', [
                            'label' => 'Téléphone',
                            'name' => 'telephone',
                            'required' => true,
                            'value' => old('telephone', $supplier?->telephone)
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php echo $__env->make('fournisseurs.portal.partials.input', [
                            'label' => 'WhatsApp',
                            'name' => 'whatsapp',
                            'value' => old('whatsapp', $supplier?->whatsapp)
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php echo $__env->make('fournisseurs.portal.partials.input', [
                            'label' => 'Email',
                            'name' => 'email',
                            'type' => 'email',
                            'required' => true,
                            'value' => old('email', $supplier?->email)
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                        <?php echo $__env->make('fournisseurs.portal.partials.input', [
                            'label' => 'Site web',
                            'name' => 'site_web',
                            'type' => 'url',
                            'value' => old('site_web', $supplier?->site_web)
                        ], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                    </div>
                </div>

                <!-- BOUTON -->
                <div class="sticky bottom-0 bg-white py-4">
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="rounded-lg bg-primary-600 px-6 py-3 text-sm font-medium text-white shadow hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        >
                            Enregistrer les modifications
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('fournisseurs.portal.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views/fournisseurs/portal/profile.blade.php ENDPATH**/ ?>