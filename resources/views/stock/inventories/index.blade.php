@extends('layouts.app')

@section('title', 'Inventaires de Stock')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Inventaires de Stock</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.inventories.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Nouvel Inventaire
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.inventories.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Statut</label>
                                    <select name="status" class="form-control">
                                        <option value="">Tous</option>
                                        <option value="en_cours" {{ request('status') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                                        <option value="valide" {{ request('status') == 'valide' ? 'selected' : '' }}>Validé</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dépôt</label>
                                    <select name="warehouse_id" class="form-control">
                                        <option value="">Tous</option>
                                        @foreach($warehouses as $id => $name)
                                            <option value="{{ $id }}" {{ request('warehouse_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Période</label>
                                    <div class="input-group">
                                        <input type="date" name="date_start" class="form-control" value="{{ request('date_start') }}">
                                        <div class="input-group-append">
                                            <span class="input-group-text">à</span>
                                        </div>
                                        <input type="date" name="date_end" class="form-control" value="{{ request('date_end') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary form-control">Filtrer</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Référence</th>
                                    <th>Date</th>
                                    <th>Dépôt</th>
                                    <th>Statut</th>
                                    <th>Créé par</th>
                                    <th>Validé par</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inventories as $inventory)
                                    <tr>
                                        <td>{{ $inventory->reference }}</td>
                                        <td>{{ $inventory->date->format('d/m/Y') }}</td>
                                        <td>{{ $inventory->warehouse->nom }}</td>
                                        <td>
                                            @if($inventory->status == 'en_cours')
                                                <span class="badge badge-warning">En cours</span>
                                            @elseif($inventory->status == 'valide')
                                                <span class="badge badge-success">Validé</span>
                                            @endif
                                        </td>
                                        <td>{{ $inventory->createdBy->name }}</td>
                                        <td>{{ $inventory->validatedBy->name ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('stock.inventories.show', $inventory) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if($inventory->status == 'en_cours')
                                                <a href="{{ route('stock.inventories.edit', $inventory) }}" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('stock.inventories.validate', $inventory) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir valider cet inventaire?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Aucun inventaire trouvé</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $inventories->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection