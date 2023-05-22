<?php

namespace Modules\Purchase\Payment\Drivers\Payze;

use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Payment\Contracts\PaymentDriverContract;
use PayzeIO\LaravelPayze\Enums\Currency;
use PayzeIO\LaravelPayze\Enums\Language;

class Payze implements PaymentDriverContract
{
    public function run()
    {
        // TODO: Implement run() method.
    }

    /**
     * @throws \Throwable
     */
    public function generateCheckoutLink(int $purchaseId, float $amount): string
    {
        $purchase = Purchase::query()->findOrFail($purchaseId);

        throw_unless($purchase->pending(), new \InvalidArgumentException('Not valid purchase'));

        $product = new ProductInfo(
            image: $purchase->book->image,
            name: $purchase->book->title
        );

        $response = (new JustPayWithProductInfo())
            ->currency(Currency::UZS)
            ->amount($amount)
            ->language(Language::UZB)
            ->product($product)
            ->for($purchase);

        return $response['response']['transactionUrl'];
    }
}
