<?php

use Illuminate\Support\Facades\Route;
use Modules\Slider\Http\Controllers\Api\Admin\SliderController;

Route::prefix('admin/sliders')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [SliderController::class, 'index'])->middleware('can:view sliders');
	Route::post('/', [SliderController::class, 'store'])->middleware('can:create sliders');
	Route::patch('/{slider}', [SliderController::class, 'update'])->middleware('can:edit sliders');
	Route::delete('/{slider}', [SliderController::class, 'destory'])->middleware('can:delete sliders');
});
