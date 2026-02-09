@extends('layouts.app')

@section('title', 'Réclamations Clients')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Gestion des Réclamations Clients</h4>
                    <a href="{{ route('clients.reclamations.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Nouvelle Réclamation
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="reclamations-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Statut</th>
                                    <th>Agent</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reclamations as $reclamation)
                                <tr>
                                    <td>{{ $reclamation->id }}</td>
                                    <td>
                                        <a href="{{ route('clients.show', $reclamation->client) }}">
                                            {{ $reclamation->client->nom_raison_sociale }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $reclamation->type_reclamation == 'produit_defectueux' ? 'danger' : 
                                                ($reclamation->type_reclamation == 'retard_livraison' ? 'warning' : 
                                                ($reclamation->type_reclamation == 'erreur_facturation' ? 'info' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $reclamation->type_reclamation)) }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($reclamation->description, 50) }}</td>
                                    <td>
                                        <span class="badge badge-{{ $reclamation->statut == 'ouverte' ? 'warning' : 
                                                ($reclamation->statut == 'en_cours' ? 'info' : 'success') }}">
                                            {{ ucfirst(str_replace('_', ' ', $reclamation->statut)) }}
                                        </span>
                                    </td>
                                    <td>{{ $reclamation->agent ? $reclamation->agent->nom . ' ' . $reclamation->agent->prenom : 'Non assigné' }}</td>
                                    <td>{{ $reclamation->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('clients.reclamations.show', $reclamation) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.reclamations.edit', $reclamation) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clients.reclamations.destroy', $reclamation) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réclamation ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $reclamations->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#reclamations-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        "pageLength": 25,
        "order": [[0, "desc"]]
    });
});
</script>
@endsection
