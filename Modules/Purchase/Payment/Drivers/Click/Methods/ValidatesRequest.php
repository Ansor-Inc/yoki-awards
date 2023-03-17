<?php

namespace Modules\Purchase\Payment\Drivers\Click\Methods;

use Modules\Purchase\Payment\Drivers\Click\Response\Response as ClickResponse;
use Modules\Purchase\Service\PurchaseService;

trait ValidatesRequest
{
    public function validate(array $params)
    {
        $purchase = $this->purchaseRepository->getPurchaseById((int)$params['merchant_trans_id']);

        if (is_null($purchase)) {
            ClickResponse::error(ClickResponse::ERROR_ORDER_NOT_FOUND);
        }

        if ($purchase->completed() || PurchaseService::checkPurchaseHasCompletedTransactions($purchase)) {
            ClickResponse::error(ClickResponse::ERROR_ALREADY_PAID);
        }

        if (!PurchaseService::checkPurchaseIsValidForPayment($purchase)) {
            ClickResponse::error(ClickResponse::ERROR_ORDER_NOT_FOUND);
        }

        if (!PurchaseService::checkIsProperAmount($params['amount'], $purchase)) {
            ClickResponse::error(ClickResponse::ERROR_INVALID_AMOUNT);
        }

        if ($purchase->canceled()) {
            ClickResponse::error(ClickResponse::ERROR_TRANSACTION_CANCELLED);
        }

        return $purchase;
    }
}
