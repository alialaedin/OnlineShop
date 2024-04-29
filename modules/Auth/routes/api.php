<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use Modules\Auth\Http\Controllers\Api\Customer\AuthController as CustomerAuthController;

/*
 *--------------------------------------------------------------------------
 * API Routes
 *--------------------------------------------------------------------------
 *
 * Here is where you can register API routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * is assigned the "api" middleware group. Enjoy building your API!
 *
*/

// Admins
Route::name('admin.')->prefix('/admin')->group(function () {

	Route::post('/login', [AdminAuthController::class, 'login'])->middleware('guest');
	Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin-api');
});

// Customers
Route::name('customer.')->prefix('/customer')->group(function () {

	Route::post('/login', [CustomerAuthController::class, 'login']);
	Route::post('/register-login', [CustomerAuthController::class, 'registerLogin']);
	Route::post('/send-token', [CustomerAuthController::class, 'sendToken']);
	Route::post('/verify', [CustomerAuthController::class, 'verify']);
	Route::post('/register', [CustomerAuthController::class, 'register']);
	Route::post('/logout', [CustomerAuthController::class, 'logout'])->middleware('auth:customer-api');
});
