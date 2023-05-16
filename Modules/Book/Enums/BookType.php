<?php

namespace Modules\Book\Enums;

enum BookType: string
{
    case E_BOOK = 'E_BOOK';
    case AUDIO_BOOK = 'AUDIO_BOOK';
    case NON_COMMERCIAL = 'NON_COMMERCIAL';

    public function label(): string
    {
        return match ($this) {
            self::E_BOOK => 'E-Book',
            self::AUDIO_BOOK => 'Audio Book',
            self::NON_COMMERCIAL => 'Non-commercial'
        };
    }
}
