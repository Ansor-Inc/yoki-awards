<?php

namespace Modules\Purchase\Service;

use Illuminate\Support\Facades\DB;
use Modules\Purchase\Service\Interfaces\PurchaseServiceInterface;

class PurchaseService implements PurchaseServiceInterface
{
    public function getPurchaseItemTitle($purchase): string
    {
        return $purchase->book->title;
    }

    public function getPurchaseAmount($purchase): float
    {
        return $purchase->getPaidAmount() * 100;
    }

    public function getPurchaseItemCode($purchase): string
    {
        return $purchase->book->code;
    }

    public function getPurchaseItemsCount($purchase): int
    {
        return 1;
    }

    public function getPurchaseItemPackageCode($purchase): string
    {
        return $purchase->book->package_code;
    }

    public function getPurchaseVatPercent($purchase): int
    {
        return (int)setting('vat_percent', 0);
    }

    public function checkPurchaseIsValidForPayment($purchase): bool
    {
        return $purchase->book()->exists() and
            $purchase->user()->exists() and
            !$purchase->book->is_free and
            !$purchase->completed();
    }

    public function checkIsProperAmount(float $amount, $purchase): bool
    {
        if ($purchase->user->getBalance() < $purchase->from_balance) return false;

        return $amount === (((float)$purchase->book->price - (float)$purchase->from_balance) * 100);
    }

    public function checkPurchaseHasActiveTransactions($purchase): bool
    {
        return $purchase->activeTransactions()->exists();
    }

    public function checkPurchaseHasCompletedTransactions($purchase): bool
    {
        return $purchase->completedTransactions()->exists();
    }

    public function cancelPurchase($purchase): void
    {
        $purchase->cancel();
    }

    public function completePurchase($purchase): void
    {
        DB::transaction(function () use ($purchase) {
            $purchase->complete();
            $purchase->user->withdraw($purchase->from_balance);
        });
    }
}
