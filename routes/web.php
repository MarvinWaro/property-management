<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EndUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
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

});

