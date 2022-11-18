<?php

namespace Modules\Purchase\Payment\Payme\MerchantApiMethods;

use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Enums\PaymentSystem;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Payme\Response;

trait CreateTransaction
{
    private function createTransaction()
    {
        $this->validateParams($this->request->params);
        $transaction = $this->findTransactionByParams($this->request->params);

        //Check whether transaction exists
        if (isset($transaction)) {
            $this->checkTransactionState($transaction);
        } else {

            $this->checkPerformTransaction();

            $purchase = $this->getPurchaseByKey();

            $isTimeOut = DataFormat::timestamp2milliseconds(1 * $this->request->params['time']) - DataFormat::timestamp(true) >= Transaction::TIMEOUT;

            if ($isTimeOut) {
                $this->response->error(
                    Response::ERROR_INVALID_ACCOUNT,
                    Response::message(
                        'С даты создания транзакции прошло ' . Transaction::TIMEOUT . 'мс',
                        'Tranzaksiya yaratilgan vaqtdan ' . Transaction::TIMEOUT . 'ms o`tgan',
                        'Since create time of the transaction passed ' . Transaction::TIMEOUT . 'ms'
                    ),
                    'time'
                );
            }

            $createTime = DataFormat::timestamp(true);

            $detail = array(
                'create_time' => $createTime,
                'perform_time' => null,
                'cancel_time' => null,
                'system_time_datetime' => DataFormat::timestamp2datetime($this->request->params['time'])
            );

            $transaction = Transaction::create([
                'payment_system' => PaymentSystem::PAYME->value,
                'system_transaction_id' => $this->request->params['id'],
                'amount' => 1 * ($this->request->amount) / 100,
                'state' => Transaction::STATE_CREATED,
                'updated_time' => 1 * $createTime,
                'comment' => $this->request->params['error_note'] ?? '',
                'detail' => $detail,
                'purchase_id' => $purchase->id
            ]);
        }

        $this->response->success([
            'create_time' => 1 * $transaction->updated_time,
            'transaction' => (string)$transaction->id,
            'state' => 1 * $transaction->state,
            'receivers' => $transaction->receivers,
        ]);
    }

    private function checkTransactionState($transaction)
    {
        if ($transaction->state != Transaction::STATE_CREATED) {
            $this->response->error(Response::ERROR_COULD_NOT_PERFORM, 'Transaction found, but is not active.');
        } elseif ($transaction->isExpired()) {
            //Check whether transaction is expired
            $transaction->cancel(Transaction::REASON_CANCELLED_BY_TIMEOUT);
            $this->response->error(Response::ERROR_COULD_NOT_PERFORM, 'Transaction is expired.');
        } else {
            $this->response->success([
                'create_time' => 1 * $transaction->updated_time,
                'transaction' => (string)$transaction->id,
                'state' => 1 * $transaction->state,
                'receivers' => $transaction->receivers,
            ]);
        }
    }
}