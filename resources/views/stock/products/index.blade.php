@extends('layouts.app')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-800"> Produits</h1>
        <a href="{{ route('stock.products.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg shadow-md transition">
             Nouveau produit
        </a>
    </div>

    <!-- Search -->
    <form method="get" class="mb-6">
        <div class="flex gap-2">
            <input name="q" value="{{ request('q') }}" 
                   placeholder="Rechercher un produit..." 
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
            <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg shadow-sm transition">
                 Filtrer
            </button>
        </div>
    </form>

    <!-- Table -->
    <div class="bg-white shadow-lg rounded-xl overflow-hidden">
        <table class="table-auto w-full text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Quantité</th>
                    <th class="px-4 py-3 text-left">Prix unitaire</th>
                    <th class="px-4 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-t odd:bg-gray-50 hover:bg-gray-100 transition">
                        <td class="px-4 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-4 py-3">{{ $product->quantite }}</td>
                        <td class="px-4 py-3">{{ number_format($product->prix_unitaire, 0, ',', ' ') }} FCFA </td>
                        <td class="px-4 py-3 text-right space-x-2">
                            <a href="{{ route('stock.products.edit', $product) }}" 
                               class="bg-primary-500 hover:bg-primary-600 text-white px-3 py-1 rounded-lg shadow-sm text-xs">
                                 Modifier
                            </a>
                            <form action="{{ route('stock.products.destroy', $product) }}" method="post" class="inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Supprimer ce produit ?')" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg shadow-sm text-xs">
                                    Supprimer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-gray-500">Aucun produit trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="p-4 border-t">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
