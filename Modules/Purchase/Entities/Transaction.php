<?php

namespace Modules\Purchase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Purchase\Payment\DataFormat;

class Transaction extends Model
{
    use SoftDeletes;

    const STATE_CREATED = 1;
    const STATE_COMPLETED = 2;
    const STATE_CANCELLED = -1;
    const STATE_CANCELLED_AFTER_COMPLETE = -2;

    const REASON_RECEIVERS_NOT_FOUND = 1;
    const REASON_PROCESSING_EXECUTION_FAILED = 2;
    const REASON_EXECUTION_FAILED = 3;
    const REASON_CANCELLED_BY_TIMEOUT = 4;
    const REASON_FUND_RETURNED = 5;
    const REASON_UNKNOWN = 10;

    const TIMEOUT = 43200000;

    protected $dates = ['deleted_at'];

    protected $casts = ['detail' => 'json'];

    protected $fillable = [
        'payment_system', //varchar 191
        'system_transaction_id', // varchar 191
        'amount', // double (15,5)
        'state', // int(11)
        'updated_time', //datetime
        'comment', // varchar 191
        'purchase_id',
        'detail', // details
    ];

    public function cancel($reason)
    {
        $this->updated_time = DataFormat::timestamp(true);

        if ($this->state === self::STATE_COMPLETED) {
            // Scenario: CreateTransaction -> PerformTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED_AFTER_COMPLETE;
        }

        if ($this->state === self::STATE_CREATED) {
            // Scenario: CreateTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED;
        }

        $this->comment = $reason;
        $detail = $this->detail;
        $detail['cancel_time'] = $this->updated_time;
        $this->detail = $detail;

        $this->update();
    }

    public function isExpired()
    {
        return $this->state === self::STATE_CREATED && (DataFormat::timestamp(true) - $this->updated_time) > self::TIMEOUT;
    }
}
