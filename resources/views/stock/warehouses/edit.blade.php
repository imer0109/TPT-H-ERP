@extends('layouts.app')

@section('title', 'Modifier un Dépôt')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modifier le Dépôt: {{ $warehouse->nom }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.warehouses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.warehouses.update', $warehouse) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="code">Code*</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $warehouse->code) }}" required>
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="nom">Nom*</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom', $warehouse->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $warehouse->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="type">Type*</label>
                            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
                                <option value="">Sélectionner un type</option>
                                <option value="Principal" {{ old('type', $warehouse->type) == 'Principal' ? 'selected' : '' }}>Principal</option>
                                <option value="Secondaire" {{ old('type', $warehouse->type) == 'Secondaire' ? 'selected' : '' }}>Secondaire</option>
                                <option value="Transit" {{ old('type', $warehouse->type) == 'Transit' ? 'selected' : '' }}>Transit</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="adresse">Adresse</label>
                            <input type="text" class="form-control @error('adresse') is-invalid @enderror" id="adresse" name="adresse" value="{{ old('adresse', $warehouse->adresse) }}">
                            @error('adresse')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="actif" name="actif" value="1" {{ old('actif', $warehouse->actif) == '1' ? 'checked' : '' }}>
                                <label class="custom-control-label" for="actif">Actif</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                            <a href="{{ route('stock.warehouses.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection