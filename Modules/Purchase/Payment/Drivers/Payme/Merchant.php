<?php

namespace Modules\Purchase\Payment\Drivers\Payme;

use Illuminate\Http\Request;
use Modules\Purchase\Payment\Drivers\Payme\DTO\PaymeConfig;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\Exceptions\PaymentException;

class Merchant
{
    public function __construct(public PaymeConfig $config)
    {
    }

    /**
     * @throws PaymentException
     */
    public function authorize(Request $request): void
    {
        $hasAuthHeader = $request->hasHeader('Authorization');

        $hasBasicAuthHeader = preg_match('/^\s*Basic\s+(\S+)\s*$/i', $request->header('Authorization'), $matches);

        if ($hasAuthHeader)
            $hasValidCredentials = base64_decode($matches[1]) == $this->config->login . ":" . $this->config->password;

        if (!$hasAuthHeader || !$hasBasicAuthHeader || !$hasValidCredentials) {
            PaymeResponse::error(PaymeResponse::ERROR_INSUFFICIENT_PRIVILEGE, 'Insufficient privilege to perform this method.');
        }
    }
}
