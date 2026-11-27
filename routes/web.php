<?php

use App\Http\Controllers\ApartmentController;
use App\Http\Controllers\Host\ApartmentController as HostApartmentController;
use Illuminate\Support\Facades\Route;

// Area pubblica
Route::get('/', [ApartmentController::class, 'index'])->name('home');
Route::get('/alloggi/{apartment}', [ApartmentController::class, 'show'])->name('apartments.show');

// Area host (utenti autenticati)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/host/alloggi')->name('dashboard');

    Route::resource('host/alloggi', HostApartmentController::class)
        ->parameters(['alloggi' => 'apartment'])
        ->except('show')
        ->names('host.apartments');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
