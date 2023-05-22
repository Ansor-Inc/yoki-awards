<?php

namespace Modules\Purchase\Payment\Drivers\Payze;

class ProductInfo
{
    public function __construct(
        public string  $image,
        public string  $name,
        public ?string $description = null
    )
    {
    }
}
