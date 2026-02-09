<div class="bg-white shadow-sm rounded-2xl border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-primary-50">
                {{ $header }}
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                {{ $body }}
            </tbody>
        </table>
    </div>
    
    @if(isset($pagination))
    <div class="bg-gray-50 px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $pagination }}
    </div>
    @endif
</div>