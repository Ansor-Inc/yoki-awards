<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Enums\PurchaseStatus;

class CheckoutController extends Controller
{
    public function checkout(Purchase $purchase)
    {
        if ($purchase->state === PurchaseStatus::PENDING_PAYMENT->value)
            return response(["checkout_link" => $this->generateCheckoutLinkForPayme($purchase)]);

        return response(['message' => 'Invalid purchase! Completed or canceled purchcase!'],500);
    }

    public function generateCheckoutLinkForPayme($purchase)
    {
        $params = collect([
            'm' => config('billing.payme')['merchant_id'],
            'ac.purchase_id' => $purchase->id,
            'a' => $purchase->amount * 100,
        ])->implode(fn($value, $key) => "{$key}={$value}", ';');

        $encodedParams = base64_encode($params);

        return "https://checkout.paycom.uz/{$encodedParams}";
    }

}
