<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FineReceiptController;
use App\Http\Controllers\NewsArticleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserNotificationController;
use App\Http\Controllers\ViolationLookupController;
use App\Http\Controllers\ViolationReportController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

Route::get('dashboard', DashboardController::class);
Route::get('violations/lookup', ViolationLookupController::class)
    ->middleware('optional.auth');

Route::apiResource('categories', ProductCategoryController::class)
    ->only(['index', 'show']);

Route::apiResource('products', ProductController::class)
    ->only(['index', 'show']);

Route::apiResource('news-articles', NewsArticleController::class)
    ->only(['index', 'show']);

Route::apiResource('reports', ViolationReportController::class)
    ->only(['index', 'store', 'show']);

Route::patch('reports/{report}/status', [ViolationReportController::class, 'updateStatus'])
    ->middleware(['auth:sanctum', 'role:admin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('orders', OrderController::class)
        ->only(['index', 'store', 'show']);

    Route::get('notifications', [UserNotificationController::class, 'index']);
    Route::patch('notifications/{notification}/read', [UserNotificationController::class, 'markAsRead']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('reports/{report}/fine-receipt', [FineReceiptController::class, 'store']);
    Route::get('fine-receipts/{fineReceipt}', [FineReceiptController::class, 'show']);
    Route::patch('fine-receipts/{fineReceipt}', [FineReceiptController::class, 'update']);

    Route::apiResource('categories', ProductCategoryController::class)
        ->only(['store']);

    Route::apiResource('products', ProductController::class)
        ->only(['store', 'update', 'destroy']);

    Route::apiResource('news-articles', NewsArticleController::class)
        ->only(['store', 'update', 'destroy']);
});
