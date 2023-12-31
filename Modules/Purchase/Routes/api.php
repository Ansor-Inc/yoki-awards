<?php

use Illuminate\Support\Facades\Route;
use Modules\Purchase\Http\Controllers\BookPurchaseController;
use Modules\Purchase\Http\Controllers\CheckoutController;
use Modules\Purchase\Http\Controllers\CouponController;

Route::middleware(['auth:sanctum', 'verified', 'verified.device'])->group(function () {
    Route::post('/books/{book}/make-purchase', [BookPurchaseController::class, 'makePurchase']);
    Route::get('/purchases', [BookPurchaseController::class, 'index']);
    Route::get('/purchases/completed', [BookPurchaseController::class, 'getCompletedPurchases']);
    Route::post('/purchases/{purchase}/checkout', [CheckoutController::class, 'checkout']);
    Route::post('/purchases/{purchase}/complete', [CheckoutController::class, 'complete']);
    Route::post('/coupons', [CouponController::class, 'activateCoupon'])->middleware('throttle:6,1');
});
