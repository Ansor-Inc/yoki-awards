<?php

namespace Modules\Purchase\Payment\Payme;

use Modules\Purchase\Payment\Contracts\CheckoutLinkGeneratorContract;

class PaymeCheckoutLinkGenerator implements CheckoutLinkGeneratorContract
{
    public function generate(int $purchaseId, float $amount): string
    {
        $params = collect([
            'm' => config('billing.payme')['merchant_id'],
            'ac.purchase_id' => $purchaseId,
            'a' => $amount * 100,
        ])->implode(fn($value, $key) => "{$key}={$value}", ';');

        $encodedParams = base64_encode($params);

        return "https://checkout.paycom.uz/{$encodedParams}";
    }
}
