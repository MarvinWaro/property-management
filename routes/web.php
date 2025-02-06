<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EndUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum',config('jetstream.auth_session'),'verified',])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/end_users', [EndUserController::class, 'index'])->name('end_users.index');
    Route::get('/end_users/create', [EndUserController::class, 'create'])->name('end_users.create');
    Route::post('/end_users', [EndUserController::class, 'store'])->name('end_users.store');


});
