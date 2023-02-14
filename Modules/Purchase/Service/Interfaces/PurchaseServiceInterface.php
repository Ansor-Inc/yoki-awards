<?php

namespace Modules\Purchase\Service\Interfaces;

use Modules\Purchase\Entities\Purchase;

interface PurchaseServiceInterface
{
    public function getPurchaseItemTitle($purchase): string;

    public function getPurchaseAmount($purchase): float;

    public function getPurchaseItemCode($purchase): string;

    public function getPurchaseItemsCount($purchase): int;

    public function getPurchaseItemPackageCode($purchase): string;

    public function getPurchaseVatPercent($purchase): int;

    public function checkPurchaseIsValidForPayment($purchase): bool;

    public function checkIsProperAmount(float $amount, $purchase): bool;

    public function checkPurchaseHasActiveTransactions($purchase): bool;

    public function checkPurchaseHasCompletedTransactions($purchase): bool;

    public function completePurchase($purchase): void;

    public function cancelPurchase($purchase): void;

}
