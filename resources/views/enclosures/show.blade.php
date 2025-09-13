@extends('layouts.layout')

@section('title', 'Enclosure: ' . $enclosure->name)

@section('content')
    <div class="enclosure-info-card">
        <div class="enclosure-name">
            <h1>{{ $enclosure->name }}</h1>
        </div>

        <div class="enclosure-meta">
            <div><span>🐾 Feeding Time:</span> {{ \Carbon\Carbon::parse($enclosure->feeding_at)->format('H:i') }}</div>
            <div><span>📦 Limit:</span> {{ $enclosure->limit }} animals</div>
            <div><span>📅 Created At:</span> {{ $enclosure->created_at->format('Y-m-d') }}</div>
        </div>

        <div class="enclosure-type-badge">
            @if ($enclosure->is_predator)
                <img src="{{ asset('images/carnivore_symbol.png') }}" alt="Carnivore enclosure">
                <span class="enclosure-type-text">Carnivore Enclosure</span>
            @else
                <img src="{{ asset('images/herbivore_symbol.png') }}" alt="Herbivore enclosure">
                <span class="enclosure-type-text">Herbivore Enclosure</span>
            @endif
        </div>
    </div>

    <div class="animal-list-wrapper">
        <h2>Animals in this enclosure:</h2>
    
        @if ($enclosure->animals->isEmpty())
            <p>No animals in this enclosure yet.</p>
        @else
            <div class="animal-list">
                @foreach ($enclosure->animals as $animal)
                <div class="animal-card" style="background-image: url('{{ asset($animal->image_path) }}')">
                        <div class="overlay-buttons">
                            @auth
                                @if (auth()->user()->admin)
                                    <a href="{{ route('animals.edit', $animal->id) }}" class="overlay-btn edit-btn" title="Edit">✏️</a>
                                    <form method="POST" action="{{ route('animals.destroy', $animal->id) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="overlay-btn delete-btn" title="Delete">🗑️</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
    
                        <div class="animal-card-details">
                            <h3>{{ $animal->name }}</h3>
                            <p><strong>Species:</strong> {{ $animal->species }}</p>
                            <p><strong>Born At:</strong> {{ $animal->born_at->format('Y-m-d')}}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    

    <div class="page-actions">
        <a href="{{ route('enclosures.index') }}">← Back to all enclosures</a>

        @auth
            @if (auth()->user()->admin)
                <a href="{{ route('enclosures.edit', $enclosure->id) }}">✏️ Edit Enclosure</a>
                <form method="POST" action="{{ route('enclosures.destroy', $enclosure->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this enclosure?')">
                        🗑️ Delete Enclosure
                    </button>
                </form>
            @endif
        @endauth
    </div>
@endsection
