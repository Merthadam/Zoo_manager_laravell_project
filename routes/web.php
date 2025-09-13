<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Animal;
use App\Models\Enclosure;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EnclosureController;
use App\Http\Controllers\AnimalController;
use App\Http\Middleware\IsAdmin;

//
// Home route – user-specific enclosure task list
//
Route::get('/', function () {
    $user = Auth::user();

    $enclosureCount = Enclosure::count();
    $animalCount = Animal::count();
    $enclosures = $user->enclosures()->with('animals')->orderBy('feeding_at')->get();

    return view('home', compact('enclosureCount', 'animalCount', 'enclosures'));
})->middleware(['auth'])->name('home');

//
// Optional: dashboard
//
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

//
// Profile settings
//
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//
// Enclosure routes using resource (split for auth control)
//

// Admin-only: create, store, edit, update, destroy
Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::resource('enclosures', EnclosureController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy']);
});

// Authenticated users: index and show
Route::middleware(['auth'])->group(function () {
    Route::resource('enclosures', EnclosureController::class)
        ->only(['index', 'show']);
});

//
// Animal routes using AnimalController – defined like EnclosureController
//

// Admin-only: create, store, edit, update, destroy + index (archived)
Route::middleware(['auth', 'verified', IsAdmin::class])->group(function () {
    Route::resource('animals', AnimalController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy', 'index']);

    // Optional restore route
    Route::put('/animals/{id}/restore', [AnimalController::class, 'restore'])->name('animals.restore');
});

// Authenticated users: show only
Route::middleware(['auth'])->group(function () {
    Route::resource('animals', AnimalController::class)
        ->only(['show']);
});

//
// Breeze-auth routes
//
require __DIR__.'/auth.php';
