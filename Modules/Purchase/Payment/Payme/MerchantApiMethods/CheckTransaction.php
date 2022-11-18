<?php

namespace Modules\Purchase\Payment\Payme\MerchantApiMethods;

use Modules\Purchase\Payment\Payme\Response;

trait CheckTransaction
{
    private function checkTransaction()
    {
        $transaction = $this->findTransactionByParams($this->request->params);

        if (is_null($transaction)) {
            $this->response->error(Response::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }

        $detail = $transaction->detail;

        $this->response->success([
            'create_time' => 1 * $detail['create_time'],
            'perform_time' => 1 * $detail['perform_time'],
            'cancel_time' => 1 * $detail['cancel_time'],
            'transaction' => (string)$transaction->id,
            'state' => 1 * $transaction->state,
            'reason' => ($transaction->comment && is_numeric($transaction->comment)) ? 1 * $transaction->comment : null,
        ]);
    }

}