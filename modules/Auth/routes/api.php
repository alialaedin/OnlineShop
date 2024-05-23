<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use Modules\Auth\Http\Controllers\Api\Customer\AuthController as CustomerAuthController;


// Admins
Route::prefix('/admin')->group(function () {

	Route::post('/login', [AdminAuthController::class, 'login'])->middleware('guest');
	Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin-api');
});

// Customers
Route::prefix('/customer')->group(function () {

	Route::post('/login', [CustomerAuthController::class, 'login']);
	Route::post('/register-login', [CustomerAuthController::class, 'registerLogin']);
	Route::post('/send-token', [CustomerAuthController::class, 'sendToken']);
	Route::post('/verify', [CustomerAuthController::class, 'verify']);
	Route::post('/register', [CustomerAuthController::class, 'register']);
	Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth:customer-api');
});
