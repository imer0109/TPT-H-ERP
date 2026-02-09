<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Fiche Client: <?php echo e($client->nom_raison_sociale); ?></h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('clients.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <a href="<?php echo e(route('clients.edit', $client)); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <a href="<?php echo e(route('clients.score', $client)); ?>" class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-chart-bar mr-2"></i> Scoring
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations générales</h2>
            
            <div class="mb-4 flex items-center">
                <span class="text-3xl mr-3 bg-red-100 text-red-800 p-3 rounded-full">
                    <i class="fas <?php echo e($client->type_client == 'particulier' ? 'fa-user' : 'fa-building'); ?>"></i>
                </span>
                <div>
                    <p class="text-sm text-gray-500">Code client</p>
                    <p class="font-semibold"><?php echo e($client->code_client); ?></p>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Type</div>
                    <div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($client->type_client == 'particulier' ? 'bg-blue-100 text-blue-800' : ''); ?>

                            <?php echo e($client->type_client == 'entreprise' ? 'bg-green-100 text-green-800' : ''); ?>

                            <?php echo e($client->type_client == 'administration' ? 'bg-purple-100 text-purple-800' : ''); ?>

                            <?php echo e($client->type_client == 'distributeur' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                        ">
                            <?php echo e(ucfirst($client->type_client)); ?>

                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Société</div>
                    <div><?php echo e($client->company->raison_sociale); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Agence</div>
                    <div><?php echo e($client->agency ? $client->agency->nom : 'Non assigné'); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Adresse</div>
                    <div><?php echo e($client->adresse ?: 'Non renseignée'); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Ville</div>
                    <div><?php echo e($client->ville ?: 'Non renseignée'); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Contact principal</div>
                    <div><?php echo e($client->contact_principal ?: 'Non renseigné'); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Canal d'acquisition</div>
                    <div>
                        <?php switch($client->canal_acquisition):
                            case ('commerce_direct'): ?>
                                Commerce direct
                                <?php break; ?>
                            <?php case ('web'): ?>
                                Web
                                <?php break; ?>
                            <?php case ('recommande'): ?>
                                Recommandé
                                <?php break; ?>
                            <?php case ('reseaux_sociaux'): ?>
                                Réseaux sociaux
                                <?php break; ?>
                            <?php case ('evenement'): ?>
                                Événement
                                <?php break; ?>
                            <?php default: ?>
                                Non renseigné
                        <?php endswitch; ?>
                    </div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Statut</div>
                    <div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            <?php echo e($client->statut == 'actif' ? 'bg-green-100 text-green-800' : ''); ?>

                            <?php echo e($client->statut == 'inactif' ? 'bg-gray-100 text-gray-800' : ''); ?>

                            <?php echo e($client->statut == 'suspendu' ? 'bg-red-100 text-red-800' : ''); ?>

                        ">
                            <?php echo e(ucfirst($client->statut)); ?>

                        </span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Catégorie</div>
                    <div><?php echo e($client->categorie ? 'Catégorie ' . $client->categorie : 'Non catégorisé'); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Référent commercial</div>
                    <div><?php echo e($client->referentCommercial ? $client->referentCommercial->nom . ' ' . $client->referentCommercial->prenom : 'Non assigné'); ?></div>
                </div>
            </div>
        </div>

        <!-- Coordonnées -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Coordonnées</h2>
            
            <div class="space-y-4">
                <div class="flex items-center">
                    <span class="text-xl mr-3 bg-blue-100 text-blue-800 p-2 rounded-full">
                        <i class="fas fa-phone"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Téléphone</p>
                        <p class="font-semibold"><?php echo e($client->telephone); ?></p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <span class="text-xl mr-3 bg-green-100 text-green-800 p-2 rounded-full">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-semibold"><?php echo e($client->email ?: 'Non renseigné'); ?></p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <span class="text-xl mr-3 bg-blue-100 text-blue-800 p-2 rounded-full">
                        <i class="fab fa-whatsapp"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">WhatsApp</p>
                        <p class="font-semibold"><?php echo e($client->whatsapp ?: 'Non renseigné'); ?></p>
                    </div>
                </div>
                
                <div class="flex items-center">
                    <span class="text-xl mr-3 bg-purple-100 text-purple-800 p-2 rounded-full">
                        <i class="fas fa-globe"></i>
                    </span>
                    <div>
                        <p class="text-sm text-gray-500">Site web</p>
                        <p class="font-semibold"><?php echo e($client->site_web ?: 'Non renseigné'); ?></p>
                    </div>
                </div>
            </div>
            
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2 mt-6">Notes</h2>
            <div class="bg-gray-50 p-3 rounded">
                <?php echo e($client->notes ?: 'Aucune note disponible'); ?>

            </div>
        </div>

        <!-- Informations financières -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations financières</h2>
            
            <div class="mb-4">
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm text-gray-500">Encours actuel</p>
                    <p class="text-2xl font-bold text-red-600"><?php echo e(number_format($client->getEncours(), 0, ',', ' ')); ?> FCFA</p>
                    <p class="text-sm text-gray-500 mt-1"><?php echo e($client->getNombreFacturesImpayees()); ?> facture(s) impayée(s)</p>
                </div>
            </div>
            
            <div class="space-y-3 mt-6">
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Type de relation</div>
                    <div><?php echo e(ucfirst($client->type_relation ?: 'Non défini')); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Délai de paiement</div>
                    <div><?php echo e($client->delai_paiement ? $client->delai_paiement . ' jours' : 'Non défini'); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Plafond de crédit</div>
                    <div><?php echo e($client->plafond_credit ? number_format($client->plafond_credit, 0, ',', ' ') . ' FCFA' : 'Non défini'); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Mode de paiement préféré</div>
                    <div><?php echo e(ucfirst(str_replace('_', ' ', $client->mode_paiement_prefere ?: 'Non défini'))); ?></div>
                </div>
                
                <div class="grid grid-cols-2">
                    <div class="text-sm text-gray-500">Délai moyen de règlement</div>
                    <div><?php echo e($client->getDelaiMoyenReglement()); ?> jours</div>
                </div>
            </div>
            
            <div class="mt-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="font-semibold text-gray-700">Dernières transactions</h3>
                    <a href="<?php echo e(route('clients.transactions', $client)); ?>" class="text-sm text-blue-600 hover:text-blue-800">
                        Voir toutes les transactions <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <?php if($client->transactions->count() > 0): ?>
                    <ul class="divide-y divide-gray-200">
                        <?php $__currentLoopData = $client->transactions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="py-2">
                                <div class="flex justify-between">
                                    <span class="text-sm"><?php echo e($transaction->numero_transaction); ?></span>
                                    <span class="text-sm font-semibold <?php echo e($transaction->type == 'encaissement' ? 'text-green-600' : 'text-red-600'); ?>">
                                        <?php echo e($transaction->type == 'encaissement' ? '+' : '-'); ?> <?php echo e(number_format($transaction->montant, 0, ',', ' ')); ?> FCFA
                                    </span>
                                </div>
                                <div class="text-xs text-gray-500"><?php echo e($transaction->created_at->format('d/m/Y')); ?> - <?php echo e($transaction->libelle); ?></div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-sm text-gray-500 italic">Aucune transaction enregistrée</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Onglets pour les sections supplémentaires -->
    <div class="mt-8">
        <div x-data="{ activeTab: 'interactions' }" class="bg-white rounded-lg shadow-md">
            <div class="border-b">
                <nav class="flex -mb-px">
                    <button @click="activeTab = 'interactions'" :class="{ 'border-red-500 text-red-600': activeTab === 'interactions', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'interactions' }" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                        <i class="fas fa-comments mr-2"></i> Interactions
                    </button>
                    <button @click="activeTab = 'reclamations'" :class="{ 'border-red-500 text-red-600': activeTab === 'reclamations', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reclamations' }" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                        <i class="fas fa-exclamation-circle mr-2"></i> Réclamations
                    </button>
                    <button @click="activeTab = 'documents'" :class="{ 'border-red-500 text-red-600': activeTab === 'documents', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'documents' }" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                        <i class="fas fa-file-alt mr-2"></i> Documents
                    </button>
                    <button @click="activeTab = 'integrations'" :class="{ 'border-red-500 text-red-600': activeTab === 'integrations', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'integrations' }" class="py-4 px-6 font-medium border-b-2 focus:outline-none">
                        <i class="fas fa-plug mr-2"></i> Intégrations
                    </button>
                </nav>
            </div>
            
            <!-- Interactions -->
            <div x-show="activeTab === 'interactions'" class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Historique des interactions</h2>
                    <a href="<?php echo e(route('clients.interactions.create', ['client_id' => $client->id])); ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i> Nouvelle interaction
                    </a>
                </div>
                
                <?php if($client->interactions->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $client->interactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $interaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border rounded-lg p-4 <?php echo e($interaction->suivi_necessaire && !$interaction->date_suivi ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200'); ?>">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-semibold"><?php echo e(ucfirst($interaction->type_interaction)); ?></h3>
                                        <p class="text-sm text-gray-600"><?php echo e($interaction->date_interaction->format('d/m/Y H:i')); ?> - Par <?php echo e($interaction->user->nom); ?> <?php echo e($interaction->user->prenom); ?></p>
                                    </div>
                                    <div>
                                        <?php if($interaction->suivi_necessaire && !$interaction->date_suivi): ?>
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">Suivi requis</span>
                                        <?php elseif($interaction->suivi_necessaire && $interaction->date_suivi): ?>
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Suivi effectué</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <p class="mt-2"><?php echo e($interaction->description); ?></p>
                                <?php if($interaction->resultat): ?>
                                    <p class="mt-1 text-sm"><span class="font-semibold">Résultat:</span> <?php echo e($interaction->resultat); ?></p>
                                <?php endif; ?>
                                
                                <?php if($interaction->suivi_necessaire && !$interaction->date_suivi): ?>
                                    <div class="mt-3 flex justify-end">
                                        <form action="<?php echo e(route('clients.interactions.mark-as-followed-up', $interaction)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-1 px-3 rounded">
                                                <i class="fas fa-check mr-1"></i> Marquer comme suivi
                                            </button>
                                        </form>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 italic">Aucune interaction enregistrée</p>
                <?php endif; ?>
            </div>
            
            <!-- Réclamations -->
            <div x-show="activeTab === 'reclamations'" class="p-6" style="display: none;">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Réclamations</h2>
                    <a href="<?php echo e(route('clients.reclamations.create', ['client_id' => $client->id])); ?>" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i> Nouvelle réclamation
                    </a>
                </div>
                
                <?php if($client->reclamations->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $client->reclamations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reclamation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($reclamation->created_at->format('d/m/Y')); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(ucfirst($reclamation->type_reclamation)); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate"><?php echo e($reclamation->description); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php echo e($reclamation->statut == 'ouverte' ? 'bg-red-100 text-red-800' : ''); ?>

                                                <?php echo e($reclamation->statut == 'en_cours' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                                <?php echo e($reclamation->statut == 'resolue' ? 'bg-green-100 text-green-800' : ''); ?>

                                                <?php echo e($reclamation->statut == 'fermee' ? 'bg-gray-100 text-gray-800' : ''); ?>

                                            ">
                                                <?php echo e(ucfirst(str_replace('_', ' ', $reclamation->statut))); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($reclamation->agent ? $reclamation->agent->nom . ' ' . $reclamation->agent->prenom : 'Non assigné'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="<?php echo e(route('clients.reclamations.show', $reclamation)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo e(route('clients.reclamations.edit', $reclamation)); ?>" class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 italic">Aucune réclamation enregistrée</p>
                <?php endif; ?>
            </div>
            
            <!-- Documents -->
            <div x-show="activeTab === 'documents'" class="p-6" style="display: none;">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Documents</h2>
                    <form action="<?php echo e(route('clients.update', $client)); ?>" method="POST" enctype="multipart/form-data" class="flex items-center">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        <input type="file" name="documents[]" id="add_documents" multiple class="hidden" onchange="this.form.submit()">
                        <label for="add_documents" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded cursor-pointer">
                            <i class="fas fa-upload mr-2"></i> Ajouter des documents
                        </label>
                    </form>
                </div>
                
                <?php if($client->documents->count() > 0): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php $__currentLoopData = $client->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border rounded-lg p-4 flex flex-col">
                                <div class="flex justify-between items-start">
                                    <div class="flex items-center">
                                        <span class="text-2xl mr-3 <?php echo e($document->format == 'pdf' ? 'text-red-500' : 'text-blue-500'); ?>">
                                            <i class="fas <?php echo e($document->format == 'pdf' ? 'fa-file-pdf' : 'fa-file-image'); ?>"></i>
                                        </span>
                                        <div>
                                            <h3 class="font-semibold truncate max-w-xs"><?php echo e($document->nom); ?></h3>
                                            <p class="text-xs text-gray-500"><?php echo e($document->created_at->format('d/m/Y')); ?> - <?php echo e(number_format($document->taille / 1024, 2)); ?> KB</p>
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
                                <div class="mt-2">
                                    <p class="text-sm text-gray-600 truncate"><?php echo e($document->description ?: 'Aucune description'); ?></p>
                                </div>
                                <div class="mt-3 text-center">
                                    <?php if(in_array($document->format, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                        <a href="<?php echo e(route('documents.show', $document)); ?>" class="text-sm text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye mr-1"></i> Aperçu
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 italic">Aucun document disponible</p>
                <?php endif; ?>
            </div>
            
            <!-- Intégrations -->
            <div x-show="activeTab === 'integrations'" class="p-6" style="display: none;">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-700">Intégrations</h2>
                    <a href="<?php echo e(route('clients.integrations.create', $client)); ?>" class="bg-red-600 hover:bg-red-700 text-white text-sm font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i> Nouvelle intégration
                    </a>
                </div>
                
                <?php if($client->integrations->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Système Externe</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Externe</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dernière Synchronisation</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php $__currentLoopData = $client->integrations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $integration): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($integration->external_system); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e(ucfirst($integration->integration_type)); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo e($integration->external_id ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($integration->sync_status == 'synced'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    Synchronisé
                                                </span>
                                            <?php elseif($integration->sync_status == 'pending'): ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    En attente
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    Échoué
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php echo e($integration->last_sync_at ? $integration->last_sync_at->format('d/m/Y H:i') : 'Jamais'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="<?php echo e(route('clients.integrations.edit', $integration)); ?>" class="text-blue-600 hover:text-blue-900 mr-3">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('clients.integrations.destroy', $integration)); ?>" method="POST" class="inline-block">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette intégration ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500 italic">Aucune intégration configurée</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\clients\show.blade.php ENDPATH**/ ?>