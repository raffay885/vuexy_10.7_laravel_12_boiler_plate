<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\RoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
})->name('admin.index');

// 
Route::resource('roles', RoleController::class);

// Route::get('/dashboard', function () {
//     return view('admin.index');
// });

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__.'/auth.php';
