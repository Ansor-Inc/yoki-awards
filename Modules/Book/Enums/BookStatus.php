<?php

namespace Modules\Book\Enums;

enum BookStatus: string
{
    case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';

    public function label(): string
    {
        return match ($this) {
            self::PENDING_APPROVAL => 'Pending approval',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected'
        };
    }
}