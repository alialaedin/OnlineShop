<?php

use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\Api\Admin\CustomerController as AdminCustomerController;

// Customer (Admin)
Route::name('customers.')->prefix('customers')->middleware('auth:admin-api')->group(function () {
	Route::get('/', [AdminCustomerController::class, 'index'])->middleware('can:view customers');
	Route::get('/{customer}', [AdminCustomerController::class, 'show'])->middleware('can:view customers');
	Route::delete('/{customer}', [AdminCustomerController::class, 'destroy'])->middleware('can:delete customers');
});
