<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PropertyController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // End Users Routes
    Route::get('/end_users', [EndUserController::class, 'index'])->name('end_users.index');
    Route::get('/end_users/create', [EndUserController::class, 'create'])->name('end_users.create');
    Route::post('/end_users', [EndUserController::class, 'store'])->name('end_users.store');
    Route::get('/end_users/{endUser}/edit', [EndUserController::class, 'edit'])->name('end_users.edit');
    Route::put('/end_users/{endUser}', [EndUserController::class, 'update'])->name('end_users.update');
    Route::delete('/end_users/{endUser}', [EndUserController::class, 'destroy'])->name('end_users.destroy');

    // Location Routes
    Route::get('/location', [LocationController::class, 'index'])->name('location.index');
    Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
    Route::post('/location', [LocationController::class, 'store'])->name('location.store');
    Route::get('/location/{location}/edit', [LocationController::class, 'edit'])->name('location.edit');
    Route::put('/location/{location}', [LocationController::class, 'update'])->name('location.update');
    Route::delete('/location/{location}', [LocationController::class, 'destroy'])->name('location.destroy');

    // Property Routes
    Route::get('/property', [PropertyController::class, 'index'])->name('property.index');
    

});

