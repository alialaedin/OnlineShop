<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Admin\OrderController as AdminOrderController;
use Modules\Order\Http\Controllers\Front\OrderController as FrontOrderController;
use Modules\Order\Http\Controllers\Customer\OrderController as CustomerOrderController;


Route::prefix('admin/orders')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [AdminOrderController::class, 'index']);
	Route::get('/{order}', [AdminOrderController::class, 'show']);
	Route::put('/{order}', [AdminOrderController::class, 'changeStatus']);
});

Route::middleware('auth:customer-api')->group(function () {

	Route::prefix('customer/orders')->group(function () {
		Route::get('/', [CustomerOrderController::class, 'index']);
		Route::get('/{order}', [CustomerOrderController::class, 'show']);
	});

	Route::prefix('front/orders')->group(function () {
		Route::post('/purchase', [FrontOrderController::class, 'purchase']);
		Route::post('/verify', [FrontOrderController::class, 'verify']);
	});
});
