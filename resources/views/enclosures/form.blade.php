@extends('layouts.layout')

@section('title', isset($enclosure) ? 'Edit Enclosure' : 'Add Enclosure')

@section('content')
    <div class="form-container">
        <h1>{{ isset($enclosure) ? 'Edit' : 'Add' }} Enclosure</h1>

        <form method="POST" action="{{ isset($enclosure) ? route('enclosures.update', $enclosure->id) : route('enclosures.store') }}">
            @csrf
            @isset($enclosure)
                @method('PUT')
            @endisset

            <div class="form-group">
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $enclosure->name ?? '') }}"
                    required
                >
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="limit">Limit</label>
                <input
                    type="number"
                    id="limit"
                    name="limit"
                    value="{{ old('limit', $enclosure->limit ?? '') }}"
                    required
                >
                @error('limit')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>
            @if (!isset($enclosure))
                <div class="form-group">
                    <label for="is_predator">Predator</label>
                    <input type="hidden" name="is_predator" value="0">
                    <input
                        type="checkbox"
                        id="is_predator"
                        name="is_predator"
                        value="1"
                        {{ old('is_predator', $enclosure->is_predator ?? false) ? 'checked' : '' }}>
                </div>
            @else

            @endif

            <div class="form-group">
                <label for="feeding_at">Feeding Time</label>
                <input type="time" name="feeding_at"
                    value="{{ old('feeding_at', \Carbon\Carbon::parse($enclosure->feeding_at ?? now())->format('H:i')) }}"
                    required>
                @error('feeding_at')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn">Submit</button>
                <a href="{{ route('enclosures.index') }}" class="btn btn-secondary">Back</a>
            </div>

        </form>
    </div>
@endsection
