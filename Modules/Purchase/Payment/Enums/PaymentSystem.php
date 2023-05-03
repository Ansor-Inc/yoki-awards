<?php

namespace Modules\Purchase\Payment\Enums;

enum PaymentSystem: string
{
    case PAYME = 'payme';

    case CLICK = 'click';

    public function getIpWhitelist(): array
    {
        return match ($this) {
            self::PAYME => config('billing.payme.ip_whitelist'),
            self::CLICK => config('billing.click.ip_whitelist')
        };
    }

}
