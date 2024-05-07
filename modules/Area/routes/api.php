<?php

use Illuminate\Support\Facades\Route;
use Modules\Area\Http\Controllers\Api\ProvinceController;
use Modules\Area\Http\Controllers\Api\CityController;

// City
Route::prefix('admin/cities')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [CityController::class, 'index'])->middleware('can:view cities');
	Route::post('/', [CityController::class, 'store'])->middleware('can:create cities');
	Route::patch('/{city}', [CityController::class, 'update'])->middleware('can:edit cities');
	Route::delete('/{city}', [CityController::class, 'destory'])->middleware('can:delete cities');
});

// Province
Route::get('admin/provinces', [ProvinceController::class, 'index'])->middleware(['can:view provinces', 'auth:admin-api']);