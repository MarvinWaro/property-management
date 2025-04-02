<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EndUserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/assets-dashboard', [DashboardController::class, 'assets'])->name('assets.dashboard');

    // Suplies Routes
    Route::get('/supplies', function () {
        return view('supplies.index');
    })->name('supplies.index');

    // Suppliers Routes
    Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
    Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
    Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
    Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
    Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

    // Categories Routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // End Users Routes
    Route::get('/end_users', [EndUserController::class, 'index'])->name('end_users.index');
    Route::get('/end_users/create', [EndUserController::class, 'create'])->name('end_users.create');
    Route::post('/end_users', [EndUserController::class, 'store'])->name('end_users.store');
    Route::get('/end_users/{hashedId}/edit', [EndUserController::class, 'edit'])->name('end_users.edit');
    Route::put('/end_users/{endUser}', [EndUserController::class, 'update'])->name('end_users.update');
    Route::delete('/end_users/{endUser}', [EndUserController::class, 'destroy'])->name('end_users.destroy');

    // Location Routes
    Route::get('/location', [LocationController::class, 'index'])->name('location.index');
    Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
    Route::post('/location', [LocationController::class, 'store'])->name('location.store');
    Route::get('/location/{hashedId}/edit', [LocationController::class, 'edit'])->name('location.edit');
    Route::put('/location/{location}', [LocationController::class, 'update'])->name('location.update');
    Route::delete('/location/{location}', [LocationController::class, 'destroy'])->name('location.destroy');

    // Property Routes
    Route::get('/property', [PropertyController::class, 'index'])->name('property.index');
    Route::get('/property/create', [PropertyController::class, 'create'])->name('property.create');
    Route::post('/property', [PropertyController::class, 'store'])->name('property.store');
    Route::get('/property/{hashedId}/edit', [PropertyController::class, 'edit'])->name('property.edit');
    Route::put('/property/{property}', [PropertyController::class, 'update'])->name('property.update');
    Route::delete('/property/{property}', [PropertyController::class, 'destroy'])->name('property.destroy');
    Route::get('/property/{property}', [PropertyController::class, 'view'])->name('property.view');
    Route::get('/property/{property}/download-qr', [PropertyController::class, 'downloadQr'])->name('property.download-qr');
});


Route::get('/home',[AdminController::class,'index'])->name('home');
