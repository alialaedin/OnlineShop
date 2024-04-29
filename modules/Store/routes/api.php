<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\Api\Admin\StoreController;

// Category
Route::name('stores.')->prefix('stores')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [StoreController::class, 'index'])->middleware('can:view stores')->name('index');
	Route::get('/{store}', [StoreController::class, 'show'])->middleware('can:view stores')->name('show');
	Route::post('/', [StoreController::class, 'store'])->middleware('can:create stores')->name('store');
});
