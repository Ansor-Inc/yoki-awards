<?php

namespace Modules\Billing\Payment\Payme\MerchantApiMethods;

use App\Models\Transaction;
use Goodoneuz\PayUz\Http\Classes\DataFormat;
use Modules\Billing\Payment\Payme\Response;

trait CancelTransaction
{
    private function cancelTransaction()
    {
        $transaction = $this->findTransactionByParams($this->request->params);

        // if transaction not found, send error
        if (is_null($transaction)) {
            $this->response->error(Response::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }

        switch ($transaction->state) {
            // if already cancelled, just send it
            case Transaction::STATE_CANCELLED:
            case Transaction::STATE_CANCELLED_AFTER_COMPLETE:
                $detail = $transaction->detail;
                $this->response->success([
                    'transaction' => (string)$transaction->id,
                    'cancel_time' => 1 * $detail['cancel_time'],
                    'state' => 1 * $transaction->state,
                ]);
                break;

            // cancel active transaction
            case Transaction::STATE_CREATED:
                // cancel transaction with given reason
                $transaction->cancel(1 * $this->request->params['reason']);

                $cancel_time = DataFormat::timestamp(true);

                $detail = $transaction->detail;
                $detail['cancel_time'] = $cancel_time;

                $transaction->update([
                    'updated_time' => $cancel_time,
                    'detail' => $detail
                ]);


                $this->response->success([
                    'transaction' => (string)$transaction->id,
                    'cancel_time' => 1 * $cancel_time,
                    'state' => 1 * $transaction->state,
                ]);
                break;

            case Transaction::STATE_COMPLETED:
                $this->response->error(
                    Response::ERROR_COULD_NOT_CANCEL,
                    'Could not cancel transaction. Order is delivered/Service is completed.'
                );
                break;
        }
    }

}