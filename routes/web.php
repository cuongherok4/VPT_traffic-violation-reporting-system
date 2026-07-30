<?php

use App\Http\Controllers\Web\AdminDashboardController;
use App\Http\Controllers\Web\AdminProductController;
use App\Http\Controllers\Web\AdminReportController;
use App\Http\Controllers\Web\AuthSessionController;
use App\Http\Controllers\Web\CitizenReportController;
use App\Http\Controllers\Web\LookupController;
use App\Http\Controllers\Web\ShopController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/lookup');

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthSessionController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthSessionController::class, 'login'])->name('login.store');
    Route::get('register', [AuthSessionController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthSessionController::class, 'register'])->name('register.store');
});

Route::get('lookup', [LookupController::class, 'index'])->name('lookup.index');
Route::get('shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('shop/{product}', [ShopController::class, 'show'])->name('shop.show');

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthSessionController::class, 'logout'])->name('logout');
    Route::post('shop/{product}/order', [ShopController::class, 'order'])->name('shop.order');

    Route::get('reports', [CitizenReportController::class, 'index'])->name('citizen.reports.index');
    Route::get('reports/create', [CitizenReportController::class, 'create'])->name('citizen.reports.create');
    Route::post('reports', [CitizenReportController::class, 'store'])->name('citizen.reports.store');
    Route::get('reports/{report}', [CitizenReportController::class, 'show'])->name('citizen.reports.show');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('dashboard', AdminDashboardController::class)->name('dashboard');
        Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/{report}', [AdminReportController::class, 'show'])->name('reports.show');
        Route::patch('reports/{report}', [AdminReportController::class, 'update'])->name('reports.update');

        Route::resource('products', AdminProductController::class)->except('show');
    });
});
