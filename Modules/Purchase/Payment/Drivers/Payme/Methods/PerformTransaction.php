<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Methods;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\Drivers\Payme\Enums\Reason;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Exceptions\PaymentException;
use Modules\Purchase\Service\PurchaseService;

class PerformTransaction
{
    use ValidatesParams;

    public function __construct(private readonly TransactionRepositoryInterface $transactionRepository,
                                private readonly PurchaseRepositoryInterface    $purchaseRepository)
    {
    }

    /**
     * @throws PaymentException
     */
    public function execute(Request $request): void
    {
        $params = $this->validateAndGetParams($request);

        $transaction = $this->transactionRepository->getTransactionById($params['id'], PaymentSystem::PAYME);

        // if transaction not found, send error
        if (is_null($transaction)) {
            PaymeResponse::error(PaymeResponse::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }

        if ($transaction->isCreated()) {

            if ($transaction->isExpired()) {
                $this->cancelTransactionIfExpired($transaction);
                PaymeResponse::error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'Transaction is expired.');
            }

            $this->closeTransaction($transaction);

            $this->successResponse($transaction);
        }

        if ($transaction->isCompleted()) {
            $this->successResponse($transaction);
        }

        PaymeResponse::error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'Could not perform this operation.');
    }

    /**
     * @throws PaymentException
     */
    private function validateAndGetParams(Request $request)
    {
        $params = $request->input('params');

        $this->validateTransactionId($params);

        return $params;
    }

    private function cancelTransactionIfExpired(Transaction $transaction): void
    {
        $transaction->cancel(Reason::REASON_CANCELLED_BY_TIMEOUT);
    }

    private function closeTransaction(Transaction $transaction): void
    {
        $purchase = $this->purchaseRepository->getPurchaseById($transaction->purchase_id);

        DB::transaction(function () use ($transaction, $purchase) {
            PurchaseService::completePurchase($purchase);
            $transaction->complete();
        });
    }

    private function successResponse(Transaction $transaction): void
    {
        PaymeResponse::success([
            'transaction' => (string)$transaction->id,
            'perform_time' => 1 * $transaction->detail['perform_time'],
            'state' => $transaction->state,
        ]);
    }
}
