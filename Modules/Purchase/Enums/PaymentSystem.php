<?php

namespace Modules\Purchase\Enums;

enum PaymentSystem: string
{
    case PAYME = 'PAYME';
    case CLICK = 'CLICK';
}