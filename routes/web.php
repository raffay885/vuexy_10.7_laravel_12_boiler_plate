<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerAssetController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EstimateController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Webhook\WebhookController;
use App\Http\Controllers\Admin\ProfileController;

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

    // Invoices
    Route::resource('invoices', InvoiceController::class);

    // Role
    Route::resource('roles', RoleController::class);

    // Admin Users
    Route::resource('users', UserController::class);

    // Profile
    Route::get('profile', [ProfileController::class, 'profile'])->name('profile.index');
    Route::post('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::get('profile/changePassword', [ProfileController::class, 'changePassword'])->name('profile.changePassword.index');
    Route::post('profile/changePassword', [ProfileController::class, 'updatePassword'])->name('profile.changePassword.update');
});

// Webhooks
Route::post('webhook/approveEstimate', [WebhookController::class, 'approveEstimate']);

require __DIR__.'/auth.php';
