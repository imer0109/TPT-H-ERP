@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Gestion des Alertes de Stock</h2>
                    <a href="{{ route('stock.alerts.create') }}" class="btn btn-primary">Nouvelle Alerte</a>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produit</th>
                                <th>Entrepôt</th>
                                <th>Seuil Minimum</th>
                                <th>Seuil de Sécurité</th>
                                <th>Stock Actuel</th>
                                <th>Statut</th>
                                <th>Notifications Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($alerts as $alert)
                            <tr>
                                <td>{{ $alert->product->name }}</td>
                                <td>{{ $alert->warehouse->name }}</td>
                                <td>{{ $alert->minimum_threshold }} {{ $alert->product->unite }}</td>
                                <td>{{ $alert->security_threshold }} {{ $alert->product->unite }}</td>
                                <td>
                                    @php
                                        $currentStock = $alert->product->getStockLevel($alert->warehouse_id);
                                    @endphp
                                    <span class="{{ $currentStock <= $alert->minimum_threshold ? 'text-danger' : ($currentStock <= $alert->security_threshold ? 'text-warning' : 'text-success') }}">
                                        {{ $currentStock }} {{ $alert->product->unite }}
                                    </span>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input alert-status-toggle"
                                               id="status_{{ $alert->id }}" 
                                               {{ $alert->is_active ? 'checked' : '' }}
                                               data-alert-id="{{ $alert->id }}">
                                        <label class="custom-control-label" for="status_{{ $alert->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input email-notification-toggle"
                                               id="email_{{ $alert->id }}" 
                                               {{ $alert->email_notifications ? 'checked' : '' }}
                                               data-alert-id="{{ $alert->id }}">
                                        <label class="custom-control-label" for="email_{{ $alert->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('stock.alerts.edit', $alert->id) }}" class="btn btn-sm btn-info">Modifier</a>
                                    <form action="{{ route('stock.alerts.destroy', $alert->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette alerte ?')">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $alerts->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('.alert-status-toggle').change(function() {
        const alertId = $(this).data('alert-id');
        const isActive = $(this).prop('checked');
        
        $.ajax({
            url: `/stock/alerts/${alertId}/toggle-status`,
            type: 'POST',
            data: {
                is_active: isActive,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success('Statut mis à jour avec succès');
            },
            error: function() {
                toastr.error('Erreur lors de la mise à jour du statut');
                $(this).prop('checked', !isActive);
            }
        });
    });

    $('.email-notification-toggle').change(function() {
        const alertId = $(this).data('alert-id');
        const emailNotifications = $(this).prop('checked');
        
        $.ajax({
            url: `/stock/alerts/${alertId}/toggle-notifications`,
            type: 'POST',
            data: {
                email_notifications: emailNotifications,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success('Préférences de notification mises à jour avec succès');
            },
            error: function() {
                toastr.error('Erreur lors de la mise à jour des préférences de notification');
                $(this).prop('checked', !emailNotifications);
            }
        });
    });
});
</script>
@endpush
@endsection