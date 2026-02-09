@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Gérer les affectations de {{ $user->name }}</h1>
        <a href="{{ route('user-assignments.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
            Retour
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Affectations aux sociétés -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Affectations aux sociétés</h3>
            
            <form action="{{ route('user-assignments.assign.company', $user) }}" method="POST" class="mb-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="company_id" class="block text-sm font-medium text-gray-700">Société</label>
                        <select name="company_id" id="company_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionner une société</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->raison_sociale }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="date_fin" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
            
            @if($user->societes->count() > 0)
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Société</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Dates</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($user->societes as $company)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                {{ $company->raison_sociale }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                @if($company->pivot->date_debut)
                                    Du {{ \Carbon\Carbon::parse($company->pivot->date_debut)->format('d/m/Y') }}
                                @endif
                                @if($company->pivot->date_fin)
                                    Au {{ \Carbon\Carbon::parse($company->pivot->date_fin)->format('d/m/Y') }}
                                @endif
                                @if(!$company->pivot->date_debut && !$company->pivot->date_fin)
                                    <span class="text-gray-500">Indéfini</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('user-assignments.remove.company', [$user, $company->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4 text-gray-500">
                <p>Aucune affectation à une société.</p>
            </div>
            @endif
        </div>

        <!-- Affectations aux agences -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Affectations aux agences</h3>
            
            <form action="{{ route('user-assignments.assign.agency', $user) }}" method="POST" class="mb-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div>
                        <label for="agency_id" class="block text-sm font-medium text-gray-700">Agence</label>
                        <select name="agency_id" id="agency_id" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionner une agence</option>
                            @foreach($agencies as $agency)
                                <option value="{{ $agency->id }}">{{ $agency->nom }} ({{ $agency->company->raison_sociale }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label for="date_debut_agency" class="block text-sm font-medium text-gray-700">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut_agency"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="date_fin_agency" class="block text-sm font-medium text-gray-700">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin_agency"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                    </div>
                    
                    <div class="flex items-end">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                            Ajouter
                        </button>
                    </div>
                </div>
            </form>
            
            @if($user->agences->count() > 0)
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-primary-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Agence</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Dates</th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($user->agences as $agency)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                {{ $agency->nom }}
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                @if($agency->pivot->date_debut)
                                    Du {{ \Carbon\Carbon::parse($agency->pivot->date_debut)->format('d/m/Y') }}
                                @endif
                                @if($agency->pivot->date_fin)
                                    Au {{ \Carbon\Carbon::parse($agency->pivot->date_fin)->format('d/m/Y') }}
                                @endif
                                @if(!$agency->pivot->date_debut && !$agency->pivot->date_fin)
                                    <span class="text-gray-500">Indéfini</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                <form action="{{ route('user-assignments.remove.agency', [$user, $agency->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4 text-gray-500">
                <p>Aucune affectation à une agence.</p>
            </div>
            @endif
        </div>

        <!-- Affectation à l'équipe et au département -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Affectation à l'équipe et au département</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <form action="{{ route('user-assignments.assign.team', $user) }}" method="POST">
                    @csrf
                    <div>
                        <label for="team_id" class="block text-sm font-medium text-gray-700">Équipe</label>
                        <select name="team_id" id="team_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionner une équipe</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}" {{ $user->team_id == $team->id ? 'selected' : '' }}>
                                    {{ $team->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                            Mettre à jour
                        </button>
                    </div>
                </form>
                
                <form action="{{ route('user-assignments.assign.department', $user) }}" method="POST">
                    @csrf
                    <div>
                        <label for="department_id" class="block text-sm font-medium text-gray-700">Département</label>
                        <select name="department_id" id="department_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                            <option value="">Sélectionner un département</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}" {{ $user->department_id == $department->id ? 'selected' : '' }}>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-2">
                        <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Équipe actuelle:</span> 
                        @if($user->team)
                            {{ $user->team->name }}
                        @else
                            <span class="text-gray-500">Aucune</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Département actuel:</span> 
                        @if($user->department)
                            {{ $user->department->name }}
                        @else
                            <span class="text-gray-500">Aucun</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Affectation du responsable hiérarchique -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Responsable hiérarchique</h3>
            
            <form action="{{ route('user-assignments.assign.manager', $user) }}" method="POST" class="mb-4">
                @csrf
                <div>
                    <label for="manager_id" class="block text-sm font-medium text-gray-700">Responsable</label>
                    <select name="manager_id" id="manager_id"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm">
                        <option value="">Sélectionner un responsable</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ $user->manager_id == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-2">
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg">
                        Mettre à jour
                    </button>
                </div>
            </form>
            
            <div>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Responsable actuel:</span> 
                    @if($user->manager)
                        {{ $user->manager->name }}
                    @else
                        <span class="text-gray-500">Aucun</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection