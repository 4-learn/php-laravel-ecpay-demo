<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\EcpayController;

Route::get('/ecpay/test', [EcpayController::class, 'checkout']);
Route::post('/ecpay/checkout', [EcpayController::class, 'checkout']);