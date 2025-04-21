<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CategoryController;
// use App\Http\Controllers\EndUserController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupplyController;
use App\Http\Controllers\SupplyStockController;
use App\Http\Controllers\SupplyTransactionController;


use App\Http\Controllers\StaffDashboardController;


Route::get('/', function () {
    return view('welcome');
});

// Combine admin + staff under one main group, but nest role checks
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    /**
     * ------------------
     *  ADMIN ROUTES
     * ------------------
     */
    Route::middleware(['role:admin'])->group(function () {
        // Dashboard Routes
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/assets-dashboard', [DashboardController::class, 'assets'])->name('assets.dashboard');

        // Example route, adjusting the URI as you see fit:
        Route::post('/users', [UserController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('users.update');
        // routes/web.php

        // Supplies
        Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies.index');
        Route::post('/supplies', [SupplyController::class, 'store'])->name('supplies.store');
        Route::put('/supplies/{supply}', [SupplyController::class, 'update'])->name('supplies.update');
        Route::delete('/supplies/{supply}', [SupplyController::class, 'destroy'])->name('supplies.destroy');

        Route::resource('supply-stocks', SupplyStockController::class)->except(['create','show']);

        Route::get  ('supply-transactions',         [SupplyTransactionController::class,'index'])->name('supply-transactions.index');
        Route::post ('supply-transactions',         [SupplyTransactionController::class,'store'])->name('supply-transactions.store');
        Route::get  ('supply-transactions/{txn}',   [SupplyTransactionController::class,'show'])->name('supply-transactions.show');


        // Departments
        Route::get('/departments', [DepartmentController::class, 'index'])->name('departments.index');
        Route::get('/departments/create', [DepartmentController::class, 'create'])->name('departments.create');
        Route::post('/departments', [DepartmentController::class, 'store'])->name('departments.store');
        Route::get('/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('departments.edit');
        Route::put('/departments/{department}', [DepartmentController::class, 'update'])->name('departments.update');
        Route::delete('/departments/{department}', [DepartmentController::class, 'destroy'])->name('departments.destroy');
        Route::get('/departments/{department}', [DepartmentController::class, 'view'])->name('departments.view');

        // Designations
        Route::get('/designations', [DesignationController::class, 'index'])->name('designations.index');
        Route::get('/designations/create', [DesignationController::class, 'create'])->name('designations.create');
        Route::post('/designations', [DesignationController::class, 'store'])->name('designations.store');
        Route::get('/designations/{designation}/edit', [DesignationController::class, 'edit'])->name('designations.edit');
        Route::put('/designations/{designation}', [DesignationController::class, 'update'])->name('designations.update');
        Route::delete('/designations/{designation}', [DesignationController::class, 'destroy'])->name('designations.destroy');


        // Suppliers
        Route::get('/supplier', [SupplierController::class, 'index'])->name('supplier.index');
        Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create');
        Route::post('/supplier', [SupplierController::class, 'store'])->name('supplier.store');
        Route::get('/supplier/{id}/edit', [SupplierController::class, 'edit'])->name('supplier.edit');
        Route::put('/supplier/{id}', [SupplierController::class, 'update'])->name('supplier.update');
        Route::delete('/supplier/{id}', [SupplierController::class, 'destroy'])->name('supplier.destroy');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // End Users
        // Route::get('/end_users', [EndUserController::class, 'index'])->name('end_users.index');
        // Route::get('/end_users/create', [EndUserController::class, 'create'])->name('end_users.create');
        // Route::post('/end_users', [EndUserController::class, 'store'])->name('end_users.store');
        // Route::get('/end_users/{hashedId}/edit', [EndUserController::class, 'edit'])->name('end_users.edit');
        // Route::put('/end_users/{endUser}', [EndUserController::class, 'update'])->name('end_users.update');
        // Route::delete('/end_users/{endUser}', [EndUserController::class, 'destroy'])->name('end_users.destroy');

        // Location
        Route::get('/location', [LocationController::class, 'index'])->name('location.index');
        Route::get('/location/create', [LocationController::class, 'create'])->name('location.create');
        Route::post('/location', [LocationController::class, 'store'])->name('location.store');
        Route::get('/location/{hashedId}/edit', [LocationController::class, 'edit'])->name('location.edit');
        Route::put('/location/{location}', [LocationController::class, 'update'])->name('location.update');
        Route::delete('/location/{location}', [LocationController::class, 'destroy'])->name('location.destroy');

        // Property
        Route::get('/property', [PropertyController::class, 'index'])->name('property.index');
        Route::get('/property/create', [PropertyController::class, 'create'])->name('property.create');
        Route::post('/property', [PropertyController::class, 'store'])->name('property.store');
        Route::get('/property/{hashedId}/edit', [PropertyController::class, 'edit'])->name('property.edit');
        Route::put('/property/{property}', [PropertyController::class, 'update'])->name('property.update');
        Route::delete('/property/{property}', [PropertyController::class, 'destroy'])->name('property.destroy');
        Route::get('/property/{property}', [PropertyController::class, 'view'])->name('property.view');
        Route::get('/property/{property}/download-qr', [PropertyController::class, 'downloadQr'])->name('property.download-qr');
    });

    /**
     * ------------------
     *  STAFF ROUTES
     * ------------------
     */
    Route::middleware(['role:staff'])->group(function () {
        Route::get('/staff-dashboard', [StaffDashboardController::class, 'index'])
            ->name('staff.dashboard');

        Route::get('/force-change-password', [StaffDashboardController::class, 'showChangePasswordForm'])
            ->name('user.force-change-password');

        Route::post('/force-change-password', [StaffDashboardController::class, 'updatePassword'])
            ->name('user.force-change-password.update');
    });

});

