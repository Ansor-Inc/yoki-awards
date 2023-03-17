<?php

namespace Modules\Purchase\Http\Controllers;

use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Facades\Payment;

class PaymentSystemController
{
    public function handle(PaymentSystem $paymentSystem)
    {
        return Payment::driver($paymentSystem)->handle();
    }
}
