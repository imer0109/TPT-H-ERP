@extends('layouts.app')

@section('title', 'Interactions Clients')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Gestion des Interactions Clients</h4>
                    <a href="{{ route('clients.interactions.create') }}" class="btn btn-primary float-right">
                        <i class="fas fa-plus"></i> Nouvelle Interaction
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="interactions-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Utilisateur</th>
                                    <th>Suivi Nécessaire</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($interactions as $interaction)
                                <tr>
                                    <td>{{ $interaction->id }}</td>
                                    <td>
                                        <a href="{{ route('clients.show', $interaction->client) }}">
                                            {{ $interaction->client->nom_raison_sociale }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $interaction->type_interaction == 'appel_telephonique' ? 'primary' : 
                                                ($interaction->type_interaction == 'visite_commerciale' ? 'info' : 
                                                ($interaction->type_interaction == 'email' ? 'success' : 'warning')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $interaction->type_interaction)) }}
                                        </span>
                                    </td>
                                    <td>{{ Str::limit($interaction->description, 50) }}</td>
                                    <td>{{ $interaction->date_interaction->format('d/m/Y') }}</td>
                                    <td>{{ $interaction->user ? $interaction->user->nom . ' ' . $interaction->user->prenom : 'N/A' }}</td>
                                    <td>
                                        @if($interaction->suivi_necessaire)
                                            <span class="badge badge-danger">Oui</span>
                                            @if($interaction->date_suivi)
                                                <small class="d-block">Date: {{ $interaction->date_suivi->format('d/m/Y') }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge-success">Non</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('clients.interactions.show', $interaction) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.interactions.edit', $interaction) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('clients.interactions.destroy', $interaction) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette interaction ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @if($interaction->suivi_necessaire)
                                            <form action="{{ route('clients.interactions.mark-as-followed-up', $interaction) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning" title="Marquer comme suivi">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $interactions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#interactions-table').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/French.json"
        },
        "pageLength": 25,
        "order": [[0, "desc"]]
    });
});
</script>
@endsection