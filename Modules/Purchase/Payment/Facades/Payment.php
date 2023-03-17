<?php

namespace Modules\Purchase\Payment\Facades;


use Illuminate\Support\Facades\Facade;
use Modules\Purchase\Payment\Enums\PaymentSystem;

/**
 * @method static \Modules\Purchase\Payment\Payment driver(PaymentSystem $paymentSystem)
 * @method  \Modules\Purchase\Payment\Payment run()
 */
class Payment extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payment';
    }
}
