<?php

namespace Modules\Purchase\Actions\Payme;

use Illuminate\Http\Request;
use Modules\Purchase\Enums\PaymentSystem;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Payme\Response as PaymeResponse;
use Modules\Purchase\Repositories\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Repositories\Interfaces\TransactionRepositoryInterface;

class GetStatementAction
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
        $this->validateGetStatementParams($request);

        // get list of transactions for specified period
        $transactions = $this->getReport($request->params['from'], $this->request->params['to']);

        // send results back
        $this->response->success(['transactions' => $transactions]);
    }

    private function validateGetStatementParams($request)
    {
        // validate 'from'
        if (!isset($request->params['from'])) {
            $this->response->error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Incorrect period.', 'from');
        }

        // validate 'to'
        if (!isset($request->params['to'])) {
            $this->response->error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Incorrect period.', 'to');
        }

        // validate period
        if (1 * $request->params['from'] >= 1 * $this->request->params['to']) {
            $this->response->error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Incorrect period. (from >= to)', 'from');
        }
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
