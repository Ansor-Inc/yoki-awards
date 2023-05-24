<?php

namespace Modules\Purchase\Actions;

use Exception;
use Illuminate\Http\Request;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Exceptions\InsufficientBalanceException;
use Modules\Purchase\Exceptions\InvalidCheckoutException;
use Modules\Purchase\Exceptions\InvalidPurchaseException;
use Modules\Purchase\Payment\Drivers\Payme\PaymeCheckoutLinkGenerator;
use Modules\Purchase\Payment\DTO\CheckoutData;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Payment;

class CheckoutAction
{
    public function execute(Purchase $purchase, Request $request)
    {
        $fromBalance = $request->input('from_balance') ?? 0;

        if (!$purchase->isPending()) {
            throw new InvalidPurchaseException('Invalid purchase! Completed or canceled purchase!');
        }

        if ($fromBalance > $purchase->amount) {
            throw new InvalidPurchaseException("From balance field cannot be greater than purchase amount!");
        }

        if (!$purchase->userHasEnoughBalance($fromBalance)) {
            throw new InsufficientBalanceException("You do not have enough balance!");
        }

        if ($fromBalance === $purchase->amount) {
            throw new InvalidCheckoutException("You cannot checkout for 0 amount!");
        }

        $purchase->update(['from_balance' => $fromBalance]);

        return $this->generateCheckoutLink(
            $request->input('payment_system'),
            $purchase
        );
    }

    protected function generateCheckoutLink(string $paymentSystem, $purchase)
    {
        return app(Payment::class)->driver(PaymentSystem::from($paymentSystem))->generateCheckoutLink($purchase->id, $purchase->getPaidAmount());
    }
}
