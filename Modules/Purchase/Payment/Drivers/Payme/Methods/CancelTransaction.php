<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Methods;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\Drivers\Payme\DTO\PaymeConfig;
use Modules\Purchase\Payment\Drivers\Payme\Enums\Reason;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Exceptions\PaymentException;
use Modules\Purchase\Service\PurchaseService;

class CancelTransaction
{
    use ValidatesParams;

    public function __construct(private readonly PaymeConfig                    $config,
                                private readonly TransactionRepositoryInterface $transactionRepository,
                                private readonly PurchaseRepositoryInterface    $purchaseRepository,
                                private readonly Request                        $request)
    {
    }

    /**
     * @throws PaymentException
     */
    public function execute(Request $request): void
    {
        $params = $this->validateAndGetParams($request);

        $transaction = $this->getTransactionById($params['id']);

        $this->checkTransactionExists($transaction);
        $this->cancelTransactionIfStateIsCreated($transaction, Reason::from($params['reason']));
        $this->cancelTransactionIfStateIsCompleted($transaction, Reason::from($params['reason']));
        $this->successResponse($transaction);
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

    private function getTransactionById(string $id)
    {
        return $this->transactionRepository->getTransactionById($id, PaymentSystem::PAYME);
    }

    private function checkTransactionExists(?Transaction $transaction): void
    {
        // if transaction not found, send error
        if (is_null($transaction)) {
            PaymeResponse::error(PaymeResponse::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }
    }

    private function cancelTransactionIfStateIsCreated(Transaction $transaction, Reason $reason): void
    {
        if ($transaction->isCreated()) {
            $this->cancelTransaction($transaction, $reason);
            $this->successResponse($transaction);
        }
    }

    private function cancelTransactionIfStateIsCompleted(Transaction $transaction, Reason $reason): void
    {
        if ($transaction->isCompleted()) {
            $this->cancelTransactionAfterComplete($transaction, $reason);
            $this->successResponse($transaction);
        }
    }

    private function cancelTransaction($transaction, Reason $reason): void
    {
        // cancel transaction with given reason
        $transaction->cancel($reason->value);
    }

    private function cancelTransactionAfterComplete(Transaction $transaction, Reason $reason): void
    {
        DB::transaction(function () use ($transaction, $reason) {
            PurchaseService::cancelPurchase(
                $this->purchaseRepository->getPurchaseById($transaction->getAttribute('purchase_id'))
            );

            $this->cancelTransaction($transaction, $reason);
        });
    }

    /**
     * @throws PaymentException
     */
    private function successResponse(Transaction $transaction): void
    {
        PaymeResponse::success([
            'state' => 1 * $transaction->getAttribute('state'),
            'cancel_time' => $transaction->getAttribute('detail')['cancel_time'],
            'transaction' => (string)$transaction->getAttribute('id'),
        ]);
    }
}
