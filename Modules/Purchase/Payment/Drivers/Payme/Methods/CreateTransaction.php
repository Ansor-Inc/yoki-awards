<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Methods;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Drivers\Payme\DTO\PaymeConfig;
use Modules\Purchase\Payment\Drivers\Payme\Enums\Reason;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Exceptions\PaymentException;

class CreateTransaction
{
    use ValidatesParams;

    public function __construct(private readonly PaymeConfig                    $config,
                                private readonly TransactionRepositoryInterface $transactionRepository)
    {
    }

    /**
     * @throws PaymentException
     */
    public function execute(Request $request): void
    {
        $params = $this->validateAndGetParams($request);

        $transaction = $this->getTransactionById($params['id']);

        //Check whether transaction exists
        if (isset($transaction)) {
            $this->executeTransactionChecks($transaction);
        }

        $this->checkPerformTransaction($request);

        $transaction = $this->createTransaction($params);

        $this->successResponse($transaction);
    }

    /**
     * @throws PaymentException
     */
    private function checkPerformTransaction(Request $request): void
    {
        try {
            app(CheckPerformTransaction::class)->execute($request);
        } catch (PaymentException $exception) {
            if (!$exception->response->ok()) {
                throw $exception;
            }
        }
    }

    /**
     * @throws PaymentException
     */
    private function validateAndGetParams(Request $request): array
    {
        $params = $request->input('params');

        $this->validateParams($params);

        $this->validateTransactionId($params);

        return $params;
    }

    private function getTransactionById(string $id)
    {
        return $this->transactionRepository->getTransactionById($id, PaymentSystem::PAYME);
    }

    private function createTransaction(array $params)
    {
        $createTime = DataFormat::timestamp(true);

        $detail = array(
            'create_time' => $createTime,
            'perform_time' => null,
            'cancel_time' => null,
            'system_time_datetime' => Carbon::parse($params['time'])
        );

        return $this->transactionRepository->createTransaction([
            'payment_system' => PaymentSystem::PAYME->value,
            'system_transaction_id' => $params['id'],
            'amount' => 1 * ($params['amount']) / 100,
            'state' => Transaction::STATE_CREATED,
            'updated_time' => $createTime,
            'comment' => $request->params['error_note'] ?? '',
            'detail' => $detail,
            'purchase_id' => $params['account'][$this->config->key]
        ]);
    }

    /**
     * @throws PaymentException
     */
    private function executeTransactionChecks(Transaction $transaction): void
    {
        if ($transaction->getAttribute('state') !== Transaction::STATE_CREATED) {
            PaymeResponse::error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'Transaction found, but is not active.');
        }

        if ($transaction->isExpired()) {
            //Check whether transaction is expired
            $transaction->cancel(Reason::REASON_CANCELLED_BY_TIMEOUT);
            PaymeResponse::error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'Transaction is expired.');
        }

        $this->successResponse($transaction);
    }

    /**
     * @throws PaymentException
     */
    private function successResponse(Transaction $transaction): void
    {
        PaymeResponse::success([
            'state' => $transaction->getAttribute('state'),
            'create_time' => 1 * $transaction->getAttribute('updated_time'),
            'transaction' => (string)$transaction->getAttribute('id'),
            'receivers' => $transaction->getAttribute('receivers'),
        ]);
    }
}
