@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nouvel Employé</h3>
                </div>
                <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <!-- Informations Personnelles -->
                            <div class="col-md-6">
                                <h4>Informations Personnelles</h4>
                                <div class="form-group">
                                    <label for="matricule">Matricule*</label>
                                    <input type="text" class="form-control @error('matricule') is-invalid @enderror" 
                                           id="matricule" name="matricule" value="{{ old('matricule') }}" required>
                                    @error('matricule')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nom">Nom*</label>
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom') }}" required>
                                    @error('nom')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="prenom">Prénom*</label>
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                    @error('prenom')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="date_naissance">Date de Naissance*</label>
                                    <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                           id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                                    @error('date_naissance')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="lieu_naissance">Lieu de Naissance</label>
                                    <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                           id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance') }}">
                                    @error('lieu_naissance')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nationalite">Nationalité</label>
                                    <input type="text" class="form-control @error('nationalite') is-invalid @enderror" 
                                           id="nationalite" name="nationalite" value="{{ old('nationalite') }}">
                                    @error('nationalite')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Informations Professionnelles -->
                            <div class="col-md-6">
                                <h4>Informations Professionnelles</h4>
                                <div class="form-group">
                                    <label for="position_id">Poste*</label>
                                    <select class="form-control @error('position_id') is-invalid @enderror" 
                                            id="position_id" name="position_id" required>
                                        <option value="">Sélectionner un poste</option>
                                        @foreach($positions as $position)
                                        <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>
                                            {{ $position->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('position_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email Professionnel*</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="telephone">Téléphone*</label>
                                    <input type="tel" class="form-control @error('telephone') is-invalid @enderror" 
                                           id="telephone" name="telephone" value="{{ old('telephone') }}" required>
                                    @error('telephone')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="date_embauche">Date d'Embauche*</label>
                                    <input type="date" class="form-control @error('date_embauche') is-invalid @enderror" 
                                           id="date_embauche" name="date_embauche" value="{{ old('date_embauche') }}" required>
                                    @error('date_embauche')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="supervisor_id">Superviseur</label>
                                    <select class="form-control @error('supervisor_id') is-invalid @enderror" 
                                            id="supervisor_id" name="supervisor_id">
                                        <option value="">Sélectionner un superviseur</option>
                                        @foreach($supervisors as $supervisor)
                                        <option value="{{ $supervisor->id }}" {{ old('supervisor_id') == $supervisor->id ? 'selected' : '' }}>
                                            {{ $supervisor->nom }} {{ $supervisor->prenom }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('supervisor_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('employees.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection