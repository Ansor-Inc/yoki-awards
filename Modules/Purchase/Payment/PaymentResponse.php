<?php

namespace Modules\Purchase\Payment;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

abstract class PaymentResponse
{
    protected array $params = [];

    public function send(): Response|Application|ResponseFactory
    {
        return response($this->params);
    }
}
