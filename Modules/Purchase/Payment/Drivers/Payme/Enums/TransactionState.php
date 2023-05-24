<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Enums;

enum TransactionState: int
{
    public const STATE_CREATED = 1;
    public const STATE_COMPLETED = 2;
    public const STATE_CANCELLED = -1;
    public const STATE_CANCELLED_AFTER_COMPLETE = -2;
}
