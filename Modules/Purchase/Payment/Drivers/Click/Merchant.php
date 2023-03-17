<?php

namespace Modules\Purchase\Payment\Drivers\Click;

use Modules\Purchase\Payment\Drivers\Click\DTO\ClickConfig;
use Modules\Purchase\Payment\Drivers\Click\Response\Response as ClickResponse;

class Merchant
{
    public function __construct(private readonly ClickConfig $config)
    {
    }

    public function authorizeCompleteRequest(array $params): void
    {
        if (!$this->checkCompleteRequest($params) || !$this->checkServiceId($params['service_id'])) {
            ClickResponse::error(ClickResponse::ERROR_SIGN_CHECK);
        }
    }

    public function authorizePrepareRequest(array $params): void
    {
        if (!$this->checkPrepareRequest($params) || !$this->checkServiceId($params['service_id'])) {
            ClickResponse::error(ClickResponse::ERROR_SIGN_CHECK);
        }
    }

    private function checkServiceId(int $serviceId): bool
    {
        return $this->config->serviceId === $serviceId;
    }

    private function checkPrepareRequest(array $params): bool
    {
        return $params['sign_string'] === $this->makeSignStringForPrepareRequest($params);
    }

    private function checkCompleteRequest(array $params): bool
    {
        return $params['sign_string'] === $this->makeSignStringForCompleteRequest($params);
    }

    private function makeSignStringForPrepareRequest(array $params): string
    {
        return md5(
            $params['click_trans_id'] .
            $params['service_id'] .
            $this->config->secretKey .
            $params['merchant_trans_id'] .
            $params['amount'] .
            $params['action'] .
            $params['sign_time']
        );
    }

    private function makeSignStringForCompleteRequest(array $params): string
    {
        return md5(
            $params['click_trans_id'] .
            $params['service_id'] .
            $this->config->secretKey .
            $params['merchant_trans_id'] .
            $params['merchant_prepare_id'] .
            $params['amount'] .
            $params['action'] .
            $params['sign_time']
        );
    }
}
