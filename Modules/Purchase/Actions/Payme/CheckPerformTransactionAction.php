<?php

namespace Modules\Purchase\Actions\Payme;

use Illuminate\Http\Request;
use Modules\Purchase\Payment\Payme\Response as PaymeResponse;
use Modules\Purchase\Repositories\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Service\Interfaces\PurchaseServiceInterface;

class CheckPerformTransactionAction
{
    use ValidatesParams;

    private mixed $config;

    public function __construct(private PaymeResponse               $response,
                                private PurchaseRepositoryInterface $repository,
                                private PurchaseServiceInterface    $purchaseService)
    {
        $this->config = config('billing.payme');
    }

    public function execute(Request $request)
    {
        $this->validateParams($request->input('params'));

        $purchaseId = $request->params['account'][$this->config['key']];
        $amount = (float)$request->params['amount'];

        $purchase = $this->repository->getPurchaseById($purchaseId);

        //Checking if purchase exists
        if (is_null($purchase)) {
            $this->response->error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Object not fount.');
        }

        //Checking purchase state
        if (!$this->purchaseService->checkPurchaseIsValidForPayment($purchase)) {
            $this->response->error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Invalid purchase data. Completed, canceled purchase or purchase item does not exists (or free).');
        }

        //Checking amount of purchase
        $isProperAmount = $this->purchaseService->checkIsProperAmount($amount, $purchase);

        if (!$isProperAmount) {
            $this->response->error(PaymeResponse::ERROR_INVALID_AMOUNT, 'Invalid amount for this object.');
        }

        //Checking whether purchase has active or completed transactions
        $hasActiveTransactions = $this->purchaseService->checkPurchaseHasActiveTransactions($purchase);
        $hasCompletedTransaction = $this->purchaseService->checkPurchaseHasCompletedTransactions($purchase);

        if ($hasActiveTransactions || $hasCompletedTransaction) {
            $this->response->error(PaymeResponse::ERROR_INVALID_TRANSACTION, 'There is other active/completed transaction for this object.');
        }

        $this->response->success([
            'allow' => true,
            'detail' => $this->prepareCheckDetail($purchase)
        ]);
    }

    private function prepareCheckDetail($purchase)
    {
        return [
            'receipt_type' => 0,

            'items' => [
                [
                    'title' => $this->purchaseService->getPurchaseItemTitle($purchase),
                    'price' => $this->purchaseService->getPurchaseAmount($purchase),
                    'count' => $this->purchaseService->getPurchaseItemsCount($purchase),
                    'code' => $this->purchaseService->getPurchaseItemCode($purchase),
                    'package_code' => $this->purchaseService->getPurchaseItemPackageCode($purchase),
                    'vat_percent' => $this->purchaseService->getPurchaseVatPercent($purchase),
                ]
            ]
        ];
    }
}
