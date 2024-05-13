<?php

use Illuminate\Support\Facades\Route;
use Modules\Order\Http\Controllers\Front\OrderController;

Route::match(
  ['get', 'post'],
  'payments/verify/{driver}',
  [OrderController::class, 'verify']
)->name('payments.verify');
