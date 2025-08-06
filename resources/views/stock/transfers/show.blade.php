@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Détails du Transfert #{{ $transfer->numero_transfert }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('stock.transfers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Statut</th>
                                    <td>
                                        <span class="badge badge-{{ $transfer->statut === 'en_attente' ? 'warning' : ($transfer->statut === 'en_transit' ? 'info' : ($transfer->statut === 'receptionne' ? 'success' : 'danger')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $transfer->statut)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Dépôt Source</th>
                                    <td>{{ $transfer->warehouseSource->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Dépôt Destination</th>
                                    <td>{{ $transfer->warehouseDestination->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Produit</th>
                                    <td>{{ $transfer->product->nom }}</td>
                                </tr>
                                <tr>
                                    <th>Quantité</th>
                                    <td>{{ $transfer->quantite }} {{ $transfer->unite }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Créé par</th>
                                    <td>{{ $transfer->createdBy->name }} le {{ $transfer->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                @if($transfer->validated_by)
                                <tr>
                                    <th>Validé par</th>
                                    <td>{{ $transfer->validatedBy->name }} le {{ $transfer->date_validation->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($transfer->received_by)
                                <tr>
                                    <th>Réceptionné par</th>
                                    <td>{{ $transfer->receivedBy->name }} le {{ $transfer->date_reception->format('d/m/Y H:i') }}</td>
                                </tr>
                                @endif
                                @if($transfer->justificatif)
                                <tr>
                                    <th>Justificatif</th>
                                    <td>
                                        <a href="{{ Storage::url($transfer->justificatif) }}" target="_blank" class="btn btn-sm btn-info">
                                            <i class="fas fa-file"></i> Voir le document
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @if($transfer->notes)
                                <tr>
                                    <th>Notes</th>
                                    <td>{{ $transfer->notes }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            @if($transfer->statut === 'en_attente')
                            <form action="{{ route('stock.transfers.validate', $transfer) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Valider le transfert
                                </button>
                            </form>
                            @endif

                            @if($transfer->statut === 'en_transit')
                            <form action="{{ route('stock.transfers.receive', $transfer) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-inbox"></i> Réceptionner
                                </button>
                            </form>
                            @endif

                            @if(in_array($transfer->statut, ['en_attente', 'en_transit']))
                            <form action="{{ route('stock.transfers.cancel', $transfer) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce transfert ?')">
                                    <i class="fas fa-times"></i> Annuler le transfert
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection