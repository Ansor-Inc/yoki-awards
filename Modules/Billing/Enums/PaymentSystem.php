<?php

namespace Modules\Billing\Enums;

enum PaymentSystem: string
{
    case PAYME = 'payme';
    case CLICK = 'click';
}