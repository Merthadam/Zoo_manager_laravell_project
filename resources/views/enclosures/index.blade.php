@extends('layouts.layout')

@section('title', 'Enclosures')

@section('content')
    <div class="page-header">
        <h1>Enclosures</h1>
        <p>Below you will find the enclosures assigned to you{{ auth()->user()->admin ? ', or all enclosures (admin)' : '' }}.</p>

        @auth
            @if (auth()->user()->admin)
                <a href="{{ route('enclosures.create') }}" class="btn create-btn">Create Enclosure</a>
            @endif
        @endauth
    </div>

    <div class="data-container">
        @forelse($enclosures as $enclosure)
            <div class="enclosure-card">
                <div class="card-info">
                    <h2>{{ $enclosure->name }}</h2>
                    <p><strong>Limit:</strong> {{ $enclosure->limit }}</p>
                    <p><strong>Current Animals:</strong> {{ $enclosure->animals->count() }}</p>
                
                    <div class="enclosure-type">
                        @if ($enclosure->is_predator)
                            <img src="{{ asset('images/carnivore_symbol.png') }}" alt="Carnivorous" title="Carnivorous" width="32">
                        @else
                            <img src="{{ asset('images/herbivore_symbol.png') }}" alt="Herbivorous" title="Herbivorous" width="32">
                        @endif
                    </div>
                </div>
            
                <div class="card-overlay">
                    <a href="{{ route('enclosures.show', $enclosure->id) }}" class="card-btn">View</a>
                    @auth
                        @if (auth()->user()->admin || auth()->user()->id === $enclosure->user_id)
                            <a href="{{ route('enclosures.edit', $enclosure->id) }}" class="card-btn">Edit</a>

                            @if ($enclosure->animals->count() > 0)
                                <button type="button" onclick="openModal({{ $enclosure->id }})" class="card-btn danger-btn">
                                    Delete
                                </button>
                            @else
                                <form method="POST" action="{{ route('enclosures.destroy', $enclosure->id) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="card-btn danger-btn">Delete</button>
                                </form>
                            @endif
                        @endif
                    @endauth
                </div>
            </div>

            @if ($enclosure->animals->count() > 0)
                <div id="modal-{{ $enclosure->id }}" class="delete-modal hidden">
                    <div class="modal-overlay" onclick="closeModal({{ $enclosure->id }})"></div>
                    <div class="modal-content">
                        <h3>Confirm Deletion</h3>
                        <p>This enclosure contains animals. Deleting it will archive them. Are you sure?</p>
                        <div class="modal-buttons">
                            <button onclick="closeModal({{ $enclosure->id }})" class="btn btn-secondary">Cancel</button>
                            <form method="POST" action="{{ route('enclosures.destroy', $enclosure->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn danger-btn">Yes, Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <p>No enclosures found.</p>
        @endforelse
    </div>

    <div class="pagination-buttons" style="margin-top: 2rem; text-align: center;">
        @if ($enclosures->onFirstPage())
            <span class="disabled-button">← Previous</span>
        @else
            <a href="{{ $enclosures->previousPageUrl() }}" class="pagination-button">← Previous</a>
        @endif

        @if ($enclosures->hasMorePages())
            <a href="{{ $enclosures->nextPageUrl() }}" class="pagination-button">Next →</a>
        @else
            <span class="disabled-button">Next →</span>
        @endif
    </div>

    <script>
        function openModal(id) {
            document.getElementById('modal-' + id).classList.remove('hidden');
        }
    
        function closeModal(id) {
            document.getElementById('modal-' + id).classList.add('hidden');
        }
    </script>

@endsection
