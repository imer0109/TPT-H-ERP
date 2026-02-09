@extends('layouts.app')

@section('title', 'Créer une Interaction')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Créer une Nouvelle Interaction</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('clients.interactions.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="client_id">Client <span class="text-danger">*</span></label>
                                    <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
                                        <option value="">Sélectionnez un client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->nom_raison_sociale }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Utilisateur <span class="text-danger">*</span></label>
                                    <select name="user_id" id="user_id" class="form-control @error('user_id') is-invalid @enderror" required>
                                        <option value="">Sélectionnez un utilisateur</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id', Auth::id()) == $user->id ? 'selected' : '' }}>
                                                {{ $user->nom }} {{ $user->prenom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type_interaction">Type d'Interaction <span class="text-danger">*</span></label>
                                    <select name="type_interaction" id="type_interaction" class="form-control @error('type_interaction') is-invalid @enderror" required>
                                        <option value="">Sélectionnez un type</option>
                                        <option value="appel_telephonique" {{ old('type_interaction') == 'appel_telephonique' ? 'selected' : '' }}>
                                            Appel Téléphonique
                                        </option>
                                        <option value="visite_commerciale" {{ old('type_interaction') == 'visite_commerciale' ? 'selected' : '' }}>
                                            Visite Commerciale
                                        </option>
                                        <option value="email" {{ old('type_interaction') == 'email' ? 'selected' : '' }}>
                                            Email
                                        </option>
                                        <option value="message_whatsapp" {{ old('type_interaction') == 'message_whatsapp' ? 'selected' : '' }}>
                                            Message WhatsApp
                                        </option>
                                        <option value="reunion" {{ old('type_interaction') == 'reunion' ? 'selected' : '' }}>
                                            Réunion
                                        </option>
                                        <option value="autre" {{ old('type_interaction') == 'autre' ? 'selected' : '' }}>
                                            Autre
                                        </option>
                                    </select>
                                    @error('type_interaction')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_interaction">Date de l'Interaction <span class="text-danger">*</span></label>
                                    <input type="date" name="date_interaction" id="date_interaction" class="form-control @error('date_interaction') is-invalid @enderror" value="{{ old('date_interaction', date('Y-m-d')) }}" required>
                                    @error('date_interaction')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror" placeholder="Décrivez l'interaction..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="resultat">Résultat</label>
                            <textarea name="resultat" id="resultat" rows="2" class="form-control @error('resultat') is-invalid @enderror" placeholder="Résultat de l'interaction...">{{ old('resultat') }}</textarea>
                            @error('resultat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="checkbox" name="suivi_necessaire" id="suivi_necessaire" class="form-check-input @error('suivi_necessaire') is-invalid @enderror" value="1" {{ old('suivi_necessaire') ? 'checked' : '' }}>
                                        <label for="suivi_necessaire" class="form-check-label">Suivi nécessaire</label>
                                    </div>
                                    @error('suivi_necessaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date_suivi">Date de Suivi</label>
                                    <input type="date" name="date_suivi" id="date_suivi" class="form-control @error('date_suivi') is-invalid @enderror" value="{{ old('date_suivi') }}">
                                    @error('date_suivi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="campagne_id">ID de Campagne (facultatif)</label>
                            <input type="number" name="campagne_id" id="campagne_id" class="form-control @error('campagne_id') is-invalid @enderror" value="{{ old('campagne_id') }}">
                            <small class="form-text text-muted">Identifiant de la campagne marketing liée (si applicable)</small>
                            @error('campagne_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Créer l'Interaction
                            </button>
                            <a href="{{ route('clients.interactions.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Gérer la visibilité du champ date_suivi en fonction de suivi_necessaire
    $('#suivi_necessaire').change(function() {
        if ($(this).is(':checked')) {
            $('#date_suivi').prop('required', true);
        } else {
            $('#date_suivi').prop('required', false);
        }
    });
    
    // Initialiser l'état du champ date_suivi
    if ($('#suivi_necessaire').is(':checked')) {
        $('#date_suivi').prop('required', true);
    }
});
</script>
@endsection