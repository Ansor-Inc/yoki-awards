<?php

namespace Modules\Purchase\Actions\Payme;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Enums\PaymentSystem;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Payme\Response as PaymeResponse;
use Modules\Purchase\Repositories\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Repositories\Interfaces\TransactionRepositoryInterface;

class PerformTransactionAction
{
    use ValidatesParams;

    private mixed $config;

    public function __construct(private PaymeResponse                  $response,
                                private TransactionRepositoryInterface $transactionRepository,
                                private PurchaseRepositoryInterface    $purchaseRepository)
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

            if ($transaction->isExpired()) {
                $this->cancelTransaction($transaction);
            }

            $purchase = $this->purchaseRepository->getPurchaseById($transaction->purchase_id);

            $this->closeTransaction($transaction, $purchase);
        }

        if ($transaction->state === Transaction::STATE_COMPLETED) {
            $this->response->success([
                'state' => $transaction->state,
                'perform_time' => 1 * $transaction->detail['perform_time'],
                'transaction' => (string)$transaction->id,
            ]);
        }

        $this->response->error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'Could not perform this operation.');
    }


    private function cancelTransaction($transaction)
    {
        $transaction->cancel(Transaction::REASON_CANCELLED_BY_TIMEOUT);

        $this->response->error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'Transaction is expired.');
    }

    private function closeTransaction($transaction, $purchase)
    {
        $performTime = DataFormat::timestamp(true);

        DB::transaction(function () use ($transaction, $purchase, $performTime) {
            $purchase->update(['state' => PurchaseStatus::COMPLETED->value]);

            $detail = $transaction->detail;
            $detail['perform_time'] = $performTime;

            $transaction->update([
                'state' => Transaction::STATE_COMPLETED,
                'updated_time' => $performTime,
                'detail' => $detail
            ]);
        });

        $this->response->success([
            'transaction' => (string)$transaction->id,
            'perform_time' => $performTime,
            'state' => $transaction->state,
        ]);
    }
}
