<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Methods;

use Illuminate\Http\Request;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Exceptions\PaymentException;

class CheckTransaction
{
    use ValidatesParams;

    public function __construct(private readonly TransactionRepositoryInterface $transactionRepository)
    {
    }

    /**
     * @throws PaymentException
     */
    public function execute(Request $request): void
    {
        $params = $this->validateAndGetParams($request);

        $transaction = $this->getTransactionById($params['id']);

        $detail = $transaction->detail;

        PaymeResponse::success([
            'create_time' => 1 * $detail['create_time'],
            'perform_time' => 1 * $detail['perform_time'],
            'cancel_time' => 1 * $detail['cancel_time'],
            'transaction' => (string)$transaction->id,
            'state' => 1 * $transaction->state,
            'reason' => ($transaction->comment && is_numeric($transaction->comment)) ? 1 * $transaction->comment : null,
        ]);
    }

    /**
     * @throws PaymentException
     */
    private function validateAndGetParams(Request $request): array
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
        if (is_null($transaction)) {
            PaymeResponse::error(PaymeResponse::ERROR_TRANSACTION_NOT_FOUND, 'Transaction not found.');
        }
    }
}
