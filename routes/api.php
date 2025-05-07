<?php
// routes/api.php - Updated version

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Notification routes - Changed to use auth:web instead of auth:sanctum
// Route::middleware('auth')->group(function () {
//     Route::get('/pending-requisitions', [NotificationController::class, 'getPendingCount']);
//     Route::post('/mark-requisitions-viewed', [NotificationController::class, 'markAsViewed']);
// });
