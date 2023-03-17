<?php

namespace Modules\Purchase\Payment\Contracts;

use Modules\Purchase\Payment\DTO\CheckoutData;

interface PaymentDriverContract
{
    public function run();

    public function generateCheckoutLink(int $purchaseId, float $amount): string;
}
