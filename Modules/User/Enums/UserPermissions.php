<?php

namespace Modules\User\Enums;

enum UserPermissions: string
{
    case CAN_PUBLISH_ARTICLE = 'user.blog.publish';
    case CAN_CREATE_ARTICLE = 'user.blog.create';
}
