<?php

namespace Modules\Purchase\Payment\DTO;

use Spatie\LaravelData\Data;

class CheckoutData extends Data
{
    public function __construct(
        public int   $purchaseId,
        public float $amount
    )
    {

    }
}
