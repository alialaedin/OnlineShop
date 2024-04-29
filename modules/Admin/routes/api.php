<?php

use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\Api\Admin\AdminController;

Route::name('admins.')->prefix('admins')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [AdminController::class, 'index'])->middleware('can:view admins')->name('index');
	Route::post('/', [AdminController::class, 'store'])->middleware('can:create admins')->name('store');
	Route::patch('/{admin}', [AdminController::class, 'update'])->middleware('can:edit admins')->name('update');
	Route::delete('/{admin}', [AdminController::class, 'destory'])->middleware('can:delete admins')->name('destory');
});
