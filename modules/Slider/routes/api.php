<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\Api\Admin\SliderController;

Route::name('sliders.')->prefix('sliders')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [SliderController::class, 'index'])->middleware('can:view sliders')->name('index');
	Route::post('/', [SliderController::class, 'store'])->middleware('can:create sliders')->name('store');
	Route::patch('/{slider}', [SliderController::class, 'update'])->middleware('can:edit sliders')->name('update');
	Route::delete('/{slider}', [SliderController::class, 'destory'])->middleware('can:delete sliders')->name('destory');
});
