<?php

namespace Modules\Purchase\Service;

use Modules\Purchase\Entities\Purchase;

class PurchaseService
{
    public static function checkPurchaseIsValidForPayment(Purchase $purchase): bool
    {
        return $purchase->book()->exists() and
            $purchase->user()->exists() and
            !$purchase->book->is_free and
            $purchase->pending();
    }

    public static function checkIsProperAmount(float $amount, Purchase $purchase): bool
    {
        if ($purchase->user->getBalance() < $purchase->getAttribute('from_balance')) return false;

        return $amount === (float)$purchase->book->price - (float)$purchase->getAttribute('from_balance');
    }

    public static function checkPurchaseHasActiveTransactions(Purchase $purchase): bool
    {
        return $purchase->activeTransactions()->exists();
    }

    public static function checkPurchaseHasCompletedTransactions(Purchase $purchase): bool
    {
        return $purchase->completedTransactions()->exists();
    }

    public static function cancelPurchase(Purchase $purchase): void
    {
        $purchase->cancel();
    }

    public static function completePurchase(Purchase $purchase): void
    {
        $purchase->complete();
        $purchase->user->withdraw($purchase->getAttribute('from_balance'));
    }
}
