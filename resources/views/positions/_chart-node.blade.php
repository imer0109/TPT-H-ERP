<div class="chart-node">
    <div class="node-content">
        <div class="node-title">{{ $position->title }}</div>
        @if($position->department)
            <div class="node-department">{{ $position->department->name }}</div>
        @endif
        <div class="node-employees">
            {{ $position->employees->count() }} employé{{ $position->employees->count() > 1 ? 's' : '' }}
        </div>
        <div class="node-actions">
            <a href="{{ route('hr.positions.show', $position) }}">Voir</a>
            <a href="{{ route('hr.positions.edit', $position) }}">Modifier</a>
        </div>
    </div>
    
    @if($position->childPositions->count() > 0)
        <div class="node-children">
            @foreach($position->childPositions as $child)
                @include('positions._chart-node', ['position' => $child])
            @endforeach
        </div>
    @endif
</div>