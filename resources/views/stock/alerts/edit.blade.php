@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Modifier l'Alerte de Stock</h2>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('stock.alerts.update', $alert->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="product_id">Produit</label>
                            <select name="product_id" id="product_id" class="form-control @error('product_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $alert->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->reference }})
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="warehouse_id">Entrepôt</label>
                            <select name="warehouse_id" id="warehouse_id" class="form-control @error('warehouse_id') is-invalid @enderror" required>
                                <option value="">Sélectionnez un entrepôt</option>
                                @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $alert->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="minimum_threshold">Seuil Minimum</label>
                            <input type="number" name="minimum_threshold" id="minimum_threshold" 
                                   class="form-control @error('minimum_threshold') is-invalid @enderror" 
                                   value="{{ old('minimum_threshold', $alert->minimum_threshold) }}" required min="0" step="0.01">
                            @error('minimum_threshold')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="security_threshold">Seuil de Sécurité</label>
                            <input type="number" name="security_threshold" id="security_threshold" 
                                   class="form-control @error('security_threshold') is-invalid @enderror" 
                                   value="{{ old('security_threshold', $alert->security_threshold) }}" required min="0" step="0.01">
                            @error('security_threshold')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" 
                                       {{ old('is_active', $alert->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Activer l'alerte</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="email_notifications" name="email_notifications" 
                                       {{ old('email_notifications', $alert->email_notifications) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="email_notifications">Activer les notifications par email</label>
                            </div>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                Mettre à jour l'Alerte
                            </button>
                            <a href="{{ route('stock.alerts.index') }}" class="btn btn-secondary">Annuler</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#security_threshold').on('input', function() {
        const securityThreshold = parseFloat($(this).val()) || 0;
        const minimumThreshold = parseFloat($('#minimum_threshold').val()) || 0;
        
        if (minimumThreshold > securityThreshold) {
            $('#minimum_threshold').val(securityThreshold);
        }
    });

    $('#minimum_threshold').on('input', function() {
        const minimumThreshold = parseFloat($(this).val()) || 0;
        const securityThreshold = parseFloat($('#security_threshold').val()) || 0;
        
        if (minimumThreshold > securityThreshold) {
            $('#security_threshold').val(minimumThreshold);
        }
    });
});
</script>
@endpush
@endsection