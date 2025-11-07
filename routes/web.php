<?php

use App\Http\Controllers\Web\EventController;
use App\Http\Controllers\Web\PetitionController;
use App\Http\Controllers\Web\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

// Petitions
Route::controller(PetitionController::class)->group(function () {
    Route::get('/petitions', 'index')->name('petitions.index');
    Route::get('/petitions/create', 'create')->name('petitions.create');
    Route::post('/petitions', 'store')->name('petitions.store');
    Route::get('/petitions/{petition}', 'show')->name('petitions.show');
    Route::post('/petitions/{petition}/vote', 'vote')->name('petitions.vote');
    Route::post('/petitions/{petition}/comment', 'comment')->name('petitions.comment');
});

// Events
Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index')->name('events.index');
    Route::get('/events/create', 'create')->name('events.create');
    Route::post('/events', 'store')->name('events.store');
    Route::get('/events/{event}', 'show')->name('events.show');
    Route::post('/events/{event}/rsvp', 'rsvp')->name('events.rsvp');
});

// Profile
Route::controller(ProfileController::class)->group(function () {
    Route::get('/profile/{user}', 'show')->name('profile.show');
    Route::get('/profile', 'edit')->middleware('auth')->name('profile.edit');
    Route::put('/profile', 'update')->middleware('auth')->name('profile.update');
    Route::put('/profile/password', 'updatePassword')->middleware('auth')->name('profile.password');
});
