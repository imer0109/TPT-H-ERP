@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h3 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
            <i class="fas fa-plug text-indigo-600"></i>
            {{ $apiConnector->name }}
        </h3>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('api-connectors.api-connectors.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
            <a href="{{ route('api-connectors.api-connectors.edit', $apiConnector) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                <i class="fas fa-edit"></i> Modifier
            </a>
        </div>
    </div>

    <!-- Connector Info -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white shadow rounded-xl p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                <div>
                    <h4 class="text-xl font-semibold text-gray-800">{{ $apiConnector->name }}</h4>
                    <p class="text-gray-600">{{ $apiConnector->description ?? 'Aucune description' }}</p>
                </div>
                <span class="px-3 py-1 text-sm font-semibold rounded
                    {{ $apiConnector->status === 'active' ? 'bg-green-100 text-green-800' :
                       ($apiConnector->status === 'error' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800') }}">
                    {{ $apiConnector->getStatusLabel() }}
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Société</label>
                    <p class="text-gray-800 font-medium">{{ $apiConnector->company->raison_sociale }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Type</label>
                    <p class="text-gray-800 font-medium">{{ $apiConnector->getTypeLabel() }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Fréquence de Synchronisation</label>
                    <p class="text-gray-800 font-medium">{{ $apiConnector->getFrequencyLabel() }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Statut Actif</label>
                    <p class="text-gray-800 font-medium">
                        @if($apiConnector->is_active)
                            <span class="text-green-600">Oui</span>
                        @else
                            <span class="text-red-600">Non</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Dernière Synchronisation</label>
                    <p class="text-gray-800 font-medium">
                        @if($apiConnector->last_sync_at)
                            {{ $apiConnector->last_sync_at->format('d/m/Y H:i') }}
                            <br><small class="text-gray-500">{{ $apiConnector->last_sync_at->diffForHumans() }}</small>
                        @else
                            <span class="text-gray-400">Jamais</span>
                        @endif
                    </p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Prochaine Synchronisation</label>
                    <p class="text-gray-800 font-medium">
                        @if($apiConnector->next_sync_at)
                            {{ $apiConnector->next_sync_at->format('d/m/Y H:i') }}
                            <br><small class="text-gray-500">{{ $apiConnector->next_sync_at->diffForHumans() }}</small>
                        @else
                            <span class="text-gray-400">Manuel</span>
                        @endif
                    </p>
                </div>
            </div>

            <!-- Configuration -->
            <div class="border-t border-gray-200 pt-6">
                <h5 class="text-lg font-semibold text-gray-800 mb-4">Configuration</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">URL de l'API</label>
                        <p class="text-gray-800 font-medium break-all">{{ $apiConnector->getConfig('url', 'Non configuré') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Clé API</label>
                        <p class="text-gray-800 font-medium">
                            @if($apiConnector->getConfig('api_key'))
                                <span class="text-green-600">●●●●●●●●</span>
                            @else
                                <span class="text-gray-400">Non configuré</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nom d'utilisateur</label>
                        <p class="text-gray-800 font-medium">{{ $apiConnector->getConfig('username', 'Non configuré') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-white shadow rounded-xl p-6">
            <h4 class="text-lg font-semibold text-gray-800 mb-4">Statistiques</h4>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Synchronisations</span>
                    <span class="font-semibold">{{ $stats['total_syncs'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Synchronisations Réussies</span>
                    <span class="font-semibold text-green-600">{{ $stats['successful_syncs'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Synchronisations Échouées</span>
                    <span class="font-semibold text-red-600">{{ $stats['failed_syncs'] }}</span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-200">
                    <span class="text-gray-600">Taux de Succès</span>
                    <span class="px-2 py-1 text-xs font-semibold rounded
                        {{ $stats['success_rate'] >= 90 ? 'bg-green-100 text-green-800' :
                           ($stats['success_rate'] >= 70 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                        {{ $stats['success_rate'] }}%
                    </span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <h5 class="text-md font-semibold text-gray-800 mb-3">Actions</h5>
                <div class="space-y-3">
                    <button onclick="testConnection('{{ $apiConnector->id }}')"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition">
                        <i class="fas fa-plug"></i> Tester la Connexion
                    </button>
                    <button onclick="syncNow('{{ $apiConnector->id }}')"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition">
                        <i class="fas fa-sync"></i> Synchroniser Maintenant
                    </button>
                    <button onclick="toggleStatus('{{ $apiConnector->id }}')"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 
                                {{ $apiConnector->status === 'active' ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} 
                                text-white rounded-lg transition">
                        <i class="fas fa-power-off"></i> 
                        {{ $apiConnector->status === 'active' ? 'Désactiver' : 'Activer' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Sync Logs -->
    <div class="mt-8 bg-white shadow rounded-xl p-6">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-lg font-semibold text-gray-800">Historique des Synchronisations</h4>
            <button onclick="loadLogs()" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-sync"></i> Actualiser
            </button>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Date</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Déclenchée par</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Statut</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Durée</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-600">Enregistrements</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm" id="sync-logs-table">
                    @forelse($apiConnector->syncLogs as $log)
                        <tr>
                            <td class="px-4 py-3">
                                {{ $log->started_at->format('d/m/Y H:i') }}
                                <br><small class="text-gray-500">{{ $log->started_at->diffForHumans() }}</small>
                            </td>
                            <td class="px-4 py-3">
                                {{ $log->triggeredBy->name ?? 'Système' }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs font-semibold rounded
                                    {{ $log->status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($log->finished_at)
                                    {{ $log->finished_at->diffInSeconds($log->started_at) }}s
                                @else
                                    <span class="text-gray-400">En cours</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                {{ $log->records_processed ?? 0 }} / {{ $log->records_total ?? 0 }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                <i class="fas fa-info-circle text-gray-400 mb-2"></i>
                                <p>Aucune synchronisation effectuée pour ce connecteur.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
function testConnection(connectorId) {
    const btn = event.target.closest('button');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Test en cours...';
    btn.disabled = true;

    fetch(`/api-connectors/${connectorId}/test-connection`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => toastr[data.success ? 'success' : 'error'](data.message))
    .catch(() => toastr.error('Erreur lors du test'))
    .finally(() => { btn.innerHTML = original; btn.disabled = false; });
}

function syncNow(connectorId) {
    const btn = event.target.closest('button');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Synchronisation...';
    btn.disabled = true;

    fetch(`/api-connectors/${connectorId}/sync-now`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        toastr[data.success ? 'success' : 'error'](data.message);
        if (data.success) setTimeout(() => location.reload(), 2000);
    })
    .catch(() => toastr.error('Erreur de synchronisation'))
    .finally(() => { btn.innerHTML = original; btn.disabled = false; });
}

function toggleStatus(connectorId) {
    const btn = event.target.closest('button');
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Traitement...';
    btn.disabled = true;

    fetch(`/api-connectors/${connectorId}/toggle-status`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            toastr.success(data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            toastr.error(data.message);
            btn.innerHTML = original;
            btn.disabled = false;
        }
    })
    .catch(() => {
        toastr.error('Erreur lors du changement de statut');
        btn.innerHTML = original;
        btn.disabled = false;
    });
}

function loadLogs() {
    const table = document.getElementById('sync-logs-table');
    table.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center"><i class="fas fa-spinner fa-spin"></i> Chargement...</td></tr>';
    
    fetch(`/api-connectors/{{ $apiConnector->id }}/logs`, {
        headers: { 'Accept': 'application/json' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.data && data.data.length > 0) {
            table.innerHTML = data.data.map(log => `
                <tr>
                    <td class="px-4 py-3">
                        ${new Date(log.started_at).toLocaleString('fr-FR')}
                        <br><small class="text-gray-500">${timeAgo(new Date(log.started_at))}</small>
                    </td>
                    <td class="px-4 py-3">
                        ${log.triggered_by?.name || 'Système'}
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 text-xs font-semibold rounded ${
                            log.status === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        }">
                            ${log.status.charAt(0).toUpperCase() + log.status.slice(1)}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        ${log.finished_at ? Math.round((new Date(log.finished_at) - new Date(log.started_at)) / 1000) + 's' : '<span class="text-gray-400">En cours</span>'}
                    </td>
                    <td class="px-4 py-3">
                        ${log.records_processed || 0} / ${log.records_total || 0}
                    </td>
                </tr>
            `).join('');
        } else {
            table.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-gray-500"><i class="fas fa-info-circle text-gray-400 mb-2"></i><p>Aucune synchronisation effectuée pour ce connecteur.</p></td></tr>';
        }
    })
    .catch(() => {
        table.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-center text-red-500">Erreur lors du chargement des logs.</td></tr>';
    });
}

function timeAgo(date) {
    const seconds = Math.floor((new Date() - date) / 1000);
    let interval = seconds / 31536000;
    
    if (interval > 1) return Math.floor(interval) + " ans";
    interval = seconds / 2592000;
    if (interval > 1) return Math.floor(interval) + " mois";
    interval = seconds / 86400;
    if (interval > 1) return Math.floor(interval) + " jours";
    interval = seconds / 3600;
    if (interval > 1) return Math.floor(interval) + " heures";
    interval = seconds / 60;
    if (interval > 1) return Math.floor(interval) + " minutes";
    return Math.floor(seconds) + " secondes";
}
</script>
@endpush
@endsection