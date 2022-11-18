<?php

namespace Modules\Purchase\Payment\Payme\MerchantApiMethods;

use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Payment\Payme\Response;

trait CheckPerformTransaction
{
    private function checkPerformTransaction()
    {
        $this->validateParams($this->request->params);
        $purchase = $this->getPurchaseByKey();

        //Checking if purchase exists
        if (is_null($purchase)) {
            $this->response->error(Response::ERROR_INVALID_ACCOUNT, 'Object not fount.');
        }

        //Checking purchase state
        if ($purchase->state != PurchaseStatus::PENDING_PAYMENT->value) {
            $this->response->error(Response::ERROR_INVALID_ACCOUNT, 'Invalid purchase state. Completed or canceled purchase.');
        }

        //Checking amount of purchase
        $isProperAmount = (float)$this->request->params['amount'] === (float)$purchase->amount * 100;

        if (!$isProperAmount) {
            $this->response->error(Response::ERROR_INVALID_AMOUNT, 'Invalid amount for this object.');
        }

        //Checking whether purchase has active or completed transactions
        $hasActiveTransactions = $purchase->activeTransactions()->exists();
        $hasCompletedTransaction = $purchase->completedTransactions()->exists();

        if ($hasActiveTransactions || $hasCompletedTransaction) {
            $this->response->error(Response::ERROR_INVALID_TRANSACTION, 'There is other active/completed transaction for this object.');
        }

        $this->response->success(['allow' => true]);
    }
}