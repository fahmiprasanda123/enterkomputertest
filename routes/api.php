<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OrderController;

Route::post('/order', [OrderController::class, 'store']);
Route::get('/bill/{tableNumber}', [OrderController::class, 'getBill']);

