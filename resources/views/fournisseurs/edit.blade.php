@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Modifier le Fournisseur</h1>
        <a href="{{ route('fournisseurs.show', $fournisseur->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour aux détails
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('fournisseurs.update', $fournisseur->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Section: Informations générales -->
                <div class="md:col-span-2">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations générales</h2>
                </div>

                <!-- Société / Agence de rattachement -->
                <div>
                    <label for="societe_id" class="block text-sm font-medium text-gray-700 mb-1">Société / Agence de rattachement <span class="text-red-600">*</span></label>
                    <select name="societe_id" id="societe_id" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('societe_id') border-red-500 @enderror">
                        <option value="">Sélectionner une société</option>
                        @foreach($societes as $societe)
                            <option value="{{ $societe->id }}" {{ old('societe_id', $fournisseur->societe_id) == $societe->id ? 'selected' : '' }}>
                                {{ $societe->raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                    @error('societe_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Raison sociale -->
                <div>
                    <label for="raison_sociale" class="block text-sm font-medium text-gray-700 mb-1">Raison sociale <span class="text-red-600">*</span></label>
                    <input type="text" name="raison_sociale" id="raison_sociale" value="{{ old('raison_sociale', $fournisseur->raison_sociale) }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('raison_sociale') border-red-500 @enderror">
                    @error('raison_sociale')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-600">*</span></label>
                    <select name="type" id="type" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('type') border-red-500 @enderror">
                        <option value="">Sélectionner un type</option>
                        <option value="personne_physique" {{ old('type', $fournisseur->type) == 'personne_physique' ? 'selected' : '' }}>Personne physique</option>
                        <option value="entreprise" {{ old('type', $fournisseur->type) == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                        <option value="institution" {{ old('type', $fournisseur->type) == 'institution' ? 'selected' : '' }}>Institution</option>
                    </select>
                    @error('type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Activité -->
                <div>
                    <label for="activite" class="block text-sm font-medium text-gray-700 mb-1">Activité <span class="text-red-600">*</span></label>
                    <select name="activite" id="activite" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('activite') border-red-500 @enderror">
                        <option value="">Sélectionner une activité</option>
                        <option value="transport" {{ old('activite', $fournisseur->activite) == 'transport' ? 'selected' : '' }}>Transport</option>
                        <option value="logistique" {{ old('activite', $fournisseur->activite) == 'logistique' ? 'selected' : '' }}>Logistique</option>
                        <option value="matieres_premieres" {{ old('activite', $fournisseur->activite) == 'matieres_premieres' ? 'selected' : '' }}>Matières premières</option>
                        <option value="services" {{ old('activite', $fournisseur->activite) == 'services' ? 'selected' : '' }}>Services</option>
                        <option value="autre" {{ old('activite', $fournisseur->activite) == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('activite')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut <span class="text-red-600">*</span></label>
                    <select name="statut" id="statut" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('statut') border-red-500 @enderror">
                        <option value="actif" {{ old('statut', $fournisseur->statut) == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut', $fournisseur->statut) == 'inactif' ? 'selected' : '' }}>Inactif</option>
                    </select>
                    @error('statut')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIU / RCCM / CNSS -->
                <div>
                    <label for="niu" class="block text-sm font-medium text-gray-700 mb-1">NIU</label>
                    <input type="text" name="niu" id="niu" value="{{ old('niu', $fournisseur->niu) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('niu') border-red-500 @enderror">
                    @error('niu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rccm" class="block text-sm font-medium text-gray-700 mb-1">RCCM</label>
                    <input type="text" name="rccm" id="rccm" value="{{ old('rccm', $fournisseur->rccm) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('rccm') border-red-500 @enderror">
                    @error('rccm')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cnss" class="block text-sm font-medium text-gray-700 mb-1">CNSS</label>
                    <input type="text" name="cnss" id="cnss" value="{{ old('cnss', $fournisseur->cnss) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('cnss') border-red-500 @enderror">
                    @error('cnss')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Adresse -->
                <div class="md:col-span-2">
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse <span class="text-red-600">*</span></label>
                    <textarea name="adresse" id="adresse" rows="2" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('adresse') border-red-500 @enderror">{{ old('adresse', $fournisseur->adresse) }}</textarea>
                    @error('adresse')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pays / Ville -->
                <div>
                    <label for="pays" class="block text-sm font-medium text-gray-700 mb-1">Pays <span class="text-red-600">*</span></label>
                    <input type="text" name="pays" id="pays" value="{{ old('pays', $fournisseur->pays) }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('pays') border-red-500 @enderror">
                    @error('pays')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-600">*</span></label>
                    <input type="text" name="ville" id="ville" value="{{ old('ville', $fournisseur->ville) }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('ville') border-red-500 @enderror">
                    @error('ville')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact -->
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone <span class="text-red-600">*</span></label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('telephone') border-red-500 @enderror">
                    @error('telephone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $fournisseur->whatsapp) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('whatsapp') border-red-500 @enderror">
                    @error('whatsapp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $fournisseur->email) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_web" class="block text-sm font-medium text-gray-700 mb-1">Site web</label>
                    <input type="url" name="site_web" id="site_web" value="{{ old('site_web', $fournisseur->site_web) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('site_web') border-red-500 @enderror">
                    @error('site_web')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_principal" class="block text-sm font-medium text-gray-700 mb-1">Nom du contact principal <span class="text-red-600">*</span></label>
                    <input type="text" name="contact_principal" id="contact_principal" value="{{ old('contact_principal', $fournisseur->contact_principal) }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('contact_principal') border-red-500 @enderror">
                    @error('contact_principal')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section: Informations bancaires -->
                <div class="md:col-span-2 mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Informations bancaires</h2>
                </div>

                <div>
                    <label for="banque" class="block text-sm font-medium text-gray-700 mb-1">Banque</label>
                    <input type="text" name="banque" id="banque" value="{{ old('banque', $fournisseur->banque) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('banque') border-red-500 @enderror">
                    @error('banque')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="numero_compte" class="block text-sm font-medium text-gray-700 mb-1">N° de compte / IBAN</label>
                    <input type="text" name="numero_compte" id="numero_compte" value="{{ old('numero_compte', $fournisseur->numero_compte) }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('numero_compte') border-red-500 @enderror">
                    @error('numero_compte')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section: Conditions commerciales -->
                <div class="md:col-span-2 mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Conditions commerciales</h2>
                </div>

                <div>
                    <label for="devise" class="block text-sm font-medium text-gray-700 mb-1">Devise par défaut</label>
                    <select name="devise" id="devise" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('devise') border-red-500 @enderror">
                        <option value="">Sélectionner une devise</option>
                        <option value="XOF" {{ old('devise', $fournisseur->devise) == 'XOF' ? 'selected' : '' }}>Franc CFA (XOF)</option>
                        <option value="EUR" {{ old('devise', $fournisseur->devise) == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                        <option value="USD" {{ old('devise', $fournisseur->devise) == 'USD' ? 'selected' : '' }}>Dollar US (USD)</option>
                    </select>
                    @error('devise')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="condition_reglement" class="block text-sm font-medium text-gray-700 mb-1">Condition de règlement</label>
                    <select name="condition_reglement" id="condition_reglement" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('condition_reglement') border-red-500 @enderror">
                        <option value="">Sélectionner une condition</option>
                        <option value="comptant" {{ old('condition_reglement', $fournisseur->condition_reglement) == 'comptant' ? 'selected' : '' }}>Comptant</option>
                        <option value="credit" {{ old('condition_reglement', $fournisseur->condition_reglement) == 'credit' ? 'selected' : '' }}>Crédit</option>
                    </select>
                    @error('condition_reglement')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="delai_paiement" class="block text-sm font-medium text-gray-700 mb-1">Délai de paiement (jours)</label>
                    <input type="number" name="delai_paiement" id="delai_paiement" value="{{ old('delai_paiement', $fournisseur->delai_paiement) }}" min="0" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('delai_paiement') border-red-500 @enderror">
                    @error('delai_paiement')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="plafond_credit" class="block text-sm font-medium text-gray-700 mb-1">Plafond de crédit</label>
                    <input type="number" name="plafond_credit" id="plafond_credit" value="{{ old('plafond_credit', $fournisseur->plafond_credit) }}" step="0.01" min="0" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('plafond_credit') border-red-500 @enderror">
                    @error('plafond_credit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="date_debut_relation" class="block text-sm font-medium text-gray-700 mb-1">Date de début de relation</label>
                    <input type="date" name="date_debut_relation" id="date_debut_relation" value="{{ old('date_debut_relation', $fournisseur->date_debut_relation ? $fournisseur->date_debut_relation->format('Y-m-d') : '') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('date_debut_relation') border-red-500 @enderror">
                    @error('date_debut_relation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section: Documents -->
                <div class="md:col-span-2 mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Documents</h2>
                </div>

                <div>
                    <label for="contrat_cadre" class="block text-sm font-medium text-gray-700 mb-1">Contrat cadre</label>
                    <input type="file" name="contrat_cadre" id="contrat_cadre" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('contrat_cadre') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG (max 10MB)</p>
                    @if($fournisseur->documents->where('type_document', 'Contrat Cadre')->first())
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i> 
                            Document existant: <a href="{{ Storage::url($fournisseur->documents->where('type_document', 'Contrat Cadre')->first()->chemin_fichier) }}" target="_blank" class="underline">Voir le document</a>
                        </p>
                    @endif
                    @error('contrat_cadre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rccm_document" class="block text-sm font-medium text-gray-700 mb-1">RCCM</label>
                    <input type="file" name="rccm_document" id="rccm_document" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('rccm_document') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG (max 10MB)</p>
                    @if($fournisseur->documents->where('type_document', 'RCCM')->first())
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i> 
                            Document existant: <a href="{{ Storage::url($fournisseur->documents->where('type_document', 'RCCM')->first()->chemin_fichier) }}" target="_blank" class="underline">Voir le document</a>
                        </p>
                    @endif
                    @error('rccm_document')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="attestation_fiscale" class="block text-sm font-medium text-gray-700 mb-1">Attestation fiscale</label>
                    <input type="file" name="attestation_fiscale" id="attestation_fiscale" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('attestation_fiscale') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG (max 10MB)</p>
                    @if($fournisseur->documents->where('type_document', 'Attestation Fiscale')->first())
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i> 
                            Document existant: <a href="{{ Storage::url($fournisseur->documents->where('type_document', 'Attestation Fiscale')->first()->chemin_fichier) }}" target="_blank" class="underline">Voir le document</a>
                        </p>
                    @endif
                    @error('attestation_fiscale')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="autre_document" class="block text-sm font-medium text-gray-700 mb-1">Autre document</label>
                    <input type="file" name="autre_document" id="autre_document" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('autre_document') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">Formats acceptés: PDF, DOC, DOCX, JPG, JPEG, PNG (max 10MB)</p>
                    @if($fournisseur->documents->where('type_document', 'Autre Document')->first())
                        <p class="text-xs text-green-600 mt-1">
                            <i class="fas fa-check-circle mr-1"></i> 
                            Document existant: <a href="{{ Storage::url($fournisseur->documents->where('type_document', 'Autre Document')->first()->chemin_fichier) }}" target="_blank" class="underline">Voir le document</a>
                        </p>
                    @endif
                    @error('autre_document')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Boutons d'action -->
                <div class="md:col-span-2 flex justify-end space-x-3 mt-6">
                    <a href="{{ route('fournisseurs.show', $fournisseur->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-save mr-2"></i> Mettre à jour
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection