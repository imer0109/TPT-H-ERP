@php
    function renderTreeNode($node, $level = 0) {
        $hasChildren = isset($node['children']) && count($node['children']) > 0;
        $indent = str_repeat('&nbsp;', $level * 4);
@endphp

<div class="tree-node" data-level="{{ $level }}">
    <div class="tree-node-header">
        @if($hasChildren)
            <div class="tree-node-toggle">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        @else
            <div class="tree-node-toggle" style="visibility: hidden;">
                <i class="fas fa-chevron-right text-gray-400"></i>
            </div>
        @endif
        
        <div class="flex-1 flex items-center">
            <span class="font-mono text-sm">{{ $node['code'] }}</span>
            <span class="ml-2 text-sm">{{ $node['label'] }}</span>
            
            @if($node['is_auxiliary'] ?? false)
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    Auxiliaire
                </span>
            @endif
            
            @if(!($node['is_active'] ?? true))
                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    Inactif
                </span>
            @endif
            
            <div class="ml-auto flex space-x-2">
                <a href="{{ route('accounting.chart-of-accounts.show', $node['id']) }}" 
                   class="text-primary-600 hover:text-primary-900 text-xs" title="Voir">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('accounting.chart-of-accounts.edit', $node['id']) }}" 
                   class="text-green-600 hover:text-green-900 text-xs" title="Modifier">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="{{ route('accounting.chart-of-accounts.create', ['parent_id' => $node['id']]) }}" 
                   class="text-purple-600 hover:text-purple-900 text-xs" title="Ajouter un sous-compte">
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
    </div>
    
    @if($hasChildren)
        <div class="tree-node-children">
            @foreach($node['children'] as $child)
                {!! renderTreeNode($child, $level + 1) !!}
            @endforeach
        </div>
    @endif
</div>

@php
    }
@endphp

@foreach($nodes as $node)
    {!! renderTreeNode($node, $level) !!}
@endforeach