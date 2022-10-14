<?php

namespace Modules\User\Enums;

enum CodeTransportDriver: string
{
    case TELEGRAMBOT = 'TELEGRAMBOT';
    case PLAYMOBILE = 'PLAYMOBILE';
}