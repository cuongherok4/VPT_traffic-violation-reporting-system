<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ViolationReportController;
use Illuminate\Support\Facades\Route;

Route::get('dashboard', DashboardController::class);

Route::apiResource('reports', ViolationReportController::class)
    ->only(['index', 'store', 'show']);

Route::patch('reports/{report}/status', [ViolationReportController::class, 'updateStatus']);
