<?php

namespace Modules\User\Enums;

enum UserDegree: string
{
    case GENIUS = 'GENIUS';
    case SCIENTIST = 'SCIENTIST';
    case CLEVER = 'CLEVER';

    case USER = 'USER';

    public function label()
    {
        return match ($this) {
            self::GENIUS => 'Geniylar',
            self::SCIENTIST => "Olimlar",
            self::CLEVER => "Aqllilar",
            self::USER => 'Foydalanuvchilar'
        };
    }

    public function interval()
    {
        return match ($this) {
            self::GENIUS => [50, PHP_INT_MAX],
            self::SCIENTIST => [25, 49],
            self::CLEVER => [1, 24],
            self::USER => [PHP_INT_MIN, 0]
        };
    }

    public function intervalToDisplay()
    {
        return match ($this) {
            self::GENIUS => "50+",
            self::SCIENTIST => "25-49",
            self::CLEVER => "1-24"
        };
    }

    public function icon()
    {
        return match ($this) {
            self::GENIUS => asset('media/genius_icon.svg'),
            self::SCIENTIST => asset('media/scientist_icon.svg'),
            self::CLEVER => asset('media/clever_icon.svg')
        };
    }

    public static function getDegreeFromBookCount(int $count)
    {
        foreach (self::cases() as $degree) {
            if ($degree->interval()[0] <= $count && $count <= $degree->interval()[1]) {
                return $degree;
            }
        }

        return self::USER->value;
    }

}
