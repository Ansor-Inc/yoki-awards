<?php

namespace Modules\Purchase\Payment\Drivers\Payme;

use Modules\Purchase\Payment\Drivers\Payme\DTO\PaymeConfig;
use Modules\Purchase\Payment\DTO\CheckoutData;

class PaymeCheckoutLinkGenerator
{
    const BASE_URL = 'https://checkout.paycom.uz';

    public function __construct(private readonly PaymeConfig $config)
    {
    }

    public function generate(CheckoutData $checkoutData): string
    {
        $payload = $this->preparePayload($checkoutData);

        return self::BASE_URL . "/{$this->encryptPayload($payload)}";
    }

    private function preparePayload(CheckoutData $checkoutData): array
    {
        return [
            'm' => $this->config->merchantId,
            'ac.purchase_id' => $checkoutData->purchaseId,
            'a' => $checkoutData->amount * 100
        ];
    }

    private function encryptPayload(array $payload): string
    {
        return base64_encode(
            collect($payload)->implode(fn($value, $key) => "{$key}={$value}", ';')
        );
    }
}
