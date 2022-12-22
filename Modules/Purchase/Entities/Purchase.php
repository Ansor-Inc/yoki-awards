<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Book\Entities\Book;
use Modules\Purchase\Enums\PurchaseStatus;
use Modules\User\Entities\User;

class Purchase extends Model
{
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

    public function completed(): bool
    {
        return $this->state === PurchaseStatus::COMPLETED->value;
    }
}
