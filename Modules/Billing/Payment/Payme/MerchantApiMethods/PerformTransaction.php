<?php

namespace Modules\Billing\Payment\Payme\MerchantApiMethods;

use App\Models\Transaction;
use Goodoneuz\PayUz\Http\Classes\DataFormat;
use Modules\Billing\Payment\Payme\Response;

trait PerformTransaction
{
    private function performTransaction()
    {
        $transaction = $this->findTransactionByParams($this->request->params);

        // if transaction not found, send error
        if (is_null($transaction)) {
            $this->response->error(Response::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }

        switch ($transaction->state) {
            case Transaction::STATE_CREATED:
                if ($transaction->isExpired()) {
                    $transaction->cancel(Transaction::REASON_CANCELLED_BY_TIMEOUT);
                    $this->response->error(
                        Response::ERROR_COULD_NOT_PERFORM,
                        'Transaction is expired.'
                    );
                } else {

                    $perform_time = DataFormat::timestamp(true);
                    $transaction->state = Transaction::STATE_COMPLETED;
                    $transaction->updated_time = $perform_time;

                    $detail = $transaction->detail;
                    $detail['perform_time'] = $perform_time;

                    $transaction->detail = $detail;
                    $transaction->update();

                    $this->response->success([
                        'transaction' => (string)$transaction->id,
                        'perform_time' => 1 * $perform_time,
                        'state' => 1 * $transaction->state,
                    ]);
                }
                break;

            case Transaction::STATE_COMPLETED: // handle complete transaction
                $detail = $transaction->detail;

                $this->response->success([
                    'transaction' => (string)$transaction->id,
                    'perform_time' => 1 * $detail['perform_time'],
                    'state' => 1 * $transaction->state,
                ]);

                break;

            default:
                // unknown situation
                $this->response->error(
                    Response::ERROR_COULD_NOT_PERFORM,
                    'Could not perform this operation.'
                );
                break;
        }
    }
}