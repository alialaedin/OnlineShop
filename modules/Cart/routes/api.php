<?php

use Illuminate\Support\Facades\Route;
use Modules\Cart\Http\Controllers\Customer\CartController as CustomerCartController;

Route::prefix('customer/carts')->middleware('auth:customer-api')->group(function () {
	Route::get('/', [CustomerCartController::class, 'index']);
	Route::post('/', [CustomerCartController::class, 'store']);
	Route::put('/{cart}', [CustomerCartController::class, 'update']);
	Route::delete('/{cart}', [CustomerCartController::class, 'destory']);
});
