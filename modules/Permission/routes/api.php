<?php

use Illuminate\Support\Facades\Route;
use Modules\Permission\App\Http\Controllers\Api\Admin\RoleController;


Route::name('roles.')->prefix('roles')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [RoleController::class, 'index'])->middleware('can:view roles')->name('index');
	Route::post('/', [RoleController::class, 'store'])->middleware('can:create roles')->name('store');
	Route::patch('/{role}', [RoleController::class, 'update'])->middleware('can:edit roles')->name('update');
	Route::delete('/{role}', [RoleController::class, 'destory'])->middleware('can:delete roles')->name('destory');
});
