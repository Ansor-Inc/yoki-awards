<?php

namespace Modules\Purchase\Payment\Drivers\Payme\Enums;

enum Reason: int
{
    case REASON_RECEIVERS_NOT_FOUND = 1;
    case REASON_PROCESSING_EXECUTION_FAILED = 2;
    case REASON_EXECUTION_FAILED = 3;
    case REASON_CANCELLED_BY_TIMEOUT = 4;
    case REASON_FUND_RETURNED = 5;
    case REASON_UNKNOWN = 10;
}
