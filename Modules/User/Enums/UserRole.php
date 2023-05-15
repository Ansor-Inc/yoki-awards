<?php

namespace Modules\User\Enums;

enum UserRole: string
{
    case BLOGGER = 'blogger';

    case APPROVED_BLOGGER = 'approved-blogger';
}
