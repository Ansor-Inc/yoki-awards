<?php

namespace Modules\Purchase\Payment\Enums;

use phpDocumentor\Reflection\Types\Self_;

enum PaymentSystem: string
{
    case PAYME = 'payme';

    case CLICK = 'click';

    case PAYZE = 'payze';

    public function getIpWhitelist(): array
    {
        return match ($this) {
            self::PAYME => config('billing.payme.ip_whitelist'),
            self::CLICK => config('billing.click.ip_whitelist'),
            self::PAYZE => config('billing.payze.ip_whitelist')
        };
    }

}
