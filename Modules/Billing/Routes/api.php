<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Billing\Enums\PaymentSystem;
use Modules\Billing\Payment\PaymentService;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/billing/{paymentSystem}/handle', function (PaymentSystem $paymentSystem) {
    return (new PaymentService())->driver($paymentSystem)->handle();
});