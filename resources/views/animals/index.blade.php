@extends('layouts.layout')

@section('title', 'Deleted Animals List')

@section('content')
    <div class="deleted-animals-page">
        <h1>Deleted Animals</h1>

        @if ($animals->isEmpty())
            <p>No deleted animals found.</p>
        @else
            <div class="deleted-animal-cards">
                @foreach ($animals as $animal)
                    @php
                        $hasValidEnclosure = false;
                        foreach ($enclosures as $enclosure) {
                            $isFull = $enclosure->animals->count() >= $enclosure->limit;
                            $typeMismatch = $animal->is_predator !== $enclosure->is_predator;
                            if (!$isFull && !$typeMismatch) {
                                $hasValidEnclosure = true;
                                break;
                            }
                        }
                    @endphp

                    <div class="task-card">
                        <div class="task-header">
                            <div class="task-title">{{ $animal->name }}</div>
                            <div class="task-time">{{ $animal->species }}</div>
                        </div>

                        <div class="task-note">
                            Enclosure: 
                            <form method="POST" action="{{ route('animals.restore', $animal->id) }}">
                                @csrf
                                @method('PUT')

                                <select name="enclosure_id" class="enclosure-select" required>
                                    <option value="" disabled selected>-- Select Enclosure --</option>

                                    @foreach ($enclosures as $enclosure)
                                        @php
                                            $isFull = $enclosure->animals->count() >= $enclosure->limit;
                                            $typeMismatch = $animal->is_predator !== $enclosure->is_predator;
                                            $disabled = $isFull || $typeMismatch;
                                        @endphp

                                        <option 
                                            value="{{ $enclosure->id }}" 
                                            {{ $disabled ? 'disabled' : '' }}
                                        >
                                            {{ $enclosure->name }}
                                            @if ($isFull)
                                                (Full)
                                            @elseif ($typeMismatch)
                                                (Wrong Type)
                                            @else
                                                ({{ $enclosure->limit - $enclosure->animals->count() }} slots left)
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                <button class="task-button" type="submit" {{ !$hasValidEnclosure ? 'disabled' : '' }}>
                                    🔁 Restore
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
