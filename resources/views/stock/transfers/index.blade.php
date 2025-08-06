@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transferts de Stock</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.transfers.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nouveau Transfert
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>N° Transfert</th>
                                    <th>Date</th>
                                    <th>Dépôt Source</th>
                                    <th>Dépôt Destination</th>
                                    <th>Produit</th>
                                    <th>Quantité</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfers as $transfer)
                                <tr>
                                    <td>{{ $transfer->numero_transfert }}</td>
                                    <td>{{ $transfer->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $transfer->warehouseSource->nom }}</td>
                                    <td>{{ $transfer->warehouseDestination->nom }}</td>
                                    <td>{{ $transfer->product->nom }}</td>
                                    <td>{{ $transfer->quantite }} {{ $transfer->unite }}</td>
                                    <td>
                                        <span class="badge badge-{{ $transfer->statut === 'en_attente' ? 'warning' : ($transfer->statut === 'en_transit' ? 'info' : ($transfer->statut === 'receptionne' ? 'success' : 'danger')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $transfer->statut)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('stock.transfers.show', $transfer) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($transfer->statut === 'en_attente')
                                        <form action="{{ route('stock.transfers.validate', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if($transfer->statut === 'en_transit')
                                        <form action="{{ route('stock.transfers.receive', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary">
                                                <i class="fas fa-inbox"></i>
                                            </button>
                                        </form>
                                        @endif
                                        @if(in_array($transfer->statut, ['en_attente', 'en_transit']))
                                        <form action="{{ route('stock.transfers.cancel', $transfer) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce transfert ?')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $transfers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection