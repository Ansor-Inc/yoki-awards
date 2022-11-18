<?php

namespace Modules\Purchase\Payment\Payme;

use function request;

class Merchant
{
    public $config;
    public $response;

    public function __construct($config, $response)
    {
        $this->config = $config;
        $this->response = $response;
    }

    public function authorize()
    {
        $hasAuthHeader = request()->hasHeader('Authorization');
        $hasBasicAuthHeader = preg_match('/^\s*Basic\s+(\S+)\s*$/i', request()->header('Authorization'), $matches);
        $hasValidCredentials = base64_decode($matches[1]) == $this->config['login'] . ":" . $this->config['password'];

        if (!$hasAuthHeader || !$hasBasicAuthHeader || !$hasValidCredentials) {
            $this->response->error(Response::ERROR_INSUFFICIENT_PRIVILEGE, 'Insufficient privilege to perform this method.');
        }

        return true;
    }
}
