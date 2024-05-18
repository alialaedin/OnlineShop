<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\Api\Admin\AdminController;
use Modules\Admin\Http\Controllers\Api\Admin\ProfileController;

Route::prefix('admin/admins')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [AdminController::class, 'index'])->middleware('can:view admins');
	Route::get('/{admin}', [AdminController::class, 'show'])->middleware('can:view admins');
	Route::post('/', [AdminController::class, 'store'])->middleware('can:create admins');
	Route::patch('/{admin}', [AdminController::class, 'update'])->middleware('can:edit admins');
	Route::delete('/{admin}', [AdminController::class, 'destroy'])->middleware('can:delete admins');
});

Route::prefix('admin/profile')->middleware('auth:admin-api')->group(function() {
	Route::get('/', [ProfileController::class, 'showProfile']);
	Route::patch('/', [ProfileController::class, 'updateProfile']);
	Route::put('/change-password', [ProfileController::class, 'changePassword']);
});
