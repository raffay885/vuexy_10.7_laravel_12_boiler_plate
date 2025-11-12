<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerAssetController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EstimateController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{id}/details', [CustomerController::class, 'details'])->name('customers.details');

    // Customer Assets
    Route::resource('customer-assets', CustomerAssetController::class);
    Route::get('customer-assets/{assetId}/details', [CustomerAssetController::class, 'details'])->name('customer-assets.details');

    // Estimates
    Route::resource('estimates', EstimateController::class);

    // Role
    Route::resource('roles', RoleController::class);
});

require __DIR__.'/auth.php';
