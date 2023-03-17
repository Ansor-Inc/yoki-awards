<?php

namespace Modules\User\Entities\Traits;

trait HasBalance
{
    public function getBalance(): int
    {
        return (int)$this->balance;
    }

    public function deposit(int $amount): void
    {
        $this->increment('balance', $amount);
    }

    public function withdraw(int $amount): void
    {
        $this->decrement('balance', $amount);
    }
}
