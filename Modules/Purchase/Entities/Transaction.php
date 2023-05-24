<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Purchase\Payment\DataFormat;

class Transaction extends Model
{
    use SoftDeletes;

    public const STATE_CREATED = 1;
    public const STATE_COMPLETED = 2;
    public const STATE_CANCELLED = -1;
    public const STATE_CANCELLED_AFTER_COMPLETE = -2;

    public const TIMEOUT = 43200000;

    protected array $dates = ['deleted_at'];

    protected $casts = ['detail' => 'json'];

    protected $fillable = [
        'payment_system',
        'system_transaction_id',
        'amount',
        'state',
        'updated_time',
        'comment',
        'purchase_id',
        'detail'
    ];

    public function isCreated(): bool
    {
        return $this->getAttribute('state') === self::STATE_CREATED;
    }

    public function isCompleted(): bool
    {
        return $this->getAttribute('state') === self::STATE_COMPLETED;
    }

    public function isCanceled(): bool
    {
        return (int)$this->getAttribute('state') === self::STATE_CANCELLED ||
            (int)$this->getAttribute('state') === self::STATE_CANCELLED_AFTER_COMPLETE;
    }

    public function isExpired(): bool
    {
        return (int)$this->getAttribute('state') === self::STATE_CREATED &&
            (DataFormat::timestamp(true) - $this->getAttribute('updated_time')) > self::TIMEOUT;
    }

    public function complete()
    {
        $performTime = DataFormat::timestamp(true);

        $this->update([
            'state' => Transaction::STATE_COMPLETED,
            'updated_time' => $performTime,
            'detail' => array_merge($this->detail, ['perform_time' => $performTime])
        ]);
    }

    public function cancel($reason): void
    {
        $this->update($this->preparePayloadToCancelTransaction($reason));
    }

    private function preparePayloadToCancelTransaction($reason): array
    {
        $updatedTime = DataFormat::timestamp(true);

        $payload = [
            'updated_time' => $updatedTime,
            'comment' => $reason,
            'detail' => array_merge($this->getAttribute('detail'), ['cancel_time' => $updatedTime])
        ];

        if ($this->isCompleted()) {
            // Scenario: CreateTransaction -> PerformTransaction -> CancelTransaction
            $payload['state'] = self::STATE_CANCELLED_AFTER_COMPLETE;
        }

        if ($this->isCreated()) {
            // Scenario: CreateTransaction -> CancelTransaction
            $payload['state'] = self::STATE_CANCELLED;
        }

        return $payload;
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }
}
