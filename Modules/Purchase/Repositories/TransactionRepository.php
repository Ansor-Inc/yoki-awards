<?php

namespace Modules\Purchase\Repositories;

use Modules\Purchase\Entities\Transaction;
use Modules\Purchase\Interfaces\TransactionRepositoryInterface;
use Modules\Purchase\Payment\Enums\PaymentSystem;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function getTransactionById(string $id, PaymentSystem $paymentSystem)
    {
        return Transaction::query()
            ->where('payment_system', $paymentSystem->value)
            ->where('system_transaction_id', $id)
            ->firstOrFail();
    }

    public function getTransactionsByPeriod(string $from, string $to, PaymentSystem $paymentSystem)
    {
        return Transaction::query()->where('payment_system', $paymentSystem->value)
            ->whereBetween('created_at', [$from, $to])
            ->get();
    }

    public function createTransaction(array $payload)
    {
        return Transaction::query()->create($payload);
    }
}
