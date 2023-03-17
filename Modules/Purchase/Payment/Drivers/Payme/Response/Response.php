<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Response;

use Modules\Purchase\Payment\Exceptions\PaymentException;
use Modules\Purchase\Payment\PaymentResponse;

class Response extends PaymentResponse
{
    const ERROR_INTERNAL_SYSTEM = -32400;
    const ERROR_INSUFFICIENT_PRIVILEGE = -32504;
    const ERROR_INVALID_JSON_RPC_OBJECT = -32600;
    const ERROR_METHOD_NOT_FOUND = -32601;
    const ERROR_INVALID_AMOUNT = -31001;
    const ERROR_TRANSACTION_NOT_FOUND = -31003;
    const ERROR_INVALID_ACCOUNT = -31050;
    const ERROR_INVALID_TRANSACTION = -31051;
    const ERROR_COULD_NOT_CANCEL = -31007;
    const ERROR_COULD_NOT_PERFORM = -31008;

    public function __construct()
    {
        $this->params['jsonrpc'] = '2.0';
    }


    /**
     * @throws PaymentException
     */
    public static function error(int $code, string|array $message = null, string|array $data = null)
    {
        $response = new static();

        // prepare error data
        $error = ['code' => $code];

        if ($message) $error['message'] = $message;
        if ($data) $error['data'] = $data;

        $response->params['result'] = null;
        $response->params['error'] = $error;

        throw new PaymentException($response);
    }

    /**
     * @throws PaymentException
     */
    public static function success(array $result)
    {
        $response = new static();

        $response->params['result'] = $result;
        $response->params['error'] = null;

        throw new PaymentException($response);
    }

    public static function message($ru, $uz = '', $en = ''): array
    {
        return ['ru' => $ru, 'uz' => $uz, 'en' => $en];
    }

    public function ok(): bool
    {
        return !isset($this->params['error']);
    }
}
