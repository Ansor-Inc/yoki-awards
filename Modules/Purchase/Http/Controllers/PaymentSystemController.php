<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Purchase\Actions\PaymentSystemRequestHandler;
use Modules\Purchase\Enums\PaymentSystem;

class PaymentSystemController
{
    public function handle(PaymentSystem $paymentSystem, Request $request, PaymentSystemRequestHandler $handler)
    {
        return $handler->execute($paymentSystem, $request);
    }
}
