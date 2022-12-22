<?php

namespace Modules\Purchase\Actions\Payme;

use Illuminate\Http\Request;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Enums\PaymentSystem;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Payme\Response as PaymeResponse;
use Modules\Purchase\Repositories\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Repositories\Interfaces\TransactionRepositoryInterface;

class CancelTransactionAction
{
    use ValidatesParams;

    private mixed $config;

    public function __construct(private PaymeResponse                  $response,
                                private TransactionRepositoryInterface $transactionRepository,
                                private PurchaseRepositoryInterface    $purchaseRepository,
                                private Request                        $request)
    {
        $this->config = config('billing.payme');
    }

    public function execute(Request $request)
    {
        $this->validateTransactionId($request->params);

        $transaction = $this->transactionRepository->getTransactionById($request->params['id'], PaymentSystem::PAYME);

        // if transaction not found, send error
        if (is_null($transaction)) {
            $this->response->error(PaymeResponse::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }

        if ($transaction->state === Transaction::STATE_CREATED) {
            $this->cancelTransaction($transaction);
        }

        if ($transaction->state === Transaction::STATE_COMPLETED) {
            $this->response->error(
                PaymeResponse::ERROR_COULD_NOT_CANCEL,
                'Could not cancel transaction. Order is delivered/Service is completed.'
            );
        }
    }

    private function cancelTransaction($transaction)
    {
        // cancel transaction with given reason
        $transaction->cancel(1 * $this->request->params['reason']);

        $cancelTime = DataFormat::timestamp(true);

        $transaction->update([
            'updated_time' => $cancelTime,
            'detail.cancel_time' => $cancelTime
        ]);

        $this->response->success([
            'state' => 1 * $transaction->state,
            'cancel_time' => $cancelTime,
            'transaction' => (string)$transaction->id,
        ]);
    }
}
