<?php

namespace Modules\Purchase\Actions\Payme;

use Illuminate\Http\Request;
use Modules\Book\Entities\Book;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\Purchase\Payment\Payme\Response as PaymeResponse;
use Modules\Purchase\Repositories\Interfaces\PurchaseRepositoryInterface;

class CheckPerformTransactionAction
{
    use ValidatesParams;

    private mixed $config;

    public function __construct(private PaymeResponse $response, private PurchaseRepositoryInterface $repository)
    {
        $this->config = config('billing.payme');
    }

    public function execute(Request $request)
    {
        $this->validateParams($request->input('params'));

        $purchase = $this->repository->getPurchaseById($request->params['account'][$this->config['key']]);

        //Checking if purchase exists

        if (is_null($purchase)) {
            $this->response->error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Object not fount.');
        }

        //Checking purchase state
        if ($this->repository->checkPurchaseIsValidForPayment($purchase)) {
            $this->response->error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Invalid purchase data. Completed, canceled purchase or purchase item does not exists (or free).');
        }

        //Checking amount of purchase
        $isProperAmount = (float)$request->params['amount'] === (float)$purchase->amount * 100;

        if (!$isProperAmount) {
            $this->response->error(PaymeResponse::ERROR_INVALID_AMOUNT, 'Invalid amount for this object.');
        }

        //Checking whether purchase has active or completed transactions
        $hasActiveTransactions = $purchase->activeTransactions()->exists();
        $hasCompletedTransaction = $purchase->completedTransactions()->exists();

        if ($hasActiveTransactions || $hasCompletedTransaction) {
            $this->response->error(PaymeResponse::ERROR_INVALID_TRANSACTION, 'There is other active/completed transaction for this object.');
        }

        $this->response->success(['allow' => true]);
    }
}
