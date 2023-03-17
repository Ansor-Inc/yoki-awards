<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Methods;

use Illuminate\Http\Request;
use Modules\Purchase\DTO\PurchaseData;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Payment\Drivers\Payme\DTO\PaymeConfig;
use Modules\Purchase\Payment\Drivers\Payme\DTO\RequestParamsData;
use Modules\Purchase\Payment\Drivers\Payme\Response\Response as PaymeResponse;
use Modules\Purchase\Payment\Exceptions\PaymentException;
use Modules\Purchase\Service\PurchaseService;

class CheckPerformTransaction
{
    use ValidatesParams;

    public function __construct(private readonly PurchaseRepositoryInterface $repository,
                                private readonly PaymeConfig                 $config)
    {
    }

    /**
     * @throws PaymentException
     */
    public function execute(Request $request): void
    {
        $paramsData = $this->validateAndGetParams($request);

        $purchase = $this->repository->getPurchaseById(
            $paramsData->account[$this->config->key]
        );

        $this->executeChecks($purchase, $paramsData);

        PaymeResponse::success([
            'allow' => true,
            'detail' => $this->prepareCheckDetail($purchase)
        ]);
    }

    /**
     * @throws PaymentException
     */
    private function validateAndGetParams(Request $request): RequestParamsData
    {
        $params = $request->input('params');

        $this->validateParams($params);

        return RequestParamsData::from($params);
    }


    /**
     * @throws PaymentException
     */
    private function executeChecks(?Purchase $purchase, RequestParamsData $paramsData): void
    {
        //Checking if purchase exists
        if (is_null($purchase)) {
            PaymeResponse::error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Object not fount.');
        }

        $this->executeStateCheck($purchase);
        $this->executeAmountCheck($purchase, $paramsData);
        $this->executeTransactionChecks($purchase);
    }

    private function executeStateCheck(Purchase $purchase): void
    {
        //Checking purchase state
        if (!PurchaseService::checkPurchaseIsValidForPayment($purchase)) {
            PaymeResponse::error(PaymeResponse::ERROR_INVALID_ACCOUNT, 'Invalid purchase data. Completed, canceled purchase or purchase item does not exists (or free).');
        }
    }

    private function executeAmountCheck(Purchase $purchase, RequestParamsData $paramsData): void
    {
        //Checking amount of purchase
        $isProperAmount = PurchaseService::checkIsProperAmount($paramsData->amount / 100, $purchase);

        if (!$isProperAmount) {
            PaymeResponse::error(PaymeResponse::ERROR_INVALID_AMOUNT, 'Invalid amount for this object.');
        }
    }

    private function executeTransactionChecks(Purchase $purchase): void
    {
        //Checking whether purchase has active or completed transactions
        $hasActiveTransactions = PurchaseService::checkPurchaseHasActiveTransactions($purchase);
        $hasCompletedTransaction = PurchaseService::checkPurchaseHasCompletedTransactions($purchase);

        if ($hasActiveTransactions || $hasCompletedTransaction) {
            PaymeResponse::error(PaymeResponse::ERROR_INVALID_TRANSACTION, 'There is other active/completed transaction for this object.');
        }
    }

    private function prepareCheckDetail($purchase): array
    {
        $purchaseData = PurchaseData::fromModel($purchase);

        return [
            'receipt_type' => 0,

            'items' => [
                [
                    'title' => $purchaseData->title,
                    'price' => $purchaseData->amount * 100,
                    'count' => $purchaseData->count,
                    'code' => $purchaseData->itemCode,
                    'package_code' => $purchaseData->packageCode,
                    'vat_percent' => $purchaseData->vatPercent,
                ]
            ]
        ];
    }
}
