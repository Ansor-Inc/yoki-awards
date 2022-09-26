<?php

namespace App\Models;

use Goodoneuz\PayUz\Http\Classes\DataFormat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes;

    protected $dates = [
        'deleted_at'
    ];
    protected $casts = [
        'detail' => 'json',
    ];
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
    const TIMEOUT = 43200000;

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

    public function cancel($reason)
    {
        $this->updated_time = DataFormat::timestamp(true);

        if ($this->state == self::STATE_COMPLETED) {
            // Scenario: CreateTransaction -> PerformTransaction -> CancelTransaction
            $this->state = self::STATE_CANCELLED_AFTER_COMPLETE;
        } else {
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
        return $this->state == self::STATE_CREATED && $this->updated_time - time() > self::TIMEOUT;
    }
}
