@props([
    'name',
    'value' => old($name),
    'label' => null,
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
    'readonly' => false,
    'disabled' => false,
    'help' => null,
    'errorKey' => $name,
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"
        @if($required) required @endif
        @if($readonly) readonly @endif
        @if($disabled) disabled @endif
        {{ $attributes->merge(['class' => 'w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500']) }}
    >
    
    @if($errors->has($errorKey))
        <p class="mt-1 text-sm text-red-600">{{ $errors->first($errorKey) }}</p>
    @endif
    
    @if($help)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>