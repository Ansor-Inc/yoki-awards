<?php

namespace Modules\Purchase\Payment\Enums;

enum PaymentSystem: string
{
    case PAYME = 'payme';
    case CLICK = 'click';
}
