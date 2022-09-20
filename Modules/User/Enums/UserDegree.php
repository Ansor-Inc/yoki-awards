<?php

namespace Modules\User\Enums;

enum UserDegree: string
{
    case GENIUS = 'GENIUS';
    case SCIENTIST = 'SCIENTIST';
    case CLEVER = 'CLEVER';
    case ANY = '*';

    public function degreeMapping()
    {
        return [
            self::CLEVER->value => [1, 24],
            self::SCIENTIST->value => [25, 49],
            self::GENIUS->value => [50, '*']
        ];
    }

    public function getDegreeFromBookCount(int $count)
    {
        
    }


}