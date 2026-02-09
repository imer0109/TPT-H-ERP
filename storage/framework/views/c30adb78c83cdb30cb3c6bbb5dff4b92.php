<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Fiche Fournisseur</h1>
        <div class="flex space-x-2">
            <a href="<?php echo e(route('fournisseurs.index')); ?>" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
            </a>
            <a href="<?php echo e(route('fournisseurs.edit', $fournisseur->id)); ?>" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">
                <i class="fas fa-edit mr-2"></i> Modifier
            </a>
            <form action="<?php echo e(route('fournisseurs.destroy', $fournisseur->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-trash-alt mr-2"></i> Supprimer
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Informations générales -->
        <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations générales</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Code fournisseur</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->code_fournisseur); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Raison sociale</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->raison_sociale); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Type</p>
                    <p class="text-base font-medium">
                        <?php if($fournisseur->type == 'personne_physique'): ?>
                            Personne physique
                        <?php elseif($fournisseur->type == 'entreprise'): ?>
                            Entreprise
                        <?php elseif($fournisseur->type == 'institution'): ?>
                            Institution
                        <?php else: ?>
                            <?php echo e($fournisseur->type); ?>

                        <?php endif; ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Activité</p>
                    <p class="text-base font-medium">
                        <?php if($fournisseur->activite == 'transport'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-truck mr-1"></i> Transport
                            </span>
                        <?php elseif($fournisseur->activite == 'logistique'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-warehouse mr-1"></i> Logistique
                            </span>
                        <?php elseif($fournisseur->activite == 'matieres_premieres'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-boxes mr-1"></i> Matières premières
                            </span>
                        <?php elseif($fournisseur->activite == 'services'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-concierge-bell mr-1"></i> Services
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-briefcase mr-1"></i> Autre
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Société / Agence</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->societe->raison_sociale ?? '-'); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Statut</p>
                    <p class="text-base font-medium">
                        <?php if($fournisseur->statut == 'actif'): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Actif
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Inactif
                            </span>
                        <?php endif; ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">NIU</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->niu ?: '-'); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">RCCM</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->rccm ?: '-'); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">CNSS</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->cnss ?: '-'); ?></p>
                </div>
                
                <div class="md:col-span-2">
                    <p class="text-sm font-medium text-gray-500">Adresse</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->adresse); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Pays</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->pays); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Ville</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->ville); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Contact -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Contact</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Contact principal</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->contact_principal); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Téléphone</p>
                    <p class="text-base font-medium">
                        <a href="tel:<?php echo e($fournisseur->telephone); ?>" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-phone-alt mr-1"></i> <?php echo e($fournisseur->telephone); ?>

                        </a>
                    </p>
                </div>
                
                <?php if($fournisseur->whatsapp): ?>
                <div>
                    <p class="text-sm font-medium text-gray-500">WhatsApp</p>
                    <p class="text-base font-medium">
                        <a href="https://wa.me/<?php echo e(preg_replace('/[^0-9]/', '', $fournisseur->whatsapp)); ?>" target="_blank" class="text-green-600 hover:text-green-800">
                            <i class="fab fa-whatsapp mr-1"></i> <?php echo e($fournisseur->whatsapp); ?>

                        </a>
                    </p>
                </div>
                <?php endif; ?>
                
                <?php if($fournisseur->email): ?>
                <div>
                    <p class="text-sm font-medium text-gray-500">Email</p>
                    <p class="text-base font-medium">
                        <a href="mailto:<?php echo e($fournisseur->email); ?>" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-envelope mr-1"></i> <?php echo e($fournisseur->email); ?>

                        </a>
                    </p>
                </div>
                <?php endif; ?>
                
                <?php if($fournisseur->site_web): ?>
                <div>
                    <p class="text-sm font-medium text-gray-500">Site web</p>
                    <p class="text-base font-medium">
                        <a href="<?php echo e($fournisseur->site_web); ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-globe mr-1"></i> <?php echo e($fournisseur->site_web); ?>

                        </a>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Informations bancaires -->
        <div class="bg-white rounded-lg shadow-md p-6 md:col-span-2">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations bancaires</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm font-medium text-gray-500">Banque</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->banque ?: '-'); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">N° de compte / IBAN</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->numero_compte ?: '-'); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Devise par défaut</p>
                    <p class="text-base font-medium">
                        <?php if($fournisseur->devise == 'XOF'): ?>
                            Franc CFA (XOF)
                        <?php elseif($fournisseur->devise == 'EUR'): ?>
                            Euro (EUR)
                        <?php elseif($fournisseur->devise == 'USD'): ?>
                            Dollar US (USD)
                        <?php else: ?>
                            <?php echo e($fournisseur->devise ?: '-'); ?>

                        <?php endif; ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Condition de règlement</p>
                    <p class="text-base font-medium">
                        <?php if($fournisseur->condition_reglement == 'comptant'): ?>
                            Comptant
                        <?php elseif($fournisseur->condition_reglement == 'credit'): ?>
                            Crédit
                        <?php else: ?>
                            <?php echo e($fournisseur->condition_reglement ?: '-'); ?>

                        <?php endif; ?>
                    </p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Délai de paiement</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->delai_paiement ? $fournisseur->delai_paiement . ' jours' : '-'); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Plafond de crédit</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->plafond_credit ? number_format($fournisseur->plafond_credit, 2) . ' ' . ($fournisseur->devise ?: 'XOF') : '-'); ?></p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">Date de début de relation</p>
                    <p class="text-base font-medium"><?php echo e($fournisseur->date_debut_relation ? $fournisseur->date_debut_relation->format('d/m/Y') : '-'); ?></p>
                </div>
            </div>
        </div>
        
        <!-- Documents -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Documents</h2>
            
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $fournisseur->documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-file mr-2 text-gray-500"></i>
                            <div>
                                <p class="text-sm font-medium"><?php echo e($document->type_document); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($document->nom_fichier); ?></p>
                            </div>
                        </div>
                        <a href="<?php echo e(Storage::url($document->chemin_fichier)); ?>" target="_blank" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-sm text-gray-500 text-center py-4">Aucun document disponible</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Évaluations -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Évaluations</h2>
                <a href="<?php echo e(route('fournisseurs.ratings.create', $fournisseur)); ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i> Nouvelle évaluation
                </a>
            </div>
            
            <?php if($fournisseur->ratings->count() > 0): ?>
                <div class="mb-4">
                    <div class="flex items-center">
                        <div class="text-3xl font-bold mr-4"><?php echo e(number_format($fournisseur->average_rating, 1)); ?></div>
                        <div>
                            <div class="flex">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= round($fournisseur->average_rating)): ?>
                                        <i class="fas fa-star text-yellow-400"></i>
                                    <?php else: ?>
                                        <i class="far fa-star text-gray-300"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="text-sm text-gray-500"><?php echo e($fournisseur->rating_count); ?> évaluation(s)</div>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <?php $__currentLoopData = $fournisseur->ratings->sortByDesc('created_at')->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex">
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $rating->overall_score): ?>
                                                <i class="fas fa-star text-yellow-400"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-gray-300"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">Évalué par <?php echo e($rating->evaluator->name ?? 'Système'); ?> le <?php echo e($rating->evaluation_date->format('d/m/Y')); ?></div>
                                </div>
                                <div class="flex space-x-2">
                                    <a href="<?php echo e(route('fournisseurs.ratings.edit', $rating)); ?>" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="<?php echo e(route('fournisseurs.ratings.destroy', $rating)); ?>" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette évaluation ?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php if($rating->comments): ?>
                                <div class="mt-2 text-sm text-gray-700">
                                    <?php echo e($rating->comments); ?>

                                </div>
                            <?php endif; ?>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 mt-3 text-xs">
                                <div>
                                    <div class="text-gray-500">Qualité</div>
                                    <div class="font-medium"><?php echo e($rating->quality_rating); ?>/5</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Livraison</div>
                                    <div class="font-medium"><?php echo e($rating->delivery_rating); ?>/5</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Réactivité</div>
                                    <div class="font-medium"><?php echo e($rating->responsiveness_rating); ?>/5</div>
                                </div>
                                <div>
                                    <div class="text-gray-500">Prix</div>
                                    <div class="font-medium"><?php echo e($rating->pricing_rating); ?>/5</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-star text-4xl mb-2"></i>
                    <p>Aucune évaluation pour ce fournisseur</p>
                    <a href="<?php echo e(route('fournisseurs.ratings.create', $fournisseur)); ?>" class="mt-2 inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                        Ajouter une évaluation
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Contrats -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-700">Contrats</h2>
                <a href="<?php echo e(route('fournisseurs.contracts.create', ['fournisseur_id' => $fournisseur->id])); ?>" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                    <i class="fas fa-plus mr-2"></i> Nouveau contrat
                </a>
            </div>
            
            <?php if($fournisseur->contracts->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $fournisseur->contracts->sortByDesc('created_at')->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border rounded-lg p-4 <?php echo e($contract->isExpiringSoon() ? 'border-yellow-300 bg-yellow-50' : ''); ?> <?php echo e($contract->isExpired() ? 'border-red-300 bg-red-50' : ''); ?>">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center">
                                        <h3 class="text-md font-medium text-gray-900">
                                            <a href="<?php echo e(route('fournisseurs.contracts.show', $contract)); ?>" class="text-blue-600 hover:text-blue-800">
                                                <?php echo e($contract->contract_number); ?>

                                            </a>
                                        </h3>
                                        <div class="ml-2">
                                            <?php echo $contract->status_badge; ?>

                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-500 mt-1"><?php echo e($contract->contract_type); ?></p>
                                    <p class="text-sm text-gray-700 mt-1"><?php echo e(Str::limit($contract->description, 100)); ?></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        <?php echo e($contract->start_date->format('d/m/Y')); ?> - <?php echo e($contract->end_date->format('d/m/Y')); ?>

                                    </p>
                                    <?php if($contract->isExpiringSoon()): ?>
                                        <p class="text-xs text-yellow-600 mt-1">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Expire dans <?php echo e($contract->days_until_expiry); ?> jours
                                        </p>
                                    <?php endif; ?>
                                    <?php if($contract->isExpired()): ?>
                                        <p class="text-xs text-red-600 mt-1">
                                            <i class="fas fa-exclamation-circle mr-1"></i>
                                            Expiré
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if($contract->value): ?>
                                <div class="mt-2 text-sm text-gray-700">
                                    Valeur: <?php echo e(number_format($contract->value, 2, ',', ' ')); ?> <?php echo e($contract->currency); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php if($fournisseur->contracts->count() > 5): ?>
                        <div class="text-center mt-4">
                            <a href="<?php echo e(route('fournisseurs.contracts.index', ['fournisseur_id' => $fournisseur->id])); ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Voir tous les <?php echo e($fournisseur->contracts->count()); ?> contrats
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-file-contract text-4xl mb-2"></i>
                    <p>Aucun contrat pour ce fournisseur</p>
                    <a href="<?php echo e(route('fournisseurs.contracts.create', ['fournisseur_id' => $fournisseur->id])); ?>" class="mt-2 inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold py-2 px-4 rounded">
                        Ajouter un contrat
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Commandes, Livraisons, Paiements, Réclamations -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Historique des transactions</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Commandes -->
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-700">Commandes</h3>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-shopping-cart"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-center"><?php echo e($commandes->count()); ?></p>
                <p class="text-xs text-gray-500 text-center mt-1">dernières commandes</p>
                <?php if($commandes->count() > 0): ?>
                    <div class="mt-2 space-y-1">
                        <?php $__currentLoopData = $commandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-xs p-1 bg-gray-50 rounded">
                                <a href="<?php echo e(route('achats.orders.show', $commande->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                    <?php echo e($commande->numero_commande); ?> - <?php echo e(number_format($commande->montant_total, 2)); ?> XOF
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Livraisons -->
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-700">Livraisons</h3>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-truck"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-center"><?php echo e($livraisons->count()); ?></p>
                <p class="text-xs text-gray-500 text-center mt-1">dernières livraisons</p>
                <?php if($livraisons->count() > 0): ?>
                    <div class="mt-2 space-y-1">
                        <?php $__currentLoopData = $livraisons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $livraison): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-xs p-1 bg-gray-50 rounded">
                                <a href="<?php echo e(route('achats.deliveries.show', $livraison->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                    <?php echo e($livraison->numero_livraison); ?> - <?php echo e($livraison->quantite_recue); ?> articles
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Paiements -->
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-700">Paiements</h3>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-money-bill-wave"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-center"><?php echo e($paiements->count()); ?></p>
                <p class="text-xs text-gray-500 text-center mt-1">derniers paiements</p>
                <?php if($paiements->count() > 0): ?>
                    <div class="mt-2 space-y-1">
                        <?php $__currentLoopData = $paiements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paiement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-xs p-1 bg-gray-50 rounded">
                                <a href="<?php echo e(route('achats.payments.show', $paiement->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                    <?php echo e($paiement->numero_paiement); ?> - <?php echo e(number_format($paiement->montant, 2)); ?> XOF
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Réclamations -->
            <div class="border rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-700">Réclamations</h3>
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-exclamation-triangle"></i>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-bold text-center"><?php echo e($reclamations->count()); ?></p>
                <p class="text-xs text-gray-500 text-center mt-1">dernières réclamations</p>
                <?php if($reclamations->count() > 0): ?>
                    <div class="mt-2 space-y-1">
                        <?php $__currentLoopData = $reclamations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reclamation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="text-xs p-1 bg-gray-50 rounded">
                                <a href="<?php echo e(route('fournisseurs.issues.show', $reclamation->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                    <?php echo e($reclamation->numero_reclamation); ?> - <?php echo e(ucfirst($reclamation->statut)); ?>

                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Rémi\Desktop\TPT INTERNATIONAL\PROJET TPT-H ERP\TPT-H ERP\resources\views\fournisseurs\show.blade.php ENDPATH**/ ?>