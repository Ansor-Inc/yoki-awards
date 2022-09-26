<?php

namespace Modules\Billing\Enums;

enum PurchaseStatus: string
{
    case PENDING_PAYMENT = 'PENDING_PAYMENT';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';
}