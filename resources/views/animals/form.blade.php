@extends('layouts.layout')

@section('title', 'Animal Form')

@section('content')
    <div class="form-container">
        <h1>{{ isset($animal) ? 'Edit' : 'Add' }} Animal</h1>

        <form method="POST" action="{{ isset($animal) ? route('animals.update', $animal->id) : route('animals.store') }}" enctype="multipart/form-data">
            @csrf
            @isset($animal)
                @method('PUT')
            @endisset

            <div class="form-group">
                <label for="name">Name</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $animal->name ?? '') }}"
                    required
                >
                @error('name')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="species">Species</label>
                <select name="species" id="species" required>
                    <option disabled value="">-- Select a Dinosaur --</option>
                    @foreach ($speciesList as $species)
                        <option value="{{ $species }}" {{ old('species', $animal->species ?? '') === $species ? 'selected' : '' }}>
                            {{ $species }}
                        </option>
                    @endforeach
                </select>
                @error('species')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="born_at">Born At</label>
                <input
                    type="date"
                    id="born_at"
                    name="born_at"
                    value="{{ old('born_at', isset($animal->born_at) ? $animal->born_at->format('Y-m-d') : '') }}"
                    max="{{ now()->format('Y-m-d') }}"
                    required
                >
                @error('born_at')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- Enclosure Dropdown --}}
            <div class="form-group">
                <label for="enclosure_id">Enclosure</label>
                <select name="enclosure_id" id="enclosure_id" required>
                    <option disabled value="">-- Choose Enclosure --</option>
                    @foreach ($enclosures as $enclosure)
                        <option
                            value="{{ $enclosure->id }}"
                            {{ old('enclosure_id', $animal->enclosure_id ?? '') == $enclosure->id ? 'selected' : '' }}>
                            {{ $enclosure->name }}
                        </option>
                    @endforeach
                </select>
                @error('enclosure_id')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image Upload --}}
            <div class="form-group">
                <label for="image_path">Image</label>
                <input
                    type="file"
                    id="image_path"
                    name="image_path"
                    accept="image/*"
                >
                @error('image_path')
                    <p class="error-message">{{ $message }}</p>
                @enderror
            </div>                
            <div class="form-actions">
                <button type="submit" class="btn">Submit</button>
            
                @if (isset($animal))
                    <a href="{{ route('enclosures.show', $animal->enclosure_id) }}" class="btn btn-secondary">Back</a>
                @else
                    <a href="{{ route('enclosures.index') }}" class="btn btn-secondary">Back to Enclosures</a>
                @endif
            </div>
        </form>
    </div>
@endsection
