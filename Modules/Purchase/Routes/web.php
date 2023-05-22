<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\PaymentSystemController;

Route::any('/billing/{paymentSystem}/handle', [PaymentSystemController::class, 'handle'])->name('payment-system.handle');
