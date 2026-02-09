@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4" x-data="journalManagement()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-bold">Gestion des Journaux Comptables</h3>
        <button @click="openCreateModal" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Nouveau Journal
        </button>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Table -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Code</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Nom</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Société</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Description</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Statut</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($journals as $journal)
                    <tr>
                        <td class="px-4 py-2">{{ $journal->code }}</td>
                        <td class="px-4 py-2">{{ $journal->name }}</td>
                        <td class="px-4 py-2">
                            <span class="bg-primary-100 text-primary-800 px-2 py-1 rounded text-xs">
                                {{ \App\Models\AccountingJournal::JOURNAL_TYPES[$journal->type] ?? $journal->type }}
                            </span>
                        </td>
                        <td class="px-4 py-2">{{ $journal->company->name }}</td>
                        <td class="px-4 py-2">{{ $journal->description ?? '-' }}</td>
                        <td class="px-4 py-2">
                            <span :class="journal.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-2 py-1 rounded text-xs">
                                {{ $journal->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 space-x-2">
                            <button @click="openEditModal({{ $journal->id }})" class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('accounting.settings.journals.destroy', $journal) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center px-4 py-2">Aucun journal trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Create Modal -->
    <div x-show="showCreateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg w-96 p-6" @click.away="closeModals">
            <h3 class="text-lg font-bold mb-4">Nouveau Journal Comptable</h3>
            <form method="POST" action="{{ route('accounting.settings.journals.store') }}">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Code *</label>
                        <input type="text" name="code" class="w-full border rounded px-3 py-2" required maxlength="10">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nom *</label>
                        <input type="text" name="name" class="w-full border rounded px-3 py-2" required maxlength="255">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Type *</label>
                        <select name="type" class="w-full border rounded px-3 py-2" required>
                            <option value="">Sélectionner un type</option>
                            @foreach(\App\Models\AccountingJournal::JOURNAL_TYPES as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Société *</label>
                        <select name="company_id" class="w-full border rounded px-3 py-2" required>
                            <option value="">Sélectionner une société</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="w-full border rounded px-3 py-2" rows="2"></textarea>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" id="create_is_active" value="1" checked>
                        <label for="create_is_active" class="text-sm">Actif</label>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" @click="closeModals" class="px-4 py-2 rounded border">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded">Créer</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white rounded-lg w-96 p-6" @click.away="closeModals">
            <h3 class="text-lg font-bold mb-4">Modifier Journal Comptable</h3>
            <form :action="editFormAction" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Code *</label>
                        <input type="text" name="code" x-model="editJournal.code" class="w-full border rounded px-3 py-2" required maxlength="10">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Nom *</label>
                        <input type="text" name="name" x-model="editJournal.name" class="w-full border rounded px-3 py-2" required maxlength="255">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Type *</label>
                        <select name="type" x-model="editJournal.type" class="w-full border rounded px-3 py-2" required>
                            <template x-for="[key, value] in journalTypes" :key="key">
                                <option :value="key" x-text="value"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Société *</label>
                        <select name="company_id" x-model="editJournal.company_id" class="w-full border rounded px-3 py-2" required>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" x-model="editJournal.description" class="w-full border rounded px-3 py-2" rows="2"></textarea>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="checkbox" name="is_active" x-model="editJournal.is_active" value="1">
                        <label class="text-sm">Actif</label>
                    </div>
                    <div class="flex justify-end space-x-2 mt-4">
                        <button type="button" @click="closeModals" class="px-4 py-2 rounded border">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded">Modifier</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function journalManagement() {
    return {
        showCreateModal: false,
        showEditModal: false,
        editFormAction: '',
        editJournal: {},
        journalTypes: @json(\App\Models\AccountingJournal::JOURNAL_TYPES),
        journals: @json($journals),

        openCreateModal() {
            this.showCreateModal = true;
        },
        openEditModal(id) {
            const journal = this.journals.find(j => j.id === id);
            if(journal){
                this.editJournal = {...journal};
                this.editFormAction = `/accounting/settings/journals/${id}`;
                this.showEditModal = true;
            }
        },
        closeModals() {
            this.showCreateModal = false;
            this.showEditModal = false;
            this.editJournal = {};
        }
    }
}
</script>
@endpush
