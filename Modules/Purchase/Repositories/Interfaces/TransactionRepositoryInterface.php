<?php

namespace Modules\Purchase\Repositories\Interfaces;

use Modules\Purchase\Enums\PaymentSystem;

interface TransactionRepositoryInterface
{
    public function getTransactionById(string $id, PaymentSystem $paymentSystem);

    public function getTransactionsByPeriod(string $from, string $to, PaymentSystem $paymentSystem);

    public function createTransaction(array $payload);
}
