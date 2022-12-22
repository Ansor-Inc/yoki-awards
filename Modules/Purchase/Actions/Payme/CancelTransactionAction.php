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
            $this->cancelTransaction($transaction, $request);
            $this->successResponse($transaction);
        }

        if ($transaction->state === Transaction::STATE_COMPLETED) {
            $this->cancelTransactionAfterComplete($transaction, $request);
            $this->successResponse($transaction);
        }

        $this->response->success([
            'state' => $transaction->state,
            'cancel_time' => $transaction->detail['cancel_time'],
            'transaction' => (string)$transaction->id,
        ]);

    }

    private function cancelTransaction($transaction, $request)
    {
        // cancel transaction with given reason
        $transaction->cancel(1 * $request->params['reason']);

        $cancelTime = DataFormat::timestamp(true);

        $detail = $transaction->detail;
        $detail['cancel_time'] = $cancelTime;

        $transaction->update([
            'updated_time' => $cancelTime,
            'detail' => $detail
        ]);

        $transaction->refresh();
    }

    private function cancelTransactionAfterComplete($transaction, $request)
    {
        DB::transaction(function () use ($transaction, $request) {
            $purchase = $this->purchaseRepository->getPurchaseById($transaction->purchase_id);

            $purchase->update(['state' => PurchaseStatus::CANCELED->value]);

            $this->cancelTransaction($transaction, $request);
        });
    }

    private function successResponse($transaction)
    {
        $this->response->success([
            'state' => 1 * $transaction->state,
            'cancel_time' => $transaction->detail['cancel_time'],
            'transaction' => (string)$transaction->id,
        ]);
    }
}
