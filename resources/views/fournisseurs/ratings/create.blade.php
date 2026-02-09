@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Évaluer le fournisseur: {{ $fournisseur->raison_sociale }}</h1>
        <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-arrow-left mr-2"></i> Retour à la fiche fournisseur
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('fournisseurs.ratings.store', $fournisseur) }}" method="POST">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quality Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Qualité des produits/services <span class="text-red-600">*</span>
                    </label>
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" name="quality_rating" value="{{ $i }}" id="quality_{{ $i }}" 
                                class="hidden" {{ old('quality_rating') == $i ? 'checked' : '' }} required>
                            <label for="quality_{{ $i }}" class="cursor-pointer text-2xl {{ old('quality_rating') >= $i ? 'text-yellow-400' : 'text-gray-300' }}" 
                                onmouseover="highlightStars('quality', {{ $i }})" 
                                onmouseout="resetStars('quality', {{ old('quality_rating', 0) }})">
                                ★
                            </label>
                        @endfor
                    </div>
                    @error('quality_rating')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Delivery Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Respect des délais de livraison <span class="text-red-600">*</span>
                    </label>
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" name="delivery_rating" value="{{ $i }}" id="delivery_{{ $i }}" 
                                class="hidden" {{ old('delivery_rating') == $i ? 'checked' : '' }} required>
                            <label for="delivery_{{ $i }}" class="cursor-pointer text-2xl {{ old('delivery_rating') >= $i ? 'text-yellow-400' : 'text-gray-300' }}" 
                                onmouseover="highlightStars('delivery', {{ $i }})" 
                                onmouseout="resetStars('delivery', {{ old('delivery_rating', 0) }})">
                                ★
                            </label>
                        @endfor
                    </div>
                    @error('delivery_rating')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Responsiveness Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Réactivité <span class="text-red-600">*</span>
                    </label>
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" name="responsiveness_rating" value="{{ $i }}" id="responsiveness_{{ $i }}" 
                                class="hidden" {{ old('responsiveness_rating') == $i ? 'checked' : '' }} required>
                            <label for="responsiveness_{{ $i }}" class="cursor-pointer text-2xl {{ old('responsiveness_rating') >= $i ? 'text-yellow-400' : 'text-gray-300' }}" 
                                onmouseover="highlightStars('responsiveness', {{ $i }})" 
                                onmouseout="resetStars('responsiveness', {{ old('responsiveness_rating', 0) }})">
                                ★
                            </label>
                        @endfor
                    </div>
                    @error('responsiveness_rating')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pricing Rating -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Rapport qualité-prix <span class="text-red-600">*</span>
                    </label>
                    <div class="flex space-x-1">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" name="pricing_rating" value="{{ $i }}" id="pricing_{{ $i }}" 
                                class="hidden" {{ old('pricing_rating') == $i ? 'checked' : '' }} required>
                            <label for="pricing_{{ $i }}" class="cursor-pointer text-2xl {{ old('pricing_rating') >= $i ? 'text-yellow-400' : 'text-gray-300' }}" 
                                onmouseover="highlightStars('pricing', {{ $i }})" 
                                onmouseout="resetStars('pricing', {{ old('pricing_rating', 0) }})">
                                ★
                            </label>
                        @endfor
                    </div>
                    @error('pricing_rating')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Comments -->
                <div class="md:col-span-2">
                    <label for="comments" class="block text-sm font-medium text-gray-700 mb-1">
                        Commentaires
                    </label>
                    <textarea name="comments" id="comments" rows="4" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200">{{ old('comments') }}</textarea>
                    @error('comments')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-2">
                <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-times mr-2"></i> Annuler
                </a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-save mr-2"></i> Enregistrer l'évaluation
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function highlightStars(category, rating) {
    for (let i = 1; i <= 5; i++) {
        const star = document.querySelector(`label[for="${category}_${i}"]`);
        if (i <= rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    }
}

function resetStars(category, rating) {
    for (let i = 1; i <= 5; i++) {
        const star = document.querySelector(`label[for="${category}_${i}"]`);
        if (i <= rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-yellow-400');
        } else {
            star.classList.remove('text-yellow-400');
            star.classList.add('text-gray-300');
        }
    }
}
</script>
@endsection