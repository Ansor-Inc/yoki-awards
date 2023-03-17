<?php

namespace Modules\Purchase\Payment\Drivers\Click\Methods;

use Exception;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Drivers\Click\Merchant;
use Modules\Purchase\Payment\Drivers\Click\Requests\ClickPrepareRequest;
use Modules\Purchase\Payment\Drivers\Click\Response\Response as ClickResponse;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Payment\Exceptions\PaymentException;

class Prepare
{
    use ValidatesRequest;

    public function __construct(private readonly TransactionRepositoryInterface $transactionRepository,
                                private readonly PurchaseRepositoryInterface    $purchaseRepository,
                                private readonly Merchant                       $merchant)
    {
    }

    /**
     * @throws PaymentException
     */
    public function execute(ClickPrepareRequest $request): void
    {
        $params = $request->validated();

        $this->merchant->authorizePrepareRequest($params);

        $purchase = $this->validate($params);

        $transaction = $this->getTransaction($purchase, $params);

        if ($params['error'] != ClickResponse::SUCCESS) {
            ClickResponse::error($params['error']);
        }

        ClickResponse::success(ClickResponse::SUCCESS, [
            'click_trans_id' => $params['click_trans_id'],
            'merchant_trans_id' => $purchase->id,
            'merchant_prepare_id' => $transaction->system_transaction_id,
        ]);
    }

    private function getTransaction(Purchase $purchase, array $params)
    {
        try {
            $createTime = DataFormat::timestamp(true);

            if ($purchase->activeTransactions()->exists()) {
                return $purchase->activeTransactions()->first();
            }

            return $this->transactionRepository->createTransaction([
                'payment_system' => PaymentSystem::CLICK->value,
                'system_transaction_id' => $params['click_trans_id'],
                'amount' => $params['amount'],
                'state' => Transaction::STATE_CREATED,
                'updated_time' => $createTime,
                'comment' => $params['error_note'] ?? '',
                'detail' => [
                    'create_time' => $createTime,
                    'system_time_datetime' => DataFormat::timestamp2datetime($params['sign_time'])
                ],
                'purchase_id' => $purchase->id
            ]);
        } catch (Exception $exception) {
            report($exception);
            ClickResponse::error(ClickResponse::ERROR_UPDATE_ORDER);
        }
    }
}
