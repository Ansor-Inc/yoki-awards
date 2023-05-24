<?php

namespace Modules\Purchase\Payment\Drivers\Payze;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Exceptions\InvalidPurchaseException;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\Contracts\PaymentDriverContract;
use Modules\Purchase\Payment\DataFormat;
use Modules\Purchase\Payment\Enums\PaymentSystem;
use Modules\Purchase\Service\PurchaseService;
use PayzeIO\LaravelPayze\Enums\Currency;
use PayzeIO\LaravelPayze\Enums\Language;
use PayzeIO\LaravelPayze\Exceptions\PaymentRequestException;
use Throwable;

class Payze implements PaymentDriverContract
{
    public function __construct(protected TransactionRepositoryInterface $transactionRepository)
    {
    }

    public function run(): void
    {
        $params = request()->all();

        $status = PayzeTransactionStatus::tryFrom($params['PaymentStatus']);

        $transaction = $this->transactionRepository->getTransactionById($params['PaymentId'], PaymentSystem::PAYZE);

        $purchase = $transaction->purchase;

        $transaction->update([
            'state' => $status->associatedLocalStatus()
        ]);

        if ($params['PaymentStatus'] == PayzeTransactionStatus::COMPLETED->value) {
            PurchaseService::completePurchase($purchase);
        }
    }

    /**
     * @throws Throwable
     */
    public function generateCheckoutLink(int $purchaseId, float $amount): string
    {
        $purchase = $this->getPurchase($purchaseId);

        $this->executePurchaseChecks($purchase, $amount);

        $response = $this->request($amount, $purchase);

        $url = $response['response']['transactionUrl'];
        $id = $response['response']['transactionId'];

        throw_if(empty($id) || empty($url), new PaymentRequestException('Transaction ID is missing'));

        $this->createTransaction($response, $amount, $purchase);

        return $url;
    }

    protected function getPurchase(int $purchaseId)
    {
        return Purchase::query()->findOrFail($purchaseId);
    }

    protected function request(float $amount, Purchase $purchase)
    {
        return (new JustPayWithProductInfo())
            ->currency(Currency::UZS)
            ->amount($amount)
            ->language(Language::UZB)
            ->product(new ProductInfo(
                image: $purchase->book->image,
                name: $purchase->book->title
            ))
            ->for($purchase)
            ->process();
    }

    /**
     * @throws Throwable
     */
    protected function executePurchaseChecks(Purchase|Builder $purchase, float $amount): void
    {
        throw_unless(PurchaseService::checkIsProperAmount($amount, $purchase), new InvalidPurchaseException('Invalid amount for this purchase!'));
        throw_unless(PurchaseService::checkPurchaseIsValidForPayment($purchase), new InvalidPurchaseException('Not valid purchase!'));
        throw_if(PurchaseService::checkPurchaseHasCompletedTransactions($purchase), new InvalidPurchaseException('Purchase has completed transactions!'));
    }

    protected function createTransaction(array $response, $amount, $purchase): void
    {
        $detail = array(
            'create_time' => DataFormat::timestamp(true),
            'perform_time' => null,
            'cancel_time' => null,
            'system_time_datetime' => Carbon::parse($response['createdDate'])
        );

        $this->transactionRepository->createTransaction([
            'payment_system' => PaymentSystem::PAYZE->value,
            'system_transaction_id' => $response['response']['transactionId'],
            'amount' => $amount,
            'state' => Transaction::STATE_CREATED,
            'updated_time' => $detail['create_time'],
            'comment' => $request->params['error_note'] ?? '',
            'detail' => $detail,
            'purchase_id' => $purchase->id
        ]);
    }
}
