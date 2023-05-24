<?php

namespace Modules\Purchase\Payment\Drivers\Payze;

use Modules\Purchase\Payment\Drivers\Payme\Enums\TransactionState;

enum PayzeTransactionStatus: string
{
    case CREATED = 'Draft';
    case BLOCKED = 'Blocked';
    case CANCELLED = 'Rejected';
    case COMPLETED = 'Captured';
    case REFUNDED = 'Refunded';
    case PARTIALLY_REFUNDED = 'PartiallyRefunded';

    public function associatedLocalStatus(): int|string
    {
        return match ($this) {
            self::CREATED => TransactionState::STATE_CREATED,
            self::CANCELLED => TransactionState::STATE_CANCELLED,
            self::COMPLETED => TransactionState::STATE_COMPLETED,
            self::REFUNDED => self::REFUNDED->value,
            self::BLOCKED => self::BLOCKED->value,
            self::PARTIALLY_REFUNDED => self::PARTIALLY_REFUNDED->value
        };
    }
}
