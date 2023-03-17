<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Book\Entities\Book;
use Modules\Purchase\Entities\Traits\HasTransactions;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\User\Entities\User;

class Purchase extends Model
{
    use HasTransactions;

    protected $guarded = ['id'];

    protected $casts = [
        'user_data' => 'array',
        'book_data' => 'array'
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pending(): bool
    {
        return $this->state === PurchaseStatus::PENDING_PAYMENT->value;
    }

    public function canceled(): bool
    {
        return $this->state === PurchaseStatus::CANCELED->value;
    }

    public function completed(): bool
    {
        return $this->state === PurchaseStatus::COMPLETED->value;
    }

    public function scopeCompleted($query)
    {
        $query->where('state', PurchaseStatus::COMPLETED->value);
    }

    public function scopePending($query)
    {
        $query->where('state', PurchaseStatus::PENDING_PAYMENT->value);
    }

    public function scopeCanceled($query)
    {
        $query->where('state', PurchaseStatus::CANCELED->value);
    }

    public function scopeOfBook($query, Book $book)
    {
        $query->where('book_id', $book->id);
    }

    public function complete()
    {
        $this->update(['state' => PurchaseStatus::COMPLETED->value]);
    }

    public function cancel()
    {
        $this->update(['state' => PurchaseStatus::CANCELED->value]);
    }

    public function isPending(): bool
    {
        return $this->state === PurchaseStatus::PENDING_PAYMENT->value;
    }

    public function getPaidAmount(): float
    {
        return (float)($this->amount - $this->from_balance);
    }

    public function userHasEnoughBalance(float $amount): bool
    {
        return $this->user->getBalance() >= $amount;
    }
}
