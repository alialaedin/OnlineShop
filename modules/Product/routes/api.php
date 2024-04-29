<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\App\Http\Controllers\MediaController;
use Modules\Product\Http\Controllers\Api\Admin\CategoryController;
use Modules\Product\Http\Controllers\Api\Admin\ProductController;

// Category
Route::name('categories.')->prefix('categories')->middleware('auth:admin-api')->group(function () {
	Route::post('/specifications/{category}', [CategoryController::class, 'getSpecifications']);
	Route::get('/', [CategoryController::class, 'index'])->middleware('can:view categories')->name('index');
	Route::post('/', [CategoryController::class, 'store'])->middleware('can:create categories')->name('store');
	Route::patch('/{category}', [CategoryController::class, 'update'])->middleware('can:edit categories')->name('update');
	Route::delete('/{category}', [CategoryController::class, 'destory'])->middleware('can:delete categories')->name('destory');
});

// Product
Route::name('products.')->prefix('products')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [ProductController::class, 'index'])->middleware('can:view products')->name('index');
	Route::get('/{product}', [ProductController::class, 'show'])->middleware('can:view products')->name('show');
	Route::post('/', [ProductController::class, 'store'])->middleware('can:create products')->name('store');
	Route::patch('/{product}', [ProductController::class, 'update'])->middleware('can:edit products')->name('update');
	Route::delete('/{product}', [ProductController::class, 'destory'])->middleware('can:delete products')->name('destory');
	Route::delete('/images/{media}', [MediaController::class, 'destroy'])->middleware('can:delete products image')->name('destroyImage');
});