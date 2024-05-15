<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\App\Http\Controllers\MediaController;
use Modules\Product\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use Modules\Product\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use Modules\Product\Http\Controllers\Api\Front\CategoryController as FrontCategoryController;
use Modules\Product\Http\Controllers\Api\Front\ProductController as FrontProductController;

// Category
Route::prefix('admin/categories')->middleware('auth:admin-api')->group(function () {
	Route::post('/specifications/{category}', [AdminCategoryController::class, 'getSpecifications']);
	Route::get('/', [AdminCategoryController::class, 'index'])->middleware('can:view categories');
	Route::post('/', [AdminCategoryController::class, 'store'])->middleware('can:create categories');
	Route::patch('/{category}', [AdminCategoryController::class, 'update'])->middleware('can:edit categories');
	Route::delete('/{category}', [AdminCategoryController::class, 'destroy'])->middleware('can:delete categories');
});

Route::get('front/categories', [FrontCategoryController::class, 'index']);

// Product
Route::prefix('admin/products')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [AdminProductController::class, 'index'])->middleware('can:view products');
	Route::get('/{product}', [AdminProductController::class, 'show'])->middleware('can:view products');
	Route::post('/', [AdminProductController::class, 'store'])->middleware('can:create products');
	Route::patch('/{product}', [AdminProductController::class, 'update'])->middleware('can:edit products');
	Route::delete('/{product}', [AdminProductController::class, 'destroy'])->middleware('can:delete products');
	Route::delete('/images/{media}', [MediaController::class, 'destroy'])->middleware('can:delete products image');
});

Route::prefix('front/products')->group(function () {
	Route::get('/', [FrontProductController::class, 'index']);
	Route::get('/{product}', [FrontProductController::class, 'show']);
});
