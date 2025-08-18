@extends('layouts.app')

@section('title', 'Créer un Inventaire')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Créer un Nouvel Inventaire</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.inventories.index') }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <form action="{{ route('stock.inventories.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Information</h5>
                            <p>La création d'un inventaire va générer une liste de tous les produits actifs avec leur stock théorique dans le dépôt sélectionné.</p>
                            <p>Vous pourrez ensuite saisir les quantités réelles constatées lors de l'inventaire physique.</p>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_id">Dépôt <span class="text-danger">*</span></label>
                                    <select name="warehouse_id" id="warehouse_id" class="form-control @error('warehouse_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un dépôt</option>
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="date">Date de l'inventaire <span class="text-danger">*</span></label>
                                    <input type="date" name="date" id="date" class="form-control @error('date') is-invalid @enderror" value="{{ old('date', date('Y-m-d')) }}" required>
                                    @error('date')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Créer l'Inventaire
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection