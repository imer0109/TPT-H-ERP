<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier le client: <?php echo e($client->nom_raison_sociale); ?></h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('clients.show', $client)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-eye mr-2"></i> Voir la fiche
            </a>
            <a href="<?php echo e(route('clients.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="<?php echo e(route('clients.update', $client)); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Informations générales -->
                <div class="md:col-span-3">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations générales</h2>
                </div>

                <div>
                    <label for="code_client" class="block text-sm font-medium text-gray-700 mb-1">Code client</label>
                    <input type="text" name="code_client" id="code_client" value="<?php echo e(old('code_client', $client->code_client)); ?>" readonly 
                        class="w-full rounded-md border-gray-300 bg-gray-100 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                </div>

                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Société <span class="text-red-600">*</span></label>
                    <select name="company_id" id="company_id" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner une société</option>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>" <?php echo e(old('company_id', $client->company_id) == $company->id ? 'selected' : ''); ?>>
                                <?php echo e($company->raison_sociale); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="agency_id" class="block text-sm font-medium text-gray-700 mb-1">Agence</label>
                    <select name="agency_id" id="agency_id" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner une agence</option>
                        <?php $__currentLoopData = $agencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($agency->id); ?>" <?php echo e(old('agency_id', $client->agency_id) == $agency->id ? 'selected' : ''); ?>>
                                <?php echo e($agency->nom); ?> (<?php echo e($agency->company->raison_sociale); ?>)
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['agency_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="type_client" class="block text-sm font-medium text-gray-700 mb-1">Type de client <span class="text-red-600">*</span></label>
                    <select name="type_client" id="type_client" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un type</option>
                        <option value="particulier" <?php echo e(old('type_client', $client->type_client) == 'particulier' ? 'selected' : ''); ?>>Particulier</option>
                        <option value="entreprise" <?php echo e(old('type_client', $client->type_client) == 'entreprise' ? 'selected' : ''); ?>>Entreprise</option>
                        <option value="administration" <?php echo e(old('type_client', $client->type_client) == 'administration' ? 'selected' : ''); ?>>Administration</option>
                        <option value="distributeur" <?php echo e(old('type_client', $client->type_client) == 'distributeur' ? 'selected' : ''); ?>>Distributeur</option>
                    </select>
                    <?php $__errorArgs = ['type_client'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="nom_raison_sociale" class="block text-sm font-medium text-gray-700 mb-1">Nom/Raison sociale <span class="text-red-600">*</span></label>
                    <input type="text" name="nom_raison_sociale" id="nom_raison_sociale" value="<?php echo e(old('nom_raison_sociale', $client->nom_raison_sociale)); ?>" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['nom_raison_sociale'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                    <input type="text" name="adresse" id="adresse" value="<?php echo e(old('adresse', $client->adresse)); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['adresse'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <input type="text" name="ville" id="ville" value="<?php echo e(old('ville', $client->ville)); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['ville'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="contact_principal" class="block text-sm font-medium text-gray-700 mb-1">Contact principal (entreprise)</label>
                    <input type="text" name="contact_principal" id="contact_principal" value="<?php echo e(old('contact_principal', $client->contact_principal)); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['contact_principal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="<?php echo e(old('whatsapp', $client->whatsapp)); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['whatsapp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="canal_acquisition" class="block text-sm font-medium text-gray-700 mb-1">Canal d'acquisition</label>
                    <select name="canal_acquisition" id="canal_acquisition" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un canal</option>
                        <option value="commerce_direct" <?php echo e(old('canal_acquisition', $client->canal_acquisition) == 'commerce_direct' ? 'selected' : ''); ?>>Commerce direct</option>
                        <option value="web" <?php echo e(old('canal_acquisition', $client->canal_acquisition) == 'web' ? 'selected' : ''); ?>>Web</option>
                        <option value="recommande" <?php echo e(old('canal_acquisition', $client->canal_acquisition) == 'recommande' ? 'selected' : ''); ?>>Recommandé</option>
                        <option value="reseaux_sociaux" <?php echo e(old('canal_acquisition', $client->canal_acquisition) == 'reseaux_sociaux' ? 'selected' : ''); ?>>Réseaux sociaux</option>
                        <option value="evenement" <?php echo e(old('canal_acquisition', $client->canal_acquisition) == 'evenement' ? 'selected' : ''); ?>>Événement</option>
                    </select>
                    <?php $__errorArgs = ['canal_acquisition'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Coordonnées -->
                <div class="md:col-span-3">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Coordonnées</h2>
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone <span class="text-red-600">*</span></label>
                    <input type="text" name="telephone" id="telephone" value="<?php echo e(old('telephone', $client->telephone)); ?>" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['telephone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo e(old('email', $client->email)); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="site_web" class="block text-sm font-medium text-gray-700 mb-1">Site web</label>
                    <input type="url" name="site_web" id="site_web" value="<?php echo e(old('site_web', $client->site_web)); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['site_web'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Informations financières -->
                <div class="md:col-span-3">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Informations financières</h2>
                </div>

                <div>
                    <label for="type_relation" class="block text-sm font-medium text-gray-700 mb-1">Type de relation</label>
                    <select name="type_relation" id="type_relation" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un type</option>
                        <option value="comptant" <?php echo e(old('type_relation', $client->type_relation) == 'comptant' ? 'selected' : ''); ?>>Comptant</option>
                        <option value="credit" <?php echo e(old('type_relation', $client->type_relation) == 'credit' ? 'selected' : ''); ?>>Crédit</option>
                        <option value="mixte" <?php echo e(old('type_relation', $client->type_relation) == 'mixte' ? 'selected' : ''); ?>>Mixte</option>
                    </select>
                    <?php $__errorArgs = ['type_relation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="delai_paiement" class="block text-sm font-medium text-gray-700 mb-1">Délai de paiement (jours)</label>
                    <input type="number" name="delai_paiement" id="delai_paiement" value="<?php echo e(old('delai_paiement', $client->delai_paiement)); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['delai_paiement'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="plafond_credit" class="block text-sm font-medium text-gray-700 mb-1">Plafond de crédit (FCFA)</label>
                    <input type="number" name="plafond_credit" id="plafond_credit" value="<?php echo e(old('plafond_credit', $client->plafond_credit)); ?>" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                    <?php $__errorArgs = ['plafond_credit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="mode_paiement_prefere" class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement préféré</label>
                    <select name="mode_paiement_prefere" id="mode_paiement_prefere" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un mode</option>
                        <option value="especes" <?php echo e(old('mode_paiement_prefere', $client->mode_paiement_prefere) == 'especes' ? 'selected' : ''); ?>>Espèces</option>
                        <option value="cheque" <?php echo e(old('mode_paiement_prefere', $client->mode_paiement_prefere) == 'cheque' ? 'selected' : ''); ?>>Chèque</option>
                        <option value="virement" <?php echo e(old('mode_paiement_prefere', $client->mode_paiement_prefere) == 'virement' ? 'selected' : ''); ?>>Virement</option>
                        <option value="carte_bancaire" <?php echo e(old('mode_paiement_prefere', $client->mode_paiement_prefere) == 'carte_bancaire' ? 'selected' : ''); ?>>Carte bancaire</option>
                        <option value="mobile_money" <?php echo e(old('mode_paiement_prefere', $client->mode_paiement_prefere) == 'mobile_money' ? 'selected' : ''); ?>>Mobile Money</option>
                    </select>
                    <?php $__errorArgs = ['mode_paiement_prefere'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Autres informations -->
                <div class="md:col-span-3">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Autres informations</h2>
                </div>

                <div>
                    <label for="referent_commercial_id" class="block text-sm font-medium text-gray-700 mb-1">Référent commercial</label>
                    <select name="referent_commercial_id" id="referent_commercial_id" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner un référent</option>
                        <?php $__currentLoopData = $commerciaux; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commercial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($commercial->id); ?>" <?php echo e(old('referent_commercial_id', $client->referent_commercial_id) == $commercial->id ? 'selected' : ''); ?>>
                                <?php echo e($commercial->nom); ?> <?php echo e($commercial->prenom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['referent_commercial_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select name="categorie" id="categorie" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="">Sélectionner une catégorie</option>
                        <option value="or" <?php echo e(old('categorie', $client->categorie) == 'or' ? 'selected' : ''); ?>>Or - Premium</option>
                        <option value="argent" <?php echo e(old('categorie', $client->categorie) == 'argent' ? 'selected' : ''); ?>>Argent - Standard</option>
                        <option value="bronze" <?php echo e(old('categorie', $client->categorie) == 'bronze' ? 'selected' : ''); ?>>Bronze - Occasionnel</option>
                    </select>
                    <?php $__errorArgs = ['categorie'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" id="statut" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        <option value="actif" <?php echo e(old('statut', $client->statut) == 'actif' ? 'selected' : ''); ?>>Actif</option>
                        <option value="inactif" <?php echo e(old('statut', $client->statut) == 'inactif' ? 'selected' : ''); ?>>Inactif</option>
                        <option value="suspendu" <?php echo e(old('statut', $client->statut) == 'suspendu' ? 'selected' : ''); ?>>Suspendu</option>
                    </select>
                    <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Documents -->
                <div class="md:col-span-3">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-4">Documents</h2>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ajouter des documents (PDF, JPG, PNG)</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="document_identite" class="block text-sm font-medium text-gray-700 mb-1">Pièce d'identité</label>
                            <input type="file" name="documents[identite]" id="document_identite" accept=".pdf,.jpg,.jpeg,.png" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        <div>
                            <label for="document_registre" class="block text-sm font-medium text-gray-700 mb-1">Registre de commerce</label>
                            <input type="file" name="documents[registre]" id="document_registre" accept=".pdf,.jpg,.jpeg,.png" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                        <div>
                            <label for="document_autre" class="block text-sm font-medium text-gray-700 mb-1">Autre document</label>
                            <input type="file" name="documents[autre]" id="document_autre" accept=".pdf,.jpg,.jpeg,.png" 
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">
                        </div>
                    </div>
                    <?php $__errorArgs = ['documents'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <!-- Documents existants -->
                <?php if($client->documents->count() > 0): ?>
                    <div class="md:col-span-3 mt-4">
                        <h3 class="text-md font-semibold text-gray-700 mb-2">Documents existants</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <?php $__currentLoopData = $client->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border rounded-lg p-3 flex items-center justify-between">
                                    <div class="flex items-center">
                                        <span class="text-xl mr-2 <?php echo e($document->format == 'pdf' ? 'text-red-500' : 'text-blue-500'); ?>">
                                            <i class="fas <?php echo e($document->format == 'pdf' ? 'fa-file-pdf' : 'fa-file-image'); ?>"></i>
                                        </span>
                                        <div class="truncate max-w-xs">
                                            <p class="font-semibold text-sm"><?php echo e($document->nom); ?></p>
                                            <p class="text-xs text-gray-500"><?php echo e(number_format($document->taille / 1024, 2)); ?> KB</p>
                                        </div>
                                    </div>
                                    <div class="flex">
                                        <a href="<?php echo e(route('documents.download', $document)); ?>" class="text-blue-600 hover:text-blue-900 mr-2">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <form action="<?php echo e(route('documents.destroy', $document)); ?>" method="POST" class="inline-block">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce document ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Notes -->
                <div class="md:col-span-3">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="4" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200"><?php echo e(old('notes', $client->notes)); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="<?php echo e(route('clients.show', $client)); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Enregistrer les modifications
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\edit.blade.php ENDPATH**/ ?>