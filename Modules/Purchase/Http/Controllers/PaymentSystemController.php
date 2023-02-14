<?php

namespace Modules\Purchase\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Purchase\Actions\PaymentSystemRequestHandler;
use Modules\Purchase\Enums\PaymentSystem;

class PaymentSystemController
{
    public function handle(PaymentSystem $paymentSystem, Request $request, PaymentSystemRequestHandler $handler)
    {
        try {
            return $handler->execute($paymentSystem, $request);
        } catch (Exception $exception) {
            return response(['message' => $exception->getMessage()]);
        }
    }
}
