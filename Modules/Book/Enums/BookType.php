<?php

namespace Modules\Book\Enums;

enum BookType: string
{
    case E_BOOK = 'E_BOOK';
    case AUDIO_BOOK = 'AUDIO_BOOK';

    public function label()
    {
        return match ($this) {
            self::E_BOOK => 'E-Book',
            self::AUDIO_BOOK => 'Audio Book'
        };
    }

}