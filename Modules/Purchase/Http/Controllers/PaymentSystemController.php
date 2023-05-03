<?php

namespace Modules\Purchase\Http\Controllers;

use Modules\Purchase\Http\Requests\HandlePaymentRequest;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Facades\Payment;

class PaymentSystemController
{
    public function handle(PaymentSystem $paymentSystem, HandlePaymentRequest $request)
    {
        return Payment::driver($paymentSystem)->handle();
    }
}
