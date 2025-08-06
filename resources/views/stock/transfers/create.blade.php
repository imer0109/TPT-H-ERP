@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Nouveau Transfert de Stock</h3>
                </div>
                <form action="{{ route('stock.transfers.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_source_id">Dépôt Source</label>
                                    <select name="warehouse_source_id" id="warehouse_source_id" class="form-control @error('warehouse_source_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un dépôt</option>
                                        @foreach($warehouses as $id => $name)
                                        <option value="{{ $id }}" {{ old('warehouse_source_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_source_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="warehouse_destination_id">Dépôt Destination</label>
                                    <select name="warehouse_destination_id" id="warehouse_destination_id" class="form-control @error('warehouse_destination_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un dépôt</option>
                                        @foreach($warehouses as $id => $name)
                                        <option value="{{ $id }}" {{ old('warehouse_destination_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('warehouse_destination_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="product_id">Produit</label>
                                    <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $id => $name)
                                        <option value="{{ $id }}" {{ old('product_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('product_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quantite">Quantité</label>
                                    <input type="number" step="0.01" name="quantite" id="quantite" class="form-control @error('quantite') is-invalid @enderror" value="{{ old('quantite') }}" required>
                                    @error('quantite')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="unite">Unité</label>
                                    <input type="text" name="unite" id="unite" class="form-control @error('unite') is-invalid @enderror" value="{{ old('unite') }}" required>
                                    @error('unite')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="justificatif">Justificatif</label>
                                    <input type="file" name="justificatif" id="justificatif" class="form-control-file @error('justificatif') is-invalid @enderror">
                                    @error('justificatif')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="notes">Notes</label>
                                    <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                    @error('notes')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Créer le transfert</button>
                        <a href="{{ route('stock.transfers.index') }}" class="btn btn-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection