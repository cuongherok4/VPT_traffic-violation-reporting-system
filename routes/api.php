<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ViolationReportController;
use Illuminate\Support\Facades\Route;

Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('auth/me', [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});

Route::get('dashboard', DashboardController::class);

Route::apiResource('reports', ViolationReportController::class)
    ->only(['index', 'store', 'show']);

Route::patch('reports/{report}/status', [ViolationReportController::class, 'updateStatus'])
    ->middleware(['auth:sanctum', 'role:admin']);
