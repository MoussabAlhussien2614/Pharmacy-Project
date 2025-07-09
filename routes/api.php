<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\NotificationController;

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


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

  Route::get('/user', [AuthController::class, 'user']);

    // Categories
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);


    // Medicines
    Route::get('/medicines', [MedicineController::class, 'index']);
    Route::get('/medicines/{medicine}', [MedicineController::class, 'show']);


    // Cart
    Route::get('/cart', [CartController::class, 'show']);
    Route::post('/cart', [CartController::class, 'add']);
    Route::delete('/cart/{id}', [CartController::class, 'remove']);

    // Orders
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/mine', [OrderController::class, 'myOrders']);
    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {

        // Categories
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::post('/categories/{category}', [CategoryController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);

        // Medicines
        Route::post('/medicines', [MedicineController::class, 'store']);
        Route::post('/medicines/{medicine}', [MedicineController::class, 'update']);
        Route::delete('/medicines/{medicine}', [MedicineController::class, 'destroy']);

        // Admin Orders
        Route::get('/admin/orders', [OrderController::class, 'index']);
        Route::post('/admin/orders/{id}/status', [OrderController::class, 'updateStatus']);

        // Reports
        Route::get('/reports/low-stock', [ReportController::class, 'lowStock']);
        Route::get('/reports/expiring', [ReportController::class, 'expiring']);
        Route::post('/reports/sales', [ReportController::class, 'sales']);

        // Audit Logs
        Route::get('/audit-logs', [AuditLogController::class, 'index']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
    });
});
