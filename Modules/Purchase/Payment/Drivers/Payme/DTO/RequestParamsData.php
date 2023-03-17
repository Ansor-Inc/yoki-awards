<?php

namespace Modules\Purchase\Payment\Drivers\Payme\DTO;

use Spatie\LaravelData\Data;

class RequestParamsData extends Data
{
    public function __construct(
        public int       $amount,
        public array $account,
    )
    {

    }
}
