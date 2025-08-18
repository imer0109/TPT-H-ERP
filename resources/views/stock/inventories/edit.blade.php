@extends('layouts.app')

@section('title', 'Modifier l\'Inventaire')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Modifier l'Inventaire #{{ $inventory->reference }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.inventories.show', $inventory) }}" class="btn btn-default btn-sm">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <form action="{{ route('stock.inventories.update', $inventory) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h5><i class="icon fas fa-info"></i> Information</h5>
                            <p>Veuillez saisir les quantités réelles constatées lors de l'inventaire physique.</p>
                            <p>Les différences seront automatiquement calculées.</p>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <strong>Référence:</strong> {{ $inventory->reference }}
                            </div>
                            <div class="col-md-4">
                                <strong>Date:</strong> {{ $inventory->date->format('d/m/Y') }}
                            </div>
                            <div class="col-md-4">
                                <strong>Dépôt:</strong> {{ $inventory->warehouse->nom }}
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $inventory->notes) }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Référence</th>
                                        <th>Stock Théorique</th>
                                        <th>Stock Réel</th>
                                        <th>Différence</th>
                                        <th>Notes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inventory->items as $item)
                                        <tr>
                                            <td>{{ $item->product->nom }}</td>
                                            <td>{{ $item->product->reference }}</td>
                                            <td class="text-right">{{ number_format($item->theoretical_quantity, 2) }}</td>
                                            <td>
                                                <input type="number" step="0.01" name="items[{{ $item->id }}][actual_quantity]" class="form-control text-right" value="{{ old('items.'.$item->id.'.actual_quantity', $item->actual_quantity) }}">
                                            </td>
                                            <td class="text-right">
                                                @if($item->difference !== null)
                                                    <span class="{{ $item->difference < 0 ? 'text-danger' : ($item->difference > 0 ? 'text-success' : '') }}">
                                                        {{ number_format($item->difference, 2) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <input type="text" name="items[{{ $item->id }}][notes]" class="form-control" value="{{ old('items.'.$item->id.'.notes', $item->notes) }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les Modifications
                        </button>
                        <a href="{{ route('stock.inventories.validate', $inventory) }}" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir valider cet inventaire? Cette action est irréversible.')">
                            <i class="fas fa-check"></i> Valider l'Inventaire
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Calculer automatiquement la différence lorsque le stock réel est saisi
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const actualInput = row.querySelector('input[name^="items["]');
            const theoreticalCell = row.querySelector('td:nth-child(3)');
            const differenceCell = row.querySelector('td:nth-child(5)');
            
            actualInput.addEventListener('input', function() {
                const theoretical = parseFloat(theoreticalCell.textContent.replace(/[^\d.-]/g, ''));
                const actual = parseFloat(this.value) || 0;
                const difference = actual - theoretical;
                
                if (!isNaN(difference)) {
                    differenceCell.innerHTML = `<span class="${difference < 0 ? 'text-danger' : (difference > 0 ? 'text-success' : '')}">${difference.toFixed(2)}</span>`;
                } else {
                    differenceCell.innerHTML = '-';
                }
            });
        });
    });
</script>
@endpush