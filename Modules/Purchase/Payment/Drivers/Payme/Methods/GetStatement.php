<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Methods;

use Illuminate\Http\Request;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Exceptions\PaymentException;

class GetStatement
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
        $params = $this->validateGetStatementParams($request);

        // get list of transactions for specified period
        $transactions = $this->getReport($params['from'], $params['to']);

        // send results back
        PaymeResponse::success(['transactions' => $transactions]);
    }

    /**
     * @throws PaymentException
     */
    private function validateGetStatementParams($request): array
    {
        $params = $request->input('params');

        // validate 'from'
        if (!isset($params['from'])) {
            PaymeResponse::error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Incorrect period.', 'from');
        }

        // validate 'to'
        if (!isset($params['to'])) {
            PaymeResponse::error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Incorrect period.', 'to');
        }

        // validate period
        if (1 * $params['from'] >= 1 * $params['to']) {
            PaymeResponse::error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Incorrect period. (from >= to)', 'from');
        }

        return $params;
    }

    private function getReport($from, $to)
    {
        $from = DataFormat::timestamp2datetime($from);
        $to = DataFormat::timestamp2datetime($to);

        $transactions = $this->transactionRepository->getTransactionsByPeriod($from, $to, PaymentSystem::PAYME);

        return $transactions->map(fn($transaction) => [
            'id' => (string)$transaction->system_transaction_id,
            'time' => 1 * $transaction->detail['system_time_datetime'],
            'amount' => 1 * $transaction['amount'],
            'account' => [
                'key' => 1 * $transaction->purchase_id,
            ],
            'create_time' => DataFormat::datetime2timestamp($transaction->detail['create_time']),
            'perform_time' => DataFormat::datetime2timestamp($transaction->detail['perform_time']),
            'cancel_time' => DataFormat::datetime2timestamp($transaction->detail['cancel_time']),
            'transaction' => (string)$transaction->id,
            'state' => 1 * $transaction->state,
            'reason' => $transaction->comment,
            'receivers' => null,
        ])->toArray();
    }
}
