<?php

namespace Modules\Group\Enums;

enum GroupStatus: string
{
    case PENDING_APPROVAL = 'PENDING_APPROVAL';
    case APPROVED = 'APPROVED';
    case REJECTED = 'REJECTED';
}