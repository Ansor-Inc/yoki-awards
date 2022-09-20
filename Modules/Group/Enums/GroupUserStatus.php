<?php

namespace Modules\Group\Enums;

enum GroupUserStatus: string
{
    case JOINED = 'JOINED';
    case REQUESTED_TO_JOIN = 'REQUESTED_TO_JOIN';
    case NOT_JOINED = 'NOT_JOINED';
    case REJECTED = 'REJECTED';
}