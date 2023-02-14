<?php

namespace Modules\Purchase\Entities\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Purchase\Entities\Transaction;

trait HasTransactions
{
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeTransactions(): HasMany
    {
        return $this->transactions()->where('state', Transaction::STATE_CREATED);
    }

    public function completedTransactions(): HasMany
    {
        return $this->transactions()->where('state', Transaction::STATE_COMPLETED);
    }
}
