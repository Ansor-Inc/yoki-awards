<?php

namespace Modules\Purchase\Payment\Drivers\Click\Methods;

use Exception;
use Illuminate\Support\Facades\DB;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Interfaces\PurchaseRepositoryInterface;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\Drivers\Click\Merchant;
use Modules\Purchase\Payment\Drivers\Click\Requests\ClickCompleteRequest;
use Modules\Purchase\Payment\Drivers\Click\Response\Response as ClickResponse;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Service\PurchaseService;

class Complete
{
    use ValidatesRequest;

    public function __construct(private readonly TransactionRepositoryInterface $transactionRepository,
                                private readonly PurchaseRepositoryInterface    $purchaseRepository,
                                private readonly Merchant                       $merchant)
    {
    }

    public function execute(ClickCompleteRequest $request): void
    {
        $params = $request->validated();

        $this->merchant->authorizeCompleteRequest($params);

        $purchase = $this->validate($params);

        $transaction = $this->transactionRepository->getTransactionById($params['merchant_prepare_id'], PaymentSystem::CLICK);

        if (is_null($transaction)) {
            ClickResponse::error(ClickResponse::ERROR_TRANSACTION_NOT_FOUND);
        }

        if ($transaction->isCanceled()) {
            ClickResponse::error(ClickResponse::ERROR_TRANSACTION_CANCELLED);
        }

        if ((int)$params['error'] < 0) {
            $transaction->cancel($params['error_note']);
            ClickResponse::error(ClickResponse::ERROR_TRANSACTION_CANCELLED);
        }

        if ($params['error'] == 0) {

            $this->completePurchase($transaction, $purchase);

            ClickResponse::success(ClickResponse::SUCCESS, [
                'click_trans_id' => $params['click_trans_id'],
                'merchant_trans_id' => $purchase->id,
                'merchant_confirm_id' => $transaction->system_transaction_id
            ]);
        }

        ClickResponse::error(ClickResponse::ERROR_REQUEST_FROM);
    }

    private function completePurchase(Transaction $transaction, Purchase $purchase): void
    {
        try {
            DB::transaction(function () use ($transaction, $purchase) {
                PurchaseService::completePurchase($purchase);
                $transaction->complete();
            });
        } catch (Exception $exception) {
            report($exception);
            ClickResponse::error(ClickResponse::ERROR_UPDATE_ORDER);
        }
    }

}
