<?php

use Illuminate\Support\Facades\Route;
use Modules\Area\Http\Controllers\Api\ProvinceController;
use Modules\Area\Http\Controllers\Api\CityController;

// City
Route::name('cities.')->prefix('cities')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [CityController::class, 'index'])->middleware('can:view cities')->name('index');
	Route::post('/', [CityController::class, 'store'])->middleware('can:create cities')->name('store');
	Route::patch('/{city}', [CityController::class, 'update'])->middleware('can:edit cities')->name('update');
	Route::delete('/{city}', [CityController::class, 'destory'])->middleware('can:delete cities')->name('destory');
});

// Province
Route::get('/provinces', [ProvinceController::class, 'index'])->middleware(['can:view provinces', 'auth:admin-api'])->name('provinces.index');