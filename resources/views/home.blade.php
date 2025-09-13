@extends('layouts.layout')

@section('title', 'home')


@section('content')
    <div class="welcome-section">
        <h1>Welcome to My Zoo</h1>
        <p>Explore our amazing collection of animals and enclosures.</p>

        <div class="stats-container">
            <p>Enclosures: {{ $enclosureCount }}</p>
            <p>Animals: {{ $animalCount }}</p>
        </div>
    </div>

    
    <div class="task-page">
        <h1>Your Feeding Tasks</h1>



        @forelse ($enclosures as $enclosure)
            @php
                $feedingTime = \Carbon\Carbon::today()->setTimeFromTimeString($enclosure->feeding_at);
            @endphp

            @if ($feedingTime->isFuture())
                <div class="task-card">
                    <div class="task-header">
                        <div class="task-title">{{ $enclosure->name }}</div>
                        <div class="task-time">🕒 {{ $feedingTime->format('H:i') }}</div>
                    </div>
                    <div class="task-note">
                        Don't forget to bring the right food for this enclosure!
                    </div>
                    <button class="task-button" onclick="window.location.href='{{ route('enclosures.show', $enclosure->id) }}'">View Enclosure</button>
                </div>
            @endif
        @empty
            <p>No tasks assigned yet. Enjoy your break! 🐢</p>
        @endforelse


    </div>

    <div class></div>

@endsection
