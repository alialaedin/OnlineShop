<?php

use Illuminate\Support\Facades\Route;
use Modules\Specification\Http\Controllers\Api\Admin\SpecificationController;

Route::name('specifications.')->prefix('specifications')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [SpecificationController::class, 'index'])->middleware('can:view specifications')->name('index');
	Route::post('/', [SpecificationController::class, 'store'])->middleware('can:create specifications')->name('store');
	Route::patch('/{specification}', [SpecificationController::class, 'update'])->middleware('can:edit specifications')->name('update');
	Route::delete('/{specification}', [SpecificationController::class, 'destory'])->middleware('can:delete specifications')->name('destory');
});
