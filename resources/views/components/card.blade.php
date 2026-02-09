<div {{ $attributes->merge(['class' => 'bg-white border border-gray-200 shadow-sm rounded-2xl']) }}>
    @if(isset($header))
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            {{ $header }}
        </div>
    @endif
    
    <div class="px-4 py-5 sm:p-6">
        {{ $slot }}
    </div>
    
    @if(isset($footer))
        <div class="bg-gray-50 px-4 py-4 sm:px-6 rounded-b-2xl border-t border-gray-200">
            {{ $footer }}
        </div>
    @endif
</div>