<?php

namespace Modules\Purchase\Payment\Contracts;

interface CheckoutLinkGeneratorContract
{
    public function generate(int $purchaseId, float $amount);
}
