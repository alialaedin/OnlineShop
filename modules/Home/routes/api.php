<?php

use Illuminate\Support\Facades\Route;
use Modules\Home\Http\Controllers\Front\HomeController;

Route::get('/home', [HomeController::class, 'home']);