<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\OrderController;

Route::post('/customer/purchase', [OrderController::class, 'purchase'])->middleware('auth:customer-api');
Route::any('/customer/verify', [OrderController::class, 'verify']);
