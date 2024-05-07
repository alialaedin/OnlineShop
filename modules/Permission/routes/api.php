<?php

use Illuminate\Support\Facades\Route;
use Modules\Permission\App\Http\Controllers\Api\Admin\RoleController;


Route::prefix('admin/roles')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [RoleController::class, 'index'])->middleware('can:view roles');
	Route::post('/', [RoleController::class, 'store'])->middleware('can:create roles');
	Route::patch('/{role}', [RoleController::class, 'update'])->middleware('can:edit roles');
	Route::delete('/{role}', [RoleController::class, 'destory'])->middleware('can:delete roles');
});
