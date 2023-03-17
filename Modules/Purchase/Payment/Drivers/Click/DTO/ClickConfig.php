<?php

namespace Modules\Purchase\Payment\Drivers\Click\DTO;

use Spatie\LaravelData\Data;

class ClickConfig extends Data
{
    public function __construct(
        public int    $serviceId,
        public int    $merchantId,
        public string $secretKey,
        public int    $merchantUserId
    )
    {
    }
}
