@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-2xl font-semibold">Gestion des Centres de Coût</h3>
        <button @click="openCreateModal = true" class="bg-primary-600 text-white px-4 py-2 rounded hover:bg-primary-700 flex items-center">
            <i class="fas fa-plus mr-2"></i> Nouveau Centre de Coût
        </button>
    </div>

    <!-- Table des centres de coût -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Code</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Nom</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Société</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Statut</th>
                    <th class="px-4 py-2 text-center text-sm font-medium text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($costCenters as $cc)
                    <tr>
                        <td class="px-4 py-2">{{ $cc->code }}</td>
                        <td class="px-4 py-2">{{ $cc->name }}</td>
                        <td class="px-4 py-2">{{ $cc->company->name }}</td>
                        <td class="px-4 py-2">{{ $cc->description ?? '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            <span class="px-2 py-1 rounded text-white {{ $cc->is_active ? 'bg-green-500' : 'bg-red-500' }}">
                                {{ $cc->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <button @click="editCostCenter({{ $cc->id }})" class="text-primary-600 hover:text-primary-800 mr-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('accounting.settings.cost-centers.destroy', $cc) }}" class="inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-gray-500">Aucun centre de coût trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modals -->
    <div x-data="{ openCreateModal: false, openEditModal: false, editData: {} }">
        <!-- Create Modal -->
        <div x-show="openCreateModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded shadow-lg w-full max-w-md p-6" @click.away="openCreateModal = false">
                <h4 class="text-xl font-semibold mb-4">Nouveau Centre de Coût</h4>
                <form method="POST" action="{{ route('accounting.settings.cost-centers.store') }}">
                    @csrf
                    <div class="mb-2">
                        <label class="block mb-1">Code *</label>
                        <input type="text" name="code" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Nom *</label>
                        <input type="text" name="name" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Société *</label>
                        <select name="company_id" required class="w-full border rounded px-3 py-2">
                            <option value="">Sélectionner une société</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Description</label>
                        <textarea name="description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" value="1" checked class="form-checkbox">
                            <span class="ml-2">Actif</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openCreateModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Créer</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="openEditModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div class="bg-white rounded shadow-lg w-full max-w-md p-6" @click.away="openEditModal = false">
                <h4 class="text-xl font-semibold mb-4">Modifier Centre de Coût</h4>
                <form :action="`/accounting/settings/cost-centers/${editData.id}`" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-2">
                        <label class="block mb-1">Code *</label>
                        <input type="text" name="code" x-model="editData.code" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Nom *</label>
                        <input type="text" name="name" x-model="editData.name" required class="w-full border rounded px-3 py-2">
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Société *</label>
                        <select name="company_id" x-model="editData.company_id" required class="w-full border rounded px-3 py-2">
                            @foreach($companies as $company)
                                <option :value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="block mb-1">Description</label>
                        <textarea name="description" x-model="editData.description" class="w-full border rounded px-3 py-2" rows="3"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_active" x-model="editData.is_active" class="form-checkbox">
                            <span class="ml-2">Actif</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-2">
                        <button type="button" @click="openEditModal = false" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Annuler</button>
                        <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded hover:bg-primary-700">Modifier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function editCostCenter(id) {
        const costCenters = @json($costCenters);
        const cc = costCenters.find(c => c.id === id);
        if (cc) {
            window.Alpine.store('editData', { ...cc });
            document.querySelector('[x-data]').__x.$data.openEditModal = true;
            document.querySelector('[x-data]').__x.$data.editData = {...cc};
        }
    }
</script>
@endpush
