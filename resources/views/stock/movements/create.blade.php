@extends('layouts.app')

@section('title', 'Créer un Mouvement de Stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Créer un Mouvement de Stock</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.movements.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour à la liste
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    <form action="{{ route('stock.movements.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type de Mouvement*</label>
                                    <select name="type" id="type" class="form-control border py-2 @error('type') is-invalid @enderror" required>
                                        <option value="">Sélectionner un type</option>
                                        <option value="entree" {{ old('type') == 'entree' ? 'selected' : '' }}>Entrée</option>
                                        <option value="sortie" {{ old('type') == 'sortie' ? 'selected' : '' }}>Sortie</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_id" class="py-2">Dépôt*</label>
                                    <select name="warehouse_id" id="warehouse_id" class="form-control border py-2 @error('warehouse_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un dépôt</option>
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ old('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_id">Produit*</label>
                                    <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $id => $name)
                                            <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="quantite">Quantité*</label>
                                    <input type="number" name="quantite" id="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite') }}" min="1" step="1" required>
                                    @error('quantite')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="prix_unitaire">Prix Unitaire*</label>
                                    <input type="number" name="prix_unitaire" id="prix_unitaire" class="form-control @error('prix_unitaire') is-invalid @enderror" value="{{ old('prix_unitaire') }}" min="0" step="0.01" required>
                                    @error('prix_unitaire')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="reference">Référence</label>
                                    <input type="text" name="reference" id="reference" class="form-control @error('reference') is-invalid @enderror" value="{{ old('reference') }}">
                                    @error('reference')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="motif">Motif</label>
                            <textarea name="motif" id="motif" class="form-control @error('motif') is-invalid @enderror" rows="3">{{ old('motif') }}</textarea>
                            @error('motif')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                            <a href="{{ route('stock.movements.index') }}" class="btn btn-secondary">Annuler</a>
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
        // Calcul automatique du montant total
        $('#quantite, #prix_unitaire').on('input', function() {
            var quantite = parseFloat($('#quantite').val()) || 0;
            var prix = parseFloat($('#prix_unitaire').val()) || 0;
            var total = quantite * prix;
            $('#montant_total').val(total.toFixed(2));
        });
    });
</script>
@endsection