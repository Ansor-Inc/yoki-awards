<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function activeTransactions()
    {
        return $this->transactions()->where('state', Transaction::STATE_CREATED);
    }

    public function completedTransactions()
    {
        return $this->transactions()->where('state', Transaction::STATE_COMPLETED);
    }
}
