<?php

namespace Modules\Purchase\Payment\Drivers\Click;

use Modules\Purchase\Payment\Drivers\Click\DTO\ClickConfig;

class ClickCheckoutLinkGenerator
{
    const BASE_URL = 'https://my.click.uz/services/pay';

    public function __construct(private readonly ClickConfig $config)
    {
    }

    public function generate(int $purchaseId, float $amount): string
    {
        return self::BASE_URL . '?' . http_build_query($this->preparePayload($purchaseId, $amount));
    }

    private function preparePayload(int $purchaseId, float $amount): array
    {
        return [
            'merchant_id' => $this->config->merchantId,
            'merchant_user_id' => $this->config->merchantUserId,
            'service_id' => $this->config->serviceId,
            'transaction_param' => $purchaseId,
            'amount' => $amount,
            //'return_url' => '',
            //'card_type'=> ''
        ];
    }
}
