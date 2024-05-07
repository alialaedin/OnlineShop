<?php

use Illuminate\Support\Facades\Route;
use Modules\Specification\Http\Controllers\Api\Admin\SpecificationController;

Route::prefix('admin/specifications')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [SpecificationController::class, 'index'])->middleware('can:view specifications');
	Route::post('/', [SpecificationController::class, 'store'])->middleware('can:create specifications');
	Route::patch('/{specification}', [SpecificationController::class, 'update'])->middleware('can:edit specifications');
	Route::delete('/{specification}', [SpecificationController::class, 'destory'])->middleware('can:delete specifications');
});
