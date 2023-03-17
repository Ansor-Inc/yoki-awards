<?php

namespace Modules\Purchase\Payment\Drivers\Payme\DTO;

use Spatie\LaravelData\Data;

class PaymeConfig extends Data
{
    public function __construct(
        public string $merchantId,
        public string $key,
        public string $login,
        public string $password
    )
    {
    }
}
