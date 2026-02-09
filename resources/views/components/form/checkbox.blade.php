@props([
    'name',
    'label' => null,
    'checked' => false,
    'value' => 1,
    'required' => false,
    'error' => $errors->has($name) ? $errors->first($name) : null
])

<div class="mb-4">
    <label class="inline-flex items-center">
        <input 
            type="checkbox"
            name="{{ $name }}"
            value="{{ $value }}"
            {{ $checked || old($name) ? 'checked' : '' }}
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge(['class' => 'rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50']) }}
        >
        @if($label)
            <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
        @endif
    </label>
    
    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>