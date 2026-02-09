@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Nouveau Client</h1>
        <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded flex items-center">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la liste
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('clients.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Informations générales -->
                <div class="md:col-span-3">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations générales</h2>
                </div>

                <div>
                    <label for="company_id" class="block text-sm font-medium text-gray-700 mb-1">Société <span class="text-red-600">*</span></label>
                    <select name="company_id" id="company_id" required
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="">Sélectionner une société</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                    @error('company_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="agency_id" class="block text-sm font-medium text-gray-700 mb-1">Agence</label>
                    <select name="agency_id" id="agency_id"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="">Sélectionner une agence</option>
                        @foreach($agencies as $agency)
                            <option value="{{ $agency->id }}" {{ old('agency_id') == $agency->id ? 'selected' : '' }}>
                                {{ $agency->nom }} ({{ $agency->company->raison_sociale }})
                            </option>
                        @endforeach
                    </select>
                    @error('agency_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="type_client" class="block text-sm font-medium text-gray-700 mb-1">Type de client <span class="text-red-600">*</span></label>
                    <select name="type_client" id="type_client" required
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="">Sélectionner un type</option>
                        <option value="particulier" {{ old('type_client') == 'particulier' ? 'selected' : '' }}>Particulier</option>
                        <option value="entreprise" {{ old('type_client') == 'entreprise' ? 'selected' : '' }}>Entreprise</option>
                        <option value="administration" {{ old('type_client') == 'administration' ? 'selected' : '' }}>Administration</option>
                        <option value="distributeur" {{ old('type_client') == 'distributeur' ? 'selected' : '' }}>Distributeur</option>
                    </select>
                    @error('type_client')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="nom_raison_sociale" class="block text-sm font-medium text-gray-700 mb-1">Nom / Raison sociale <span class="text-red-600">*</span></label>
                    <input type="text" name="nom_raison_sociale" id="nom_raison_sociale" value="{{ old('nom_raison_sociale') }}" required
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('nom_raison_sociale')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                    <input type="text" name="adresse" id="adresse" value="{{ old('adresse') }}"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('adresse')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="ville" class="block text-sm font-medium text-gray-700 mb-1">Ville</label>
                    <input type="text" name="ville" id="ville" value="{{ old('ville') }}"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('ville')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="contact_principal" class="block text-sm font-medium text-gray-700 mb-1">Contact principal (entreprise)</label>
                    <input type="text" name="contact_principal" id="contact_principal" value="{{ old('contact_principal') }}"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('contact_principal')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700 mb-1">WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp') }}"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('whatsapp')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="canal_acquisition" class="block text-sm font-medium text-gray-700 mb-1">Canal d'acquisition</label>
                    <select name="canal_acquisition" id="canal_acquisition"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="">Sélectionner un canal</option>
                        <option value="commerce_direct" {{ old('canal_acquisition') == 'commerce_direct' ? 'selected' : '' }}>Commerce direct</option>
                        <option value="web" {{ old('canal_acquisition') == 'web' ? 'selected' : '' }}>Web</option>
                        <option value="recommande" {{ old('canal_acquisition') == 'recommande' ? 'selected' : '' }}>Recommandé</option>
                        <option value="reseaux_sociaux" {{ old('canal_acquisition') == 'reseaux_sociaux' ? 'selected' : '' }}>Réseaux sociaux</option>
                        <option value="evenement" {{ old('canal_acquisition') == 'evenement' ? 'selected' : '' }}>Événement</option>
                    </select>
                    @error('canal_acquisition')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Coordonnées -->
                <div class="md:col-span-3 mt-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Coordonnées</h2>
                </div>

                <div>
                    <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone <span class="text-red-600">*</span></label>
                    <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" required
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('telephone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="site_web" class="block text-sm font-medium text-gray-700 mb-1">Site web</label>
                    <input type="url" name="site_web" id="site_web" value="{{ old('site_web') }}"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('site_web')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Informations financières -->
                <div class="md:col-span-3 mt-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Informations financières</h2>
                </div>

                <div>
                    <label for="type_relation" class="block text-sm font-medium text-gray-700 mb-1">Type de relation</label>
                    <select name="type_relation" id="type_relation"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="">Sélectionner un type</option>
                        <option value="comptant" {{ old('type_relation') == 'comptant' ? 'selected' : '' }}>Comptant</option>
                        <option value="credit" {{ old('type_relation') == 'credit' ? 'selected' : '' }}>Crédit</option>
                        <option value="mixte" {{ old('type_relation') == 'mixte' ? 'selected' : '' }}>Mixte</option>
                    </select>
                    @error('type_relation')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="delai_paiement" class="block text-sm font-medium text-gray-700 mb-1">Délai de paiement (jours)</label>
                    <input type="number" name="delai_paiement" id="delai_paiement" value="{{ old('delai_paiement') }}"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('delai_paiement')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="plafond_credit" class="block text-sm font-medium text-gray-700 mb-1">Plafond de crédit (FCFA)</label>
                    <input type="number" name="plafond_credit" id="plafond_credit" value="{{ old('plafond_credit') }}"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    @error('plafond_credit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="mode_paiement_prefere" class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement préféré</label>
                    <select name="mode_paiement_prefere" id="mode_paiement_prefere"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="">Sélectionner un mode</option>
                        <option value="especes" {{ old('mode_paiement_prefere') == 'especes' ? 'selected' : '' }}>Espèces</option>
                        <option value="cheque" {{ old('mode_paiement_prefere') == 'cheque' ? 'selected' : '' }}>Chèque</option>
                        <option value="virement" {{ old('mode_paiement_prefere') == 'virement' ? 'selected' : '' }}>Virement</option>
                        <option value="carte_bancaire" {{ old('mode_paiement_prefere') == 'carte_bancaire' ? 'selected' : '' }}>Carte bancaire</option>
                        <option value="mobile_money" {{ old('mode_paiement_prefere') == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                    </select>
                    @error('mode_paiement_prefere')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Autres informations -->
                <div class="md:col-span-3 mt-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Autres informations</h2>
                </div>

                <div>
                    <label for="referent_commercial_id" class="block text-sm font-medium text-gray-700 mb-1">Référent commercial</label>
                    <select name="referent_commercial_id" id="referent_commercial_id"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="">Sélectionner un référent</option>
                        @foreach($commerciaux as $commercial)
                            <option value="{{ $commercial->id }}" {{ old('referent_commercial_id') == $commercial->id ? 'selected' : '' }}>
                                {{ $commercial->nom }} {{ $commercial->prenom }}
                            </option>
                        @endforeach
                    </select>
                    @error('referent_commercial_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <select name="categorie" id="categorie"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="">Sélectionner une catégorie</option>
                        <option value="or" {{ old('categorie') == 'or' ? 'selected' : '' }}>Or - Premium</option>
                        <option value="argent" {{ old('categorie') == 'argent' ? 'selected' : '' }}>Argent - Standard</option>
                        <option value="bronze" {{ old('categorie') == 'bronze' ? 'selected' : '' }}>Bronze - Occasionnel</option>
                    </select>
                    @error('categorie')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                    <select name="statut" id="statut"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                        <option value="actif" {{ old('statut', 'actif') == 'actif' ? 'selected' : '' }}>Actif</option>
                        <option value="inactif" {{ old('statut') == 'inactif' ? 'selected' : '' }}>Inactif</option>
                        <option value="suspendu" {{ old('statut') == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                    </select>
                    @error('statut')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Documents -->
                <div class="md:col-span-3 mt-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-4 border-b pb-2">Documents</h2>
                </div>

                <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="document_identite" class="block text-sm font-medium text-gray-700 mb-1">Pièce d'identité</label>
                        <input type="file" name="documents[identite]" id="document_identite" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    </div>
                    <div>
                        <label for="document_registre" class="block text-sm font-medium text-gray-700 mb-1">Registre de commerce</label>
                        <input type="file" name="documents[registre]" id="document_registre" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    </div>
                    <div>
                        <label for="document_autre" class="block text-sm font-medium text-gray-700 mb-1">Autre document</label>
                        <input type="file" name="documents[autre]" id="document_autre" accept=".pdf,.jpg,.jpeg,.png"
                            class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">
                    </div>
                </div>

                <!-- Notes -->
                <div class="md:col-span-3 mt-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="4"
                        class="w-full border border-gray-300 rounded-md p-2 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-red-500">{{ old('notes') }}</textarea>
                </div>

            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <button type="reset" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-undo mr-2"></i> Réinitialiser
                </button>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded flex items-center">
                    <i class="fas fa-save mr-2"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
