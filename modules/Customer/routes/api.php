<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Api\Admin\CustomerController as AdminCustomerController;
use Modules\Customer\Http\Controllers\Api\Customer\CustomerController as CustomerCustomerController;
use Modules\Customer\Http\Controllers\Api\Customer\ProfileController as CustomerProfileController;

// Customer (Admin)
Route::name('customers.')->prefix('customers')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [AdminCustomerController::class, 'index'])->middleware('can:view customers');
	Route::get('/{customer}', [AdminCustomerController::class, 'show'])->middleware('can:view customers');
	Route::delete('/{customer}', [AdminCustomerController::class, 'destroy'])->middleware('can:delete customers');
});

// Customer (Customer)
Route::prefix('customer')->middleware('auth:customer-api')->group(function () {
	Route::get('/profile', [CustomerProfileController::class, 'showProfile']);
	Route::patch('/profile', [CustomerProfileController::class, 'updateProfile']);
	Route::put('/change-password', [CustomerProfileController::class, 'changePassword']);
});
