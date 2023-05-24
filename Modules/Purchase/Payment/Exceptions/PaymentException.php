<?php

namespace Modules\Purchase\Payment\Exceptions;

use Exception;
use Modules\Purchase\Payment\PaymentResponse;

class PaymentException extends Exception
{
    public function __construct(public PaymentResponse $response)
    {
    }

    public function response()
    {
        return $this->response->send();
    }
}
