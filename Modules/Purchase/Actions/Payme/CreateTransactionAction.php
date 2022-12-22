<?php

namespace Modules\Purchase\Actions\Payme;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Enums\PaymentSystem;
use Modules\Purchase\Exceptions\PaymentException;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Payme\Response as PaymeResponse;
use Modules\Purchase\Repositories\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Repositories\Interfaces\TransactionRepositoryInterface;

class CreateTransactionAction
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
        $this->validateParams($request->params);
        $this->validateTransactionId($request->params);

        $transaction = $this->transactionRepository->getTransactionById($request->params['id'], PaymentSystem::PAYME);

        //Check whether transaction exists
        if (isset($transaction)) {
            $this->checkTransactionState($transaction);
        }

        try {
            app(CheckPerformTransactionAction::class)->execute($request);
        } catch (PaymentException $exception) {
            if (!$exception->response->ok()) {
                throw $exception;
            }
        }

        $createTime = DataFormat::timestamp(true);

        $detail = array(
            'create_time' => $createTime,
            'perform_time' => null,
            'cancel_time' => null,
            'system_time_datetime' => Carbon::parse($request->params['time'])
        );

        $transaction = $this->transactionRepository->createTransaction([
            'payment_system' => PaymentSystem::PAYME->value,
            'system_transaction_id' => $request->params['id'],
            'amount' => 1 * ($request->params['amount']) / 100,
            'state' => Transaction::STATE_CREATED,
            'updated_time' => $createTime,
            'comment' => $request->params['error_note'] ?? '',
            'detail' => $detail,
            'purchase_id' => $request->params['account'][$this->config['key']]
        ]);


        $this->response->success([
            'state' => 1 * $transaction->state,
            'create_time' => 1 * $transaction->updated_time,
            'transaction' => (string)$transaction->id,
            'receivers' => $transaction->receivers,
        ]);
    }

    private function checkTransactionState($transaction)
    {
        if ($transaction->state !== Transaction::STATE_CREATED) {
            $this->response->error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'Transaction found, but is not active.');
        }

        if ($transaction->isExpired()) {
            //Check whether transaction is expired
            $transaction->cancel(Transaction::REASON_CANCELLED_BY_TIMEOUT);
            $this->response->error(PaymeResponse::ERROR_COULD_NOT_PERFORM, 'Transaction is expired.');
        }

        $this->response->success([
            'state' => $transaction->state,
            'create_time' => 1 * $transaction->updated_time,
            'transaction' => (string)$transaction->id,
            'receivers' => $transaction->receivers,
        ]);
    }
}
