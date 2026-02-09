@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <div class="bg-white shadow rounded-2xl overflow-hidden">
        <!-- Header -->
        <div class="bg-primary-600 text-white px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold">Gestion des Alertes de Stock</h2>
            <a href="{{ route('stock.alerts.create') }}" 
               class="px-4 py-2 bg-white text-primary-600 font-medium rounded-lg hover:bg-gray-100 transition">
                + Nouvelle Alerte
            </a>
        </div>

        <div class="p-6">
            @if (session('success'))
                <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Table Responsive -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left border-collapse">
                    <thead class="bg-gray-100 text-gray-700">
                        <tr>
                            <th class="px-4 py-3">Produit</th>
                            <th class="px-4 py-3">Entrepôt</th>
                            <th class="px-4 py-3">Seuil Minimum</th>
                            <th class="px-4 py-3">Seuil Sécurité</th>
                            <th class="px-4 py-3">Stock Actuel</th>
                            <th class="px-4 py-3">Statut</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($alerts as $alert)
                        <tr class="hover:bg-primary-50">
                            <td class="px-4 py-3">{{ $alert->product->name ?? 'N/A' }}</td>
                            <td class="px-4 py-3">
                                @if($alert->warehouse)
                                    {{ $alert->warehouse->nom ?? 'N/A' }}
                                @else
                                    Aucun entrepôt
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $alert->seuil_minimum }} {{ $alert->product->unite ?? '' }}</td>
                            <td class="px-4 py-3">{{ $alert->seuil_securite }} {{ $alert->product->unite ?? '' }}</td>
                            <td class="px-4 py-3">
                                @php
                                    $currentStock = $alert->product ? $alert->product->getStockInWarehouse($alert->warehouse_id) : 0;
                                @endphp
                                <span class="
                                    {{ $currentStock <= $alert->seuil_minimum ? 'text-red-600 font-bold' : 
                                       ($currentStock <= $alert->seuil_securite ? 'text-yellow-600 font-medium' : 'text-green-600 font-medium') }}
                                ">
                                    {{ $currentStock }} {{ $alert->product->unite ?? '' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           class="sr-only peer alert-status-toggle"
                                           id="status_{{ $alert->id }}"
                                           data-alert-id="{{ $alert->id }}"
                                           {{ $alert->alerte_active ? 'checked' : '' }}>
                                    <div class="w-10 h-5 bg-gray-300 peer-checked:bg-primary-600 rounded-full relative transition">
                                        <div class="absolute w-4 h-4 bg-white rounded-full shadow -left-0.5 top-0.5 peer-checked:translate-x-5 transform transition"></div>
                                    </div>
                                </label>
                            </td>
                            <td class="px-4 py-3">
                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           class="sr-only peer email-notification-toggle"
                                           id="email_{{ $alert->id }}"
                                           data-alert-id="{{ $alert->id }}"
                                           {{ $alert->email_notification ? 'checked' : '' }}>
                                    <div class="w-10 h-5 bg-gray-300 peer-checked:bg-green-600 rounded-full relative transition">
                                        <div class="absolute w-4 h-4 bg-white rounded-full shadow -left-0.5 top-0.5 peer-checked:translate-x-5 transform transition"></div>
                                    </div>
                                </label>
                            </td>
                            <td class="px-4 py-3 text-right space-x-2">
                                <!-- Debug: Show the generated URL -->
                                <div class="text-xs text-gray-500 mb-1">
                                    <!-- URL: {{ route('stock.alerts.edit', $alert->id) }} -->
                                </div>
                                <a href="{{ route('stock.alerts.edit', $alert->id) }}" 
                                   class="px-3 py-1 text-sm rounded-lg bg-primary-500 text-white hover:bg-primary-600 transition">
                                    Modifier
                                </a>
                                <form action="{{ route('stock.alerts.destroy', $alert->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-3 py-1 text-sm rounded-lg bg-red-500 text-white hover:bg-red-600 transition"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette alerte ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $alerts->links() }}
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
            success: function() {
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
            success: function() {
                toastr.success('Préférences mises à jour avec succès');
            },
            error: function() {
                toastr.error('Erreur lors de la mise à jour');
                $(this).prop('checked', !emailNotifications);
            }
        });
    });
});
</script>
@endpush
@endsection