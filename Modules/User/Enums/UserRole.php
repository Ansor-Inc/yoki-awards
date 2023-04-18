<?php

namespace Modules\User\Enums;

enum UserRole: string
{
    case USER = 'user';

    case AMATEUR_BLOGGER = 'amateur-blogger';

    case BLOGGER = 'blogger';

    public function permissions(): array
    {
        return match ($this) {
            self::AMATEUR_BLOGGER => [UserPermissions::CAN_CREATE_ARTICLE->value],
            self::BLOGGER => [UserPermissions::CAN_CREATE_ARTICLE->value, UserPermissions::CAN_PUBLISH_ARTICLE->value],
            self::USER => []
        };
    }
}
