@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nouveau Fournisseur</h1>
        <a href="{{ route('fournisseurs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('fournisseurs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

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
                            <option value="{{ $societe->id }}" {{ old('societe_id') == $societe->id ? 'selected' : '' }}>
                                {{ $societe->nom }}
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
                    <input type="text" name="raison_sociale" id="raison_sociale" value="{{ old('raison_sociale') }}" required 
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
                        <option value="personne_physique" {{ old('type') == 'personne_physique' ? 'selected' : '' }}>Personne physique</option>
                        <option value="entreprise" {{ old('type') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                        <option value="institution" {{ old('type') == 'institution' ? 'selected' : '' }}>Institution</option>
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
                        <option value="transport" {{ old('activite') == 'transport' ? 'selected' : '' }}>Transport</option>
                        <option value="logistique" {{ old('activite') == 'logistique' ? 'selected' : '' }}>Logistique</option>
                        <option value="matieres_premieres" {{ old('activite') == 'matieres_premieres' ? 'selected' : '' }}>Matières premières</option>
                        <option value="services" {{ old('activite') == 'services' ? 'selected' : '' }}>Services</option>
                        <option value="autre" {{ old('activite') == 'autre' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('activite')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIU / RCCM / CNSS -->
                <div>
                    <label for="niu" class="block text-sm font-medium text-gray-700 mb-1">NIU</label>
                    <input type="text" name="niu" id="niu" value="{{ old('niu') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('niu') border-red-500 @enderror">
                    @error('niu')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rccm" class="block text-sm font-medium text-gray-700 mb-1">RCCM</label>
                    <input type="text" name="rccm" id="rccm" value="{{ old('rccm') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('rccm') border-red-500 @enderror">
                    @error('rccm')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cnss" class="block text-sm font-medium text-gray-700 mb-1">CNSS</label>
                    <input type="text" name="cnss" id="cnss" value="{{ old('cnss') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('cnss') border-red-500 @enderror">
                    @error('cnss')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Adresse -->
                <div class="md:col-span-2">
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse <span class="text-red-600">*</span></label>
                    <textarea name="adresse" id="adresse" rows="2" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('adresse') border-red-500 @enderror">{{ old('adresse') }}</textarea>
                    @error('adresse')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pays / Ville -->
                <div>
                    <label for="pays" class="block text-sm font-medium text-gray-700 mb-1">Pays <span class="text-red-600">*</span></label>
                    <input type="text" name="pays" id="pays" value="{{ old('pays') }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('pays') border-red-500 @enderror">
                    @error('pays')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville <span class="text-red-600">*</span></label>
                    <input type="text" name="ville" id="ville" value="{{ old('ville') }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('ville') border-red-500 @enderror">
                    @error('ville')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contact -->
                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone <span class="text-red-600">*</span></label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" required 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('telephone') border-red-500 @enderror">
                    @error('telephone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('whatsapp') border-red-500 @enderror">
                    @error('whatsapp')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="site_web" class="block text-sm font-medium text-gray-700 mb-1">Site web</label>
                    <input type="url" name="site_web" id="site_web" value="{{ old('site_web') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('site_web') border-red-500 @enderror">
                    @error('site_web')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_principal" class="block text-sm font-medium text-gray-700 mb-1">Nom du contact principal <span class="text-red-600">*</span></label>
                    <input type="text" name="contact_principal" id="contact_principal" value="{{ old('contact_principal') }}" required 
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
                    <input type="text" name="banque" id="banque" value="{{ old('banque') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('banque') border-red-500 @enderror">
                    @error('banque')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="numero_compte" class="block text-sm font-medium text-gray-700 mb-1">N° de compte / IBAN</label>
                    <input type="text" name="numero_compte" id="numero_compte" value="{{ old('numero_compte') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('numero_compte') border-red-500 @enderror">
                    @error('numero_compte')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="devise" class="block text-sm font-medium text-gray-700 mb-1">Devise préférée</label>
                    <select name="devise" id="devise" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('devise') border-red-500 @enderror">
                        <option value="">Sélectionner une devise</option>
                        <option value="XAF" {{ old('devise') == 'XAF' ? 'selected' : '' }}>XAF (FCFA)</option>
                        <option value="EUR" {{ old('devise') == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                        <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }}>USD (Dollar américain)</option>
                    </select>
                    @error('devise')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Conditions de règlement -->
                <div>
                    <label for="condition_reglement" class="block text-sm font-medium text-gray-700 mb-1">Conditions de règlement</label>
                    <select name="condition_reglement" id="condition_reglement" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('condition_reglement') border-red-500 @enderror">
                        <option value="">Sélectionner une condition</option>
                        <option value="comptant" {{ old('condition_reglement') == 'comptant' ? 'selected' : '' }}>Comptant</option>
                        <option value="credit" {{ old('condition_reglement') == 'credit' ? 'selected' : '' }}>À crédit</option>
                    </select>
                    @error('condition_reglement')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="delai_paiement" class="block text-sm font-medium text-gray-700 mb-1">Délai de paiement (jours)</label>
                    <input type="number" name="delai_paiement" id="delai_paiement" value="{{ old('delai_paiement') }}" min="0" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('delai_paiement') border-red-500 @enderror">
                    @error('delai_paiement')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="plafond_credit" class="block text-sm font-medium text-gray-700 mb-1">Plafond de crédit autorisé</label>
                    <input type="number" name="plafond_credit" id="plafond_credit" value="{{ old('plafond_credit') }}" min="0" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('plafond_credit') border-red-500 @enderror">
                    @error('plafond_credit')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Section: Documents administratifs -->
                <div class="md:col-span-2 mt-6">
                    <h2 class="text-lg font-semibold text-gray-700 border-b pb-2 mb-4">Documents administratifs</h2>
                </div>

                <div>
                    <label for="date_debut_relation" class="block text-sm font-medium text-gray-700 mb-1">Date de début de relation commerciale</label>
                    <input type="date" name="date_debut_relation" id="date_debut_relation" value="{{ old('date_debut_relation') }}" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 @error('date_debut_relation') border-red-500 @enderror">
                    @error('date_debut_relation')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Documents</label>
                    
                    <div class="space-y-4">
                        <!-- Contrat cadre -->
                        <div class="border rounded-md p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contrat cadre / Devis / CGV</label>
                            <input type="file" name="contrat_cadre" id="contrat_cadre" 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @error('contrat_cadre')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- RCCM -->
                        <div class="border rounded-md p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">RCCM</label>
                            <input type="file" name="rccm_document" id="rccm_document" 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @error('rccm_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Attestation fiscale -->
                        <div class="border rounded-md p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Attestation fiscale</label>
                            <input type="file" name="attestation_fiscale" id="attestation_fiscale" 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @error('attestation_fiscale')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Autre document -->
                        <div class="border rounded-md p-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Autre document</label>
                            <input type="file" name="autre_document" id="autre_document" 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                            @error('autre_document')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-end space-x-3">
                <button type="reset" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection