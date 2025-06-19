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
use App\Http\Controllers\SignatureController;


use App\Http\Controllers\StaffDashboardController;
use App\Http\Controllers\RisSlipController;
use App\Http\Controllers\StockCardController;
use App\Http\Controllers\Api\NotificationController;

use Illuminate\Support\Facades\Broadcast;

// Add this line BEFORE your middleware groups
Broadcast::routes(['middleware' => ['web', 'auth:sanctum']]);


Route::get('/', function () {
    return view('welcome');
});

Route::get('/run-storage-link', function () {
    Artisan::call('storage:link');
    return 'Symlink created!';
});

// Test broadcast route
Route::get('/test-broadcast', function () {
    $risSlip = \App\Models\RisSlip::first();

    if (!$risSlip) {
        return 'No RIS slips found. Create one first.';
    }

    try {
        // Test if broadcasting is working
        $event = new \App\Events\RequisitionStatusUpdated($risSlip, 'test');
        broadcast($event);

        return response()->json([
            'message' => 'Event broadcast! Check Pusher debug console.',
            'event_data' => $event->broadcastWith(),
            'channels' => ['admin-notifications'],
            'broadcast_driver' => config('broadcasting.default')
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
})->middleware('auth');

// Combine admin + staff + cao under one main group, but nest role checks
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    Route::get('/api/initial-counts', [NotificationController::class, 'getInitialCounts'])->middleware(['admin-cao']);
    Route::get('/api/user-initial-counts', [NotificationController::class, 'getUserInitialCounts']);

    // Shared routes (accessible by admin, cao, and staff)
    Route::get('/ris', [RisSlipController::class, 'index'])->name('ris.index');
    Route::post('/ris', [RisSlipController::class, 'store'])->name('ris.store');
    Route::get('/ris/{risSlip}', [RisSlipController::class, 'show'])->name('ris.show');
    Route::get('/ris/{risSlip}/print', [RisSlipController::class, 'print'])->name('ris.print');
    Route::get('/ris/{risSlip}/print', [RisSlipController::class, 'print'])->name('ris.print');

    Route::post('/ris/{risSlip}/receive', [RisSlipController::class, 'receive'])->name('ris.receive');

    Route::get('/stock-cards', [StockCardController::class, 'index'])->name('stock-cards.index');
    Route::get('/stock-cards/{supplyId}', [StockCardController::class, 'show'])->name('stock-cards.show');
    Route::get('/stock-cards/{supplyId}/export-pdf', [StockCardController::class, 'exportPdf'])->name('stock-cards.export-pdf');

    // Supply Ledger Card routes
    Route::get('/supply-ledger-cards', [App\Http\Controllers\SupplyLedgerCardController::class, 'index'])->name('supply-ledger-cards.index');
    Route::get('/supply-ledger-cards/{supplyId}', [App\Http\Controllers\SupplyLedgerCardController::class, 'show'])->name('supply-ledger-cards.show');
    Route::get('/supply-ledger-cards/{supplyId}/export-pdf', [App\Http\Controllers\SupplyLedgerCardController::class, 'exportPdf'])->name('supply-ledger-cards.export-pdf');

    // Beginning Balance creation route
    Route::post('/stocks/create-beginning-balances', [App\Http\Controllers\SupplyStockController::class, 'createBeginningBalances'])
        ->name('stocks.create-beginning-balances');


    // Signature Management Routes (add these new routes)
    Route::post('/signature/upload', [SignatureController::class, 'store'])->name('signature.upload');
    Route::delete('/signature/delete', [SignatureController::class, 'delete'])->name('signature.delete');

    //Route::get('/user-notifications', [NotificationController::class, 'getUserNotifications'])->middleware(['auth:sanctum', 'verified']);


    /**
     * ------------------
     *  ADMIN & CAO ROUTES (Same access level)
     * ------------------
     */
    Route::middleware(['admin-cao'])->group(function () {

        // these now share your normal web session cookie

        // Dashboard Routes
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/assets-dashboard', [DashboardController::class, 'assets'])->name('assets.dashboard');

        // Example route, adjusting the URI as you see fit:
        Route::post('/users', [UserController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [UserController::class, 'updateUser'])->name('users.update');
        // Add this line inside the admin-cao middleware group
        Route::post('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        // routes/web.php

        // Supplies
        Route::get('/supplies', [SupplyController::class, 'index'])->name('supplies.index');
        Route::post('/supplies', [SupplyController::class, 'store'])->name('supplies.store');
        Route::put('/supplies/{supply}', [SupplyController::class, 'update'])->name('supplies.update');
        Route::delete('/supplies/{supply}', [SupplyController::class, 'destroy'])->name('supplies.destroy');

        // Supplies Stocks
        Route::resource('stocks', SupplyStockController::class)->except(['create','show']);

        // Supplies transactions
        Route::get  ('supply-transactions',         [SupplyTransactionController::class,'index'])->name('supply-transactions.index');
        Route::post ('supply-transactions',         [SupplyTransactionController::class,'store'])->name('supply-transactions.store');
        Route::get  ('supply-transactions/{txn}',   [SupplyTransactionController::class,'show'])->name('supply-transactions.show');

        // RIS
        Route::post('/ris/{risSlip}/approve', [RisSlipController::class, 'approve'])->name('ris.approve');
        Route::post('/ris/{risSlip}/decline', [RisSlipController::class, 'decline'])->name('ris.decline');
        Route::post('/ris/{risSlip}/issue', [RisSlipController::class, 'issue'])->name('ris.issue');

        // Add these routes to your existing web.php file within the middleware group

        // RSMI (Report of Supplies and Materials Issued) routes
        // Add these routes to your existing web.php file within the middleware group

// RSMI (Report of Supplies and Materials Issued) routes
        Route::get('/rsmi', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'index'])->name('rsmi.index');
        Route::get('/rsmi/generate', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'generate'])->name('rsmi.generate');
        Route::get('/rsmi/detailed', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'detailed'])->name('rsmi.detailed');
        Route::get('/rsmi/analytics', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'analytics'])->name('rsmi.analytics');
        Route::get('/rsmi/yearly-analytics', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'yearlyAnalytics'])->name('rsmi.yearly-analytics');
        Route::get('/rsmi/summary', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'summary'])->name('rsmi.summary');
        Route::get('/rsmi/export-pdf', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'exportPdf'])->name('rsmi.export-pdf');
        Route::get('/rsmi/monthly-comparison', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'monthlyComparison'])->name('rsmi.monthly-comparison');
        Route::get('/rsmi/export-pdf-formatted', [App\Http\Controllers\ReportSuppliesMaterialsIssuedController::class, 'exportPdfFormatted'])->name('rsmi.export-pdf-formatted');

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
        Route::get('/property/{property}/edit', [PropertyController::class, 'edit'])->name('property.edit');
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


            // ────────── RIS (Requisition & Issue Slip) ──────────
        Route::resource('ris-slips', RisSlipController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->names('ris-slips');                       // ris-slips.index, ris-slips.create, …

    });

});
