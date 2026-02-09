@props([
    'href' => null,
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, success
    'size' => 'md', // sm, md, lg
])

@if($href)
    <a href="{{ $href }}" 
       {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"]) }}
       class="
           {{ $attributes->get('class') }}
           @if($variant === 'secondary') bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 @endif
           @if($variant === 'danger') bg-red-600 hover:bg-red-700 focus:ring-red-500 @endif
           @if($variant === 'success') bg-green-600 hover:bg-green-700 focus:ring-green-500 @endif
           @if($variant === 'primary') bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 @endif
           @if($size === 'sm') px-3 py-1.5 text-xs @endif
           @if($size === 'lg') px-6 py-3 text-base @endif
       ">
        {{ $slot }}
    </a>
@else
    <button 
        type="{{ $type }}"
        {{ $attributes->merge(['class' => "inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500"]) }}
        class="
            {{ $attributes->get('class') }}
            @if($variant === 'secondary') bg-gray-600 hover:bg-gray-700 focus:ring-gray-500 @endif
            @if($variant === 'danger') bg-red-600 hover:bg-red-700 focus:ring-red-500 @endif
            @if($variant === 'success') bg-green-600 hover:bg-green-700 focus:ring-green-500 @endif
            @if($variant === 'primary') bg-primary-600 hover:bg-primary-700 focus:ring-primary-500 @endif
            @if($size === 'sm') px-3 py-1.5 text-xs @endif
            @if($size === 'lg') px-6 py-3 text-base @endif
        ">
        {{ $slot }}
    </button>
@endif